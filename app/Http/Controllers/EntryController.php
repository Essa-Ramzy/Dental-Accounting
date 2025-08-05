<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Customer;
use App\Models\Item;
use Spatie\LaravelPdf\Facades\Pdf;

class EntryController extends Controller
{
    public function index()
    {
        $entries = Entry::with(['customer', 'item'])->get();
        $view = "Entry";
        return view('entries.index', compact('entries', 'view'));
    }

    private function searchFunc($request)
    {
        if ($request['search']) {
            if ($request['filter'] == 'all') {
                $entries = Entry::with(['customer', 'item'])
                    ->where('id', 'like', '%' . $request['search'] . '%')
                    ->orWhereHas('customer', function ($query) use ($request) {
                        $query->where('name', 'like', '%' . $request['search'] . '%');
                    })->orWhereHas('item', function ($query) use ($request) {
                        $query->where('name', 'like', '%' . $request['search'] . '%');
                    })->orWhere('teeth', 'like', '%' . $request['search'] . '%')
                    ->orWhere('amount', 'like', '%' . $request['search'] . '%')
                    ->orWhere('unit_price', 'like', '%' . $request['search'] . '%')
                    ->orWhere('discount', 'like', '%' . $request['search'] . '%')
                    ->orWhere('price', 'like', '%' . $request['search'] . '%')
                    ->orWhere('cost', 'like', '%' . $request['search'] . '%')->get();
            } else if ($request['filter'] == 'name') {
                $entries = Entry::with(['customer', 'item'])->whereHas('customer', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request['search'] . '%');
                })->get();
            } else if ($request['filter'] == 'item') {
                $entries = Entry::with(['customer', 'item'])->whereHas('item', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request['search'] . '%');
                })->get();
            } else {
                $entries = Entry::with(['customer', 'item'])->where($request['filter'], 'like', '%' . $request['search'] . '%')->get();
            }
        } else {
            $entries = Entry::with(['customer', 'item'])->get();
        }
        if ($request['from_date']) {
            $entries = $entries->where('date', '>=', $request['from_date']);
        }
        if ($request['to_date']) {
            $entries = $entries->where('date', '<=', $request['to_date']);
        }
        return $entries;
    }

    public function search()
    {
        if (request()->ajax()) {
            $entries = $this->searchFunc(request()->all());
            $body = '';
            foreach ($entries as $entry) {
                $body .= "
                <tr>
                    <th scope=\"row\">{$entry->id}</th>
                    <td>{$entry->date->format('d-m-Y')}</td>
                    <td>{$entry->customer->name}</td>
                    <td>{$entry->item->name}</td>
                    <td>{$entry->teeth}</td>
                    <td>{$entry->amount}</td>
                    <td>{$entry->unit_price}</td>
                    <td>{$entry->discount}</td>
                    <td>{$entry->price}</td>
                    <td>{$entry->cost}</td>
                    <td>
                        <div class=\"text-end\">
                            <a class=\"text-decoration-none\" data-bs-toggle=\"modal\" href=\"#deleteModal\" id=\"{$entry->id}\">
                                <svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"#FFFFFF\" width=\"24\" height=\"24\"
                                     viewBox=\"0 0 24 24\">
                                    <path
                                        d=\"M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zm2.46-7.12l1.41-1.41L12 12.59l2.12-2.12 1.41 1.41L13.41 14l2.12 2.12-1.41 1.41L12 15.41l-2.12 2.12-1.41-1.41L10.59 14l-2.13-2.12zM15.5 4l-1-1h-5l-1 1H5v2h14V4z\"/>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>";
            }
            $footer = "
            <tr>
                <th scope=\"row\" colspan=\"8\" class=\"text-md-center\">Number of Entries: {$entries->count()}</th>
                <td>Total: {$entries->sum('price')}</td>
                <td>Total: {$entries->sum('cost')}</td>
                <td>
                    <div class=\"text-end\">
                        <a class=\"text-decoration-none\" data-bs-toggle=\"modal\" href=\"#deleteModal\">
                            <svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"#FFFFFF\" width=\"24\" height=\"24\"
                            viewBox=\"0 0 24 24\">
                                <path
                                    d=\"M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zm2.46-7.12l1.41-1.41L12 12.59l2.12-2.12 1.41 1.41L13.41 14l2.12 2.12-1.41 1.41L12 15.41l-2.12 2.12-1.41-1.41L10.59 14l-2.13-2.12zM15.5 4l-1-1h-5l-1 1H5v2h14V4z\"/>
                            </svg>
                        </a>
                    </div>
                </td>
            </tr>";
            return response()->json(['body' => $body, 'footer' => $footer]);
        }
        return redirect(route('Home'));
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

        $data['amount'] = count($data['teeth']);
        $data['unit_price'] = $item->price;

        $data['price'] = $data['unit_price'] * $data['amount'] - $data['discount'];
        $data['cost'] = $item->cost * $data['amount'];

        $result = [];
        $sub = '';

        for ($i = 0; $i < $data['amount']; $i++) {
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

        Entry::create($data);

        return redirect(route('Home'));
    }

    public function delete()
    {
        $this->searchFunc(request()->all())->each->delete();
        return redirect(route('Home'));
    }

    public function customerRecords($id)
    {
        $customer = Customer::find($id)->name;
        return redirect(route('Home'))->with(compact('customer'));
    }

    public function itemRecords($id)
    {
        $item = Item::find($id)->name;
        return redirect(route('Home'))->with(compact('item'));
    }

    public function export()
    {
        $columns = request()->except(['_token', 'filter', 'search', 'from_date', 'to_date']);
        $entries = $this->searchFunc(request()->all());
        $count = count($columns) - isset($columns['price']) - isset($columns['cost']);
        $pdf = Pdf::view('pdf.entry_pdf', compact('entries', 'columns', 'count'));
        return $pdf->format('A4')->landscape()->download('entries.pdf');
    }
}
