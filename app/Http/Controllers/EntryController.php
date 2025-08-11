<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Customer;
use App\Models\Item;
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Support\Carbon;

class EntryController extends Controller
{
    public function index()
    {
        $entries = Entry::with(['customer', 'item'])->get();
        $view = "Entry";
        return view('pages.entries', compact('entries', 'view'));
    }

    private function searchFunc()
    {
        if (request('search')) {
            if (request('filter') == 'all') {
                $entries = Entry::with(['customer', 'item'])
                    ->where('id', 'like', '%' . request('search') . '%')
                    ->orWhereHas('customer', function ($query) {
                        $query->where('name', 'like', '%' . request('search') . '%');
                    })->orWhereHas('item', function ($query) {
                        $query->where('name', 'like', '%' . request('search') . '%');
                    })->orWhere('teeth', 'like', '%' . request('search') . '%')
                    ->orWhere('amount', 'like', '%' . request('search') . '%')
                    ->orWhere('unit_price', 'like', '%' . request('search') . '%')
                    ->orWhere('discount', 'like', '%' . request('search') . '%')
                    ->orWhere('price', 'like', '%' . request('search') . '%')
                    ->orWhere('cost', 'like', '%' . request('search') . '%')->get();
            } else if (request('filter') == 'name') {
                $entries = Entry::with(['customer', 'item'])->whereHas('customer', function ($query) {
                    $query->where('name', 'like', '%' . request('search') . '%');
                })->get();
            } else if (request('filter') == 'item') {
                $entries = Entry::with(['customer', 'item'])->whereHas('item', function ($query) {
                    $query->where('name', 'like', '%' . request('search') . '%');
                })->get();
            } else {
                $entries = Entry::with(['customer', 'item'])->where(request('filter'), 'like', '%' . request('search') . '%')->get();
            }
        } else {
            $entries = Entry::with(['customer', 'item'])->get();
        }
        if (request('from_date')) {
            $entries = $entries->where('date', '>=', request('from_date'));
        }
        if (request('to_date')) {
            $entries = $entries->where('date', '<=', request('to_date'));
        }
        return $entries;
    }

    public function search()
    {
        if (request()->ajax()) {
            $entries = $this->searchFunc();
            return response()->json([
                'body' => $entries
                    ->map(function ($entry) {
                        $entry = $entry->toArray();
                        return [
                            'id' => $entry['id'],
                            'date' => Carbon::parse($entry['date'])->format('d-m-Y'),
                            'customer_name' => $entry['customer']['name'],
                            'item_name' => $entry['item']['name'],
                            'teeth' => $entry['teeth'],
                            'amount' => $entry['amount'],
                            'unit_price' => $entry['unit_price'],
                            'discount' => $entry['discount'],
                            'price' => $entry['price'],
                            'cost' => $entry['cost'],
                            'edit_link' => route('Entry.edit', $entry['id'])
                        ];
                    }),
                'footer' => [
                    'count' => $entries->count(),
                    'total_price' => $entries->sum('price'),
                    'total_cost' => $entries->sum('cost')
                ],
                'teeth_list' => $entries->mapWithKeys(function ($entry) {
                    return [
                        $entry->id => view('components.teeth-visual', ['selectedTeeth' => $entry->teeth_list])->render()
                    ];
                })
            ]);
        }
        return redirect(route('Entries'));
    }

    public function create()
    {
        $customers = Customer::all();
        $items = Item::all();
        return view('forms.add-entry', compact('customers', 'items'));
    }

    public function store()
    {
        $data = request()->validate([
            'name' => 'required',
            'item' => 'required',
            'date' => 'nullable|date',
            'teeth' => 'required',
            'discount' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    $item = Item::where('name', request('item'))->first();
                    $price = $item ? $item->price : 0;
                    $total = $price * count(request('teeth') ?? []);
                    if ($value > $total) {
                        $fail('Discount cannot exceed total price');
                    }
                }
            ],
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

        return redirect()->back()
            ->with('success', 'Entry created successfully.');
    }

    public function delete()
    {
        $this->searchFunc()->each->delete();
        return redirect(route('Entries'));
    }

    public function edit($id)
    {
        $entry = Entry::find($id);
        $customers = Customer::all();
        $items = Item::all();
        return view('forms.edit-entry', compact('entry', 'customers', 'items'));
    }

    public function update($id)
    {
        $data = request()->validate([
            'name' => 'required',
            'item' => 'required',
            'unit_price' => 'nullable|numeric',
            'cost' => 'nullable|numeric',
            'date' => 'nullable|date',
            'teeth' => 'required',
            'discount' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    $total = (request('unit_price', 0)) * count(request('teeth', []));
                    if ($value > $total) {
                        $fail('Discount cannot exceed total price');
                    }
                }
            ],
        ]);

        $customer = Customer::where('name', '=', request('name'))->first();
        $item = Item::where('name', '=', request('item'))->first();

        $data['customer_id'] = $customer->id;
        $data['item_id'] = $item->id;

        $data['amount'] = count($data['teeth']);
        $data['price'] = ($data['unit_price'] ?? $item->price) * $data['amount'] - $data['discount'];
        $data['cost'] = $data['cost'] ?? $item->cost * $data['amount'];

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

        Entry::find($id)->update($data);

        return redirect(route('Entries'));
    }

    public function customerRecords($id)
    {
        $customer = Customer::find($id)->name;
        return redirect(route('Entries'))->with(compact('customer'));
    }

    public function itemRecords($id)
    {
        $item = Item::find($id)->name;
        return redirect(route('Entries'))->with(compact('item'));
    }

    public function export()
    {
        $columns = request()->except(['_token', 'filter', 'search', 'from_date', 'to_date']);
        $entries = $this->searchFunc();
        $count = count($columns) - isset($columns['price']) - isset($columns['cost']);
        $pdf = Pdf::view('pdf.entry-pdf', compact('entries', 'columns', 'count'));
        return $pdf->format('A4')->landscape()->download('entries.pdf');
    }
}
