<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        $view = "Item";
        return view('pages.items', compact('items', 'view'));
    }

    private function searchFunc()
    {
        if (request('search')) {
            if (request('filter') == 'all') {
                $items = Item::with("entries")
                    ->where('id', 'like', '%' . request('search') . '%')
                    ->orWhere('name', 'like', '%' . request('search') . '%')
                    ->orWhere('price', 'like', '%' . request('search') . '%')
                    ->orWhere('cost', 'like', '%' . request('search') . '%')
                    ->orWhere('description', 'like', '%' . request('search') . '%')->get();
            } else {
                $items = Item::with("entries")
                    ->where(request('filter'), 'like', '%' . request('search') . '%')->get();
            }
        } else {
            $items = Item::with("entries")->get();
        }
        if (request('from_date')) {
            $items = $items->where('updated_at', '>=', request('from_date'));
        }
        if (request('to_date')) {
            $items = $items->where('updated_at', '<=', request('to_date'));
        }
        return $items;
    }

    public function search()
    {
        if (request()->ajax()) {
            $items = $this->searchFunc();
            return response()->json([
                'body' => $items->map(function ($item) {
                    $item = $item->toArray();
                    return [
                        'id' => $item['id'],
                        'date' => Carbon::parse($item['updated_at'])->format('M d, Y'),
                        'name' => $item['name'],
                        'price' => $item['price'],
                        'cost' => $item['cost'],
                        'description' => $item['description'],
                        'entries_count' => count($item['entries']),
                        'edit_link' => route('Item.edit', $item['id']),
                        'record_link' => route('Item.records', $item['id'])
                    ];
                }),
                'footer' => [
                    'count' => $items->count(),
                    'create_link' => route('Item.create')
                ],
            ]);
        } else {
            return redirect(route('Items'));
        }
    }

    public function create()
    {
        if (url()->previous() != route('Item.create')) {
            session()->put('item_previous_url', url()->previous());
        }
        return view('forms.add-item')
            ->with('previousUrl', session('item_previous_url', route('Items')));
    }

    public function store()
    {
        $data = request()->validate([
            'name' => 'required|unique:items',
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'description' => 'nullable'
        ]);

        if (empty($data['description'])) {
            $data['description'] = "N/A";
        }

        Item::create($data);
        $previous_url = session()->get('item_previous_url', route('Items'));
        if ($previous_url == route('Items') or $previous_url == Str::finish(route('Home'), '/')) {
            $redirect = redirect()->back()
                ->with('success', 'Item created successfully.');
        } else {
            session()->forget('item_previous_url');
            $redirect = redirect($previous_url)
                ->with('createdItemId', Item::latest()->first()->id);
        }

        return $redirect;
    }

    public function delete()
    {
        $this->searchFunc()->each->delete();
        return redirect(route('Items'));
    }

    public function edit($id)
    {
        $item = Item::find($id);
        return view('forms.edit-item', compact('item'));
    }

    public function update($id)
    {
        $data = request()->validate([
            'name' => 'required|unique:items,name,' . $id,
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'description' => 'nullable'
        ]);

        if (empty($data['description'])) {
            $data['description'] = "N/A";
        }

        Item::find($id)->update($data);

        return redirect(route('Items'));
    }
}
