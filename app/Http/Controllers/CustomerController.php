<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Support\Carbon;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        $view = "Customer";
        return view('entries.customers', compact('customers', 'view'));
    }

    private function searchFunc()
    {
        if (request('search')) {
            if (request('filter') == 'all') {
                $customers = Customer::where('id', 'like', '%' . request('search') . '%')
                    ->orWhere('name', 'like', '%' . request('search') . '%')->get();
            } else {
                $customers = Customer::where(request('filter'), 'like', '%' . request('search') . '%')->get();
            }
        } else {
            $customers = Customer::all();
        }
        if (request('from_date')) {
            $customers = $customers->where('updated_at', '>=', request('from_date'));
        }
        if (request('to_date')) {
            $customers = $customers->where('updated_at', '<=', request('to_date'));
        }
        return $customers;
    }

    public function search()
    {
        if (request()->ajax()) {
            $customers = $this->searchFunc();
            return response()->json([
                'body' => $customers->map(function ($customer) {
                    $customer = $customer->toArray();
                    return [
                        'id' => $customer['id'],
                        'date' => Carbon::parse($customer['updated_at'])->format('d-m-Y'),
                        'name' => $customer['name'],
                    ];
                }),
                'footer' => [
                    'count' => $customers->count(),
                ],
            ]);
        } else {
            return redirect(route('Customers'));
        }
    }

    public function create()
    {
        if (url()->previous() != route('Customer.create')) {
            session()->put('customer_previous_url', url()->previous());
        }
        return view('forms.add-customer')
            ->with('previousUrl', session('customer_previous_url'));
    }

    public function store()
    {
        $data = request()->validate([
            'name' => ['required', 'unique:customers']
        ]);

        Customer::create($data);

        return redirect(session()->get('customer_previous_url', route('Customers')))
            ->with('createdCustomerId', Customer::latest()->first()->id);
    }

    public function delete()
    {
        $this->searchFunc()->each->delete();
        return redirect(route('Customers'));
    }

    public function edit($id)
    {
        $customer = Customer::find($id);
        return view('forms.edit-customer', compact('customer'));
    }

    public function update($id)
    {
        $data = request()->validate([
            'name' => ['required', 'unique:customers,name,' . $id]
        ]);

        Customer::whereId($id)->update($data);

        return redirect(route('Customers'));
    }
}
