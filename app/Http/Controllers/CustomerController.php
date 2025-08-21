<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Support\Str;
use App\Traits\ProvidesTrashedCount;

class CustomerController extends Controller
{
    use ProvidesTrashedCount;
    protected $model = Customer::class;

    public function __construct()
    {
        $this->shareTrashedCount();
    }

    public function index()
    {
        $customers = Customer::withCount('entries')->paginate(50);
        $trash = false;
        if (request()->ajax()) {
            $body = view('partials.customers-body', compact('customers', 'trash'))->render();
            $footer = view('partials.customers-footer', compact('customers', 'trash'))->render();
            $links = $customers->appends(request()->except('page'))->links()->toHtml();
            return response()->json(compact('body', 'footer', 'links'));
        } else {
            return view('pages.customers', compact('customers', 'trash'));
        }
    }

    private function searchFunc($trash = false)
    {
        if ($trash) {
            $query = Customer::onlyTrashed();
        } else {
            $query = Customer::withCount('entries');
        }

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
            $trash = url()->previous() == route('Customer.trash');
            $customers = $this->searchFunc(trash: $trash)->paginate(50);
            $body = view('partials.customers-body', compact('customers', 'trash'))->render();
            $footer = view('partials.customers-footer', compact('customers', 'trash'))->render();
            $links = $customers->appends(request()->except('page'))->links()->toHtml();
            return response()->json(compact('body', 'footer', 'links'));
        }
        return redirect()->back();
    }

    public function create()
    {
        if (url()->previous() != route('Customer.create')) {
            session()->put('customer_previous_url', url()->previous());
        }
        $countTrash = Customer::onlyTrashed()->count();
        return view('forms.add-customer')
            ->with('previousUrl', session('customer_previous_url', route('Customer.index')))
            ->with('countTrash', $countTrash);
    }

    public function store()
    {
        $data = request()->validate([
            'name' => 'required|unique:customers,name,NULL,id,deleted_at,NULL'
        ]);

        Customer::create($data);
        $previous_url = session()->get('customer_previous_url', route('Customer.index'));
        if ($previous_url == route('Customer.index') or $previous_url == Str::finish(route('Home'), '/')) {
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
        return redirect(route('Customer.index'));
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

        return redirect(route('Customer.index'));
    }

    public function records($id)
    {
        $customer = Customer::findOrFail($id)->name;
        return redirect(route('Entry.index'))->with(compact('customer'));
    }

    public function trash()
    {
        $customers = Customer::onlyTrashed()->paginate(50);
        $trash = true;
        if (request()->ajax()) {
            $body = view('partials.customers-body', compact('customers', 'trash'))->render();
            $footer = view('partials.customers-footer', compact('customers', 'trash'))->render();
            $links = $customers->appends(request()->except('page'))->links()->toHtml();
            return response()->json(compact('body', 'footer', 'links'));
        }
        return view('pages.customers', compact('customers', 'trash'));
    }

    public function restore()
    {
        $failed = [];
        $restored = 0;

        try {
            if (request('filter') == 'single') {
                $customer = Customer::onlyTrashed()->findOrFail(request('search'));
                if ($this->canRestore($customer)) {
                    $customer->restore();
                    $restored++;
                } else {
                    $failed[] = $customer->name . ' - Name already exists';
                }
            } else {
                $customers = $this->searchFunc(trash: true)->get();
                foreach ($customers as $customer) {
                    if ($this->canRestore($customer)) {
                        $customer->restore();
                        $restored++;
                    } else {
                        $failed[] = $customer->name . ' - Name already exists';
                    }
                }
            }
        } catch (\Exception $e) {
            if (request('filter') == 'single') {
                $failed[] = 'Unknown customer - Error occurred during restore';
            } else {
                $failed[] = 'Multiple customers failed to restore due to errors';
            }
        }

        $message = '';
        if ($restored > 0) {
            $message .= $restored . ' customer(s) restored successfully.';
        }

        return redirect(route('Customer.trash'))
            ->with('success', $message)
            ->with('failed_customers', $failed);
    }

    private function canRestore(Customer $customer)
    {
        // Check if another customer with same name exists (not trashed)
        return !Customer::where('name', $customer->name)
            ->whereNull('deleted_at')
            ->where('id', '!=', $customer->id)
            ->exists();
    }

    public function forceDelete()
    {
        if (request('filter') == 'single') {
            Customer::onlyTrashed()->findOrFail(request('search'))->forceDelete();
        } else {
            $this->searchFunc(trash: true)->get()->each->forceDelete();
        }
        return redirect(route('Customer.trash'));
    }
}
