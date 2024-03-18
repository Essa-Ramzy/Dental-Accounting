<?php

namespace App\Http\Controllers;

use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $entries = Customer::all();
        $view = "Customer";
        return view('entries.customers', compact('entries', 'view'));
    }

    public function create()
    {
        return view('forms.add_customer');
    }

    public function store()
    {
        $data = request()->validate([
            'name' => ['required', 'unique:customers']
        ]);

        Customer::create($data);

        return redirect('/customers');
    }

    public function delete($id)
    {
        Customer::destroy($id);
        return redirect('/customers');
    }

    public function edit($id)
    {
        $customer = Customer::find($id);
        return view('forms.edit_customer', compact('customer'));
    }

    public function update($id)
    {
        $data = request()->validate([
            'name' => ['required', 'unique:customers,name,' . $id]
        ]);

        Customer::whereId($id)->update($data);

        return redirect('/customers');
    }
}
