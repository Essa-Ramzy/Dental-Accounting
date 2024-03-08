<?php

namespace App\Http\Controllers;

use App\Models\Entries;
use App\Models\Customer;
use App\Models\Item;

class MainTableController extends Controller
{
    public function index()
    {
        $entries = Entries::all();
        $view = Route('addEntry');
        return view('entries.index', compact('entries', 'view'));
    }

    public function create()
    {
        $customers = Customer::all();
        $items = Item::all();
        return view('forms.add_entry', compact('customers', 'items'));
    }

    public function store()
    {
        $data = request()->validate([
            'name' => 'required',
            'item' => 'required',
            'date' => 'nullable|date',
            'teeth' => 'required',
            'discount' => 'nullable',
            'description' => 'nullable',
        ]);

        $customer = Customer::where('name', '=', request('name'))->first();
        $item = Item::where('name', '=', request('item'))->first();

        $data['customer_id'] = $customer->id;
        $data['item_id'] = $item->id;

        $data['price'] = $item->price * count($data['teeth']) - $data['discount'];
        $data['cost'] = $item->cost * count($data['teeth']);

        $result = [];
        $sub = '';

        for ($i = 0; $i < count($data['teeth']); $i++) {
            if ($i == 0) {
                $sub = $data['teeth'][$i];
            } else {
                if (substr($data['teeth'][$i], 0, 2) != substr($data['teeth'][$i - 1], 0, 2)) {
                    $result[] = $sub;
                    $sub = $data['teeth'][$i];
                } else {
                    $sub .= substr($data['teeth'][$i], 3);
                }
            }
        }

        $result[] = $sub;
        $data['teeth'] = implode(', ', $result);


        if ($data['date'] == null)
            $data['date'] = now();

        if ($data['discount'] == null)
            $data['discount'] = 0;

        if ($data['description'] == null)
            $data['description'] = 'N/A';

        Entries::create($data);

        return redirect('/');
    }

    public function delete($id)
    {
        Entries::destroy($id);
        return redirect('/');
    }

    public function searchCustomer($id)
    {
        $entries = Entries::all();
        $view = Route('addEntry');
        $customer = Customer::find($id)->name;
        return view('entries.index', compact('entries', 'customer', 'view'));
    }

    public function searchItem($id)
    {
        $entries = Entries::all();
        $view = Route('addEntry');
        $item = Item::find($id)->name;
        return view('entries.index', compact('entries', 'item', 'view'));
    }
}
