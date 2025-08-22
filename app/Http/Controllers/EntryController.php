<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Entry;
use App\Models\Customer;
use Illuminate\Support\Str;
use Spatie\LaravelPdf\Facades\Pdf;
use App\Traits\ProvidesTrashedCount;

class EntryController extends Controller
{
    use ProvidesTrashedCount;

    protected $model = Entry::class;

    public function __construct()
    {
        $this->shareTrashedCount();
    }

    public function index()
    {
        $entries = Entry::with(['customer', 'item']);
        $all = $entries->get();
        $footer = [
            'count' => $all->count(),
            'total_price' => $all->sum('price'),
            'total_cost' => $all->sum('cost')
        ];
        $entries = $entries->paginate(50);
        $trash = false;
        if (request()->ajax()) {
            $body = view('partials.entries-body', compact('entries', 'trash'))->render();
            $footer = view('partials.entries-footer', compact('entries', 'footer', 'trash'))->render();
            $links = $entries->appends(request()->except('page'))->links()->toHtml();
            return response()->json(compact('body', 'footer', 'links'));
        } else {
            return view('pages.entries', compact('entries', 'footer', 'trash'));
        }
    }

    private function searchFunc($trash = false)
    {
        if ($trash) {
            $query = Entry::onlyTrashed()->with([
                'customer' => fn($q) => $q->withTrashed(),
                'item' => fn($q) => $q->withTrashed()
            ]);
        } else {
            $query = Entry::with(['customer', 'item']);
        }

        if (request('search')) {
            if (request('filter') == 'all') {
                $query->where(
                    fn($q) =>
                    $q->where('id', 'like', '%' . request('search') . '%')
                        ->orWhereHas('customer', function ($subQuery) use ($trash) {
                            if ($trash) {
                                $subQuery->withTrashed();
                            }
                            $subQuery->where('name', 'like', '%' . request('search') . '%');
                        })->orWhereHas('item', function ($subQuery) use ($trash) {
                            if ($trash) {
                                $subQuery->withTrashed();
                            }
                            $subQuery->where('name', 'like', '%' . request('search') . '%');
                        })->orWhere('teeth', 'like', '%' . request('search') . '%')
                        ->orWhere('amount', 'like', '%' . request('search') . '%')
                        ->orWhere('unit_price', 'like', '%' . request('search') . '%')
                        ->orWhere('discount', 'like', '%' . request('search') . '%')
                        ->orWhere('price', 'like', '%' . request('search') . '%')
                        ->orWhere('cost', 'like', '%' . request('search') . '%')
                );
            } else if (request('filter') == 'name') {
                $query->whereHas('customer', function ($subQuery) use ($trash) {
                    if ($trash) {
                        $subQuery->withTrashed();
                    }
                    $subQuery->where('name', 'like', '%' . request('search') . '%');
                });
            } else if (request('filter') == 'item') {
                $query->whereHas('item', function ($subQuery) use ($trash) {
                    if ($trash) {
                        $subQuery->withTrashed();
                    }
                    $subQuery->where('name', 'like', '%' . request('search') . '%');
                });
            } else {
                $query->where(request('filter'), 'like', '%' . request('search') . '%');
            }
        }

        if (request('from_date')) {
            $query->where($trash ? 'deleted_at' : 'date', '>=', request('from_date'));
        }
        if (request('to_date')) {
            $query->where($trash ? 'deleted_at' : 'date', '<=', request('to_date'));
        }

        return $query;
    }

    public function search()
    {
        if (request()->ajax()) {
            $trash = url()->previous() == route('Entry.trash');
            $entries = $this->searchFunc(trash: $trash);
            $all = $entries->get();
            $footer = [
                'count' => $all->count(),
                'total_price' => $all->sum('price'),
                'total_cost' => $all->sum('cost')
            ];
            $entries = $entries->paginate(50);
            $body = view('partials.entries-body', compact('entries', 'trash'))->render();
            $footer = view('partials.entries-footer', compact('entries', 'footer', 'trash'))->render();
            $links = $entries->appends(request()->except('page'))->links()->toHtml();
            return response()->json(compact('body', 'footer', 'links'));
        }
        return redirect()->back();
    }

    public function create()
    {
        if (
            !collect([
                route('Entry.create'),
                route('Customer.create'),
                route('Item.create'),
            ])->contains(url()->previous())
        ) {
            session()->put('entry_previous_url', url()->previous());
        }
        $customers = Customer::all();
        $items = Item::all();
        return view('forms.add-entry', compact('customers', 'items'))
            ->with('previous_url', session('entry_previous_url'));
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
                'numeric',
                'min:0',
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

        // Set discount to 0 if not provided
        if (!isset($data['discount']) || $data['discount'] === null) {
            $data['discount'] = 0;
        }

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

        if (!isset($data['date']) || $data['date'] == null)
            $data['date'] = now();

        Entry::create($data);
        $previous_url = session()->get('entry_previous_url', route('Entry.index'));
        if ($previous_url == route('Entry.index') or $previous_url == Str::finish(route('Home'), '/')) {
            $redirect = redirect(route('Entry.create'))
                ->with('success', 'Entry created successfully.');
        } else {
            session()->forget('entry_previous_url');
            $redirect = redirect($previous_url);
        }

        return $redirect;
    }

