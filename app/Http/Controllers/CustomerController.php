<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::withCount('entries');
        $all = $customers->get();
        $footer = [
            'count' => $all->count(),
        ];
        $customers = $customers->paginate(50);
        if (request()->ajax()) {
            $body = view('pages.partials.customers-body', compact('customers'))->render();
            $footer = view('pages.partials.customers-footer', compact('customers', 'footer'))->render();
            $links = $customers->appends(request()->except('page'))->links()->toHtml();
            return response()->json(compact('body', 'footer', 'links'));
        } else {
            $view = "Customer";
            return view('pages.customers', compact('customers', 'view', 'footer'));
        }
    }

    private function searchFunc()
    {
        $query = Customer::withCount('entries');

        if (request('search')) {
            if (request('filter') == 'all') {
                $query->where(
                    fn($q) =>
                    $q->where('id', 'like', '%' . request('search') . '%')
                        ->orWhere('name', 'like', '%' . request('search') . '%')
                );
            } else {
                $query->where(request('filter'), 'like', '%' . request('search') . '%');
            }
        }

        if (request('from_date')) {
            $query->where('updated_at', '>=', request('from_date'));
        }
        if (request('to_date')) {
            $query->where('updated_at', '<=', request('to_date'));
        }

        return $query;
    }

    public function search()
    {
        if (request()->ajax()) {
            $customers = $this->searchFunc();
            $all = $customers->get();
            $footer = [
                'count' => $all->count(),
            ];
            $customers = $customers->paginate(50);
            $body = view('pages.partials.customers-body', compact('customers'))->render();
            $footer = view('pages.partials.customers-footer', compact('customers', 'footer'))->render();
            $links = $customers->appends(request()->except('page'))->links()->toHtml();
            return response()->json(compact('body', 'footer', 'links'));
        }
        return redirect(route('Customers'));
    }

    public function create()
    {
        if (url()->previous() != route('Customer.create')) {
            session()->put('customer_previous_url', url()->previous());
        }
        return view('forms.add-customer')
            ->with('previousUrl', session('customer_previous_url', route('Customers')));
    }

    public function store()
    {
        $data = request()->validate([
            'name' => 'required|unique:customers,name,NULL,id,deleted_at,NULL'
        ]);

        Customer::create($data);
        $previous_url = session()->get('customer_previous_url', route('Customers'));
        if ($previous_url == route('Customers') or $previous_url == Str::finish(route('Home'), '/')) {
            $redirect = redirect(route('Customer.create'))
                ->with('success', 'Customer created successfully.');
        } else {
            session()->forget('customer_previous_url');
            $redirect = redirect($previous_url)
                ->with('createdCustomerId', Customer::latest()->first()->id);
        }

        return $redirect;
    }

    public function delete()
    {
        if (request('filter') == 'single') {
            Customer::findOrFail(request('search'))->delete();
        } else {
            $this->searchFunc()->get()->each->delete();
        }
        return redirect(route('Customers'));
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('forms.edit-customer', compact('customer'));
    }

    public function update($id)
    {
        $data = request()->validate([
            'name' => 'required|unique:customers,name,' . $id . ',id,deleted_at,NULL'
        ]);

        Customer::whereId($id)->update($data);

        return redirect(route('Customers'));
    }

    public function records($id)
    {
        $customer = Customer::findOrFail($id)->name;
        return redirect(route('Entries'))->with(compact('customer'));
    }
}
