<?php

namespace App\Http\Controllers;

use App\Models\Entries;
use App\Models\Customer;
use App\Models\Item;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class MainTableController extends Controller
{
    public function index()
    {
        $entries = Entries::with(['customer', 'item'])->get();
        $view = "Entry";
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
            'discount' => 'nullable'
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
        $view = "Entry";
        $customer = Customer::find($id)->name;
        return view('entries.index', compact('entries', 'customer', 'view'));
    }

    public function searchItem($id)
    {
        $entries = Entries::all();
        $view = "Entry";
        $item = Item::find($id)->name;
        return view('entries.index', compact('entries', 'item', 'view'));
    }

    public function export()
    {
        $columns = request()->except('_token');
        $entries = Entries::with(['customer', 'item'])->get();
        $count = count($columns) - isset($columns['price']) - isset($columns['cost']);
        return view('pdf.entry_pdf', compact('entries', 'columns', 'count'));
    }
}