    public function delete()
    {
        if (request('filter') == 'single') {
            Entry::findOrFail(request('search'))->delete();
        } else {
            $this->searchFunc()->get()->each->delete();
        }
        return redirect(route('Entry.index'));
    }

    public function edit($id)
    {
        $entry = Entry::findOrFail($id);
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
                'numeric',
                'min:0',
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

        // Set discount to 0 if not provided
        if (!isset($data['discount']) || $data['discount'] === null) {
            $data['discount'] = 0;
        }

        $data['price'] = ($data['unit_price'] ?? $item->price) * $data['amount'] - $data['discount'];
        $data['cost'] = ($data['cost'] ?? $item->cost) * $data['amount'];

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

        if (!isset($data['date']) || $data['date'] == null)
            $data['date'] = now();

        Entry::findOrFail($id)->update($data);

        return redirect(route('Entry.index'));
    }

    public function export()
    {
        $columns = request()->except(['_token', 'filter', 'search', 'from_date', 'to_date']);
        $entries = $this->searchFunc()->get();
        $count = count($columns) - isset($columns['price']) - isset($columns['cost']);
        $pdf = Pdf::view('pdf.entry-pdf', compact('entries', 'columns', 'count'));
        return $pdf->format('A3')->landscape()->download('entries.pdf');
    }

    public function trash()
    {
        $entries = Entry::onlyTrashed()->with([
            'customer' => fn($query) => $query->withTrashed(),
            'item' => fn($query) => $query->withTrashed()
        ]);
        $all = $entries->get();
        $footer = [
            'count' => $all->count(),
            'total_price' => $all->sum('price'),
            'total_cost' => $all->sum('cost')
        ];
        $entries = $entries->paginate(50);
        $trash = true;
        if (request()->ajax()) {
            $body = view('partials.entries-body', compact('entries', 'trash'))->render();
            $footer = view('partials.entries-footer', compact('entries', 'footer', 'trash'))->render();
            $links = $entries->appends(request()->except('page'))->links()->toHtml();
            return response()->json(compact('body', 'footer', 'links'));
        } else {
            return view('pages.entries', compact('entries', 'footer', 'trash'));
        }
    }

    public function restore()
    {
        $failed = [];
        $restored = 0;

        try {
            if (request('filter') == 'single') {
                $entry = Entry::onlyTrashed()->findOrFail(request('search'));
                $result = $this->canRestore($entry);
                if ($result['can_restore']) {
                    $entry->restore();
                    $restored++;
                } else {
                    $failed[] = "Entry #{$entry->id} - {$result['reason']}";
                }
            } else {
                $entries = $this->searchFunc(trash: true)->get();
                foreach ($entries as $entry) {
                    $result = $this->canRestore($entry);
                    if ($result['can_restore']) {
                        $entry->restore();
                        $restored++;
                    } else {
                        $failed[] = "Entry #{$entry->id} - {$result['reason']}";
                    }
                }
            }
        } catch (\Exception $e) {
            if (request('filter') == 'single') {
                $failed[] = 'Unknown entry - Error occurred during restore';
            } else {
                $failed[] = 'Multiple entries failed to restore due to errors';
            }
        }

        $message = '';
        if ($restored > 0) {
            $message .= $restored . ' entry(s) restored successfully.';
        }

        return redirect(route('Entry.trash'))
            ->with('success', $message)
            ->with('failed_entries', $failed);
    }

    private function canRestore(Entry $entry)
    {
        // Load relationships with trashed models
        $customer = $entry->customer()->withTrashed()->first();
        $item = $entry->item()->withTrashed()->first();
        $return = ['can_restore' => true, 'reason' => ''];

        // Check if customer exists
        if (!$customer) {
            $return = ['can_restore' => false, 'reason' => 'Customer no longer exists'];
        }

        // Check if item exists
        if (!$item) {
            $return = ['can_restore' => false, 'reason' => $return['can_restore'] ? 'Item no longer exists' : 'Customer and Item no longer exist'];
        }

        if (!$return['can_restore']) {
            return $return;
        }

        // If customer is trashed, check if we can restore it
        if ($customer->trashed()) {
            $activeCustomerWithSameName = Customer::where('name', $customer->name)
                ->whereNull('deleted_at')
                ->where('id', '!=', $customer->id)
                ->exists();

            if ($activeCustomerWithSameName) {
                $return = ['can_restore' => false, 'reason' => 'Customer name already exists'];
            }
        }

        // If item is trashed, check if we can restore it
        if ($item->trashed()) {
            $activeItemWithSameName = Item::where('name', $item->name)
                ->whereNull('deleted_at')
                ->where('id', '!=', $item->id)
                ->exists();

            if ($activeItemWithSameName) {
                $return = ['can_restore' => false, 'reason' => $return['can_restore'] ? 'Item name already exists' : 'Customer name and Item name already exist'];
            }
        }

        return $return;
    }

    public function forceDelete()
    {
        if (request('filter') == 'single') {
            Entry::onlyTrashed()->findOrFail(request('search'))->forceDelete();
        } else {
            $this->searchFunc(trash: true)->get()->each->forceDelete();
        }
        return redirect(route('Entry.trash'));
    }
}
