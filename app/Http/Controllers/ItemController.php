<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Carbon;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        $view = "Item";
        return view('entries.items', compact('items', 'view'));
    }

    private function searchFunc()
    {
        if (request('search')) {
            if (request('filter') == 'all') {
                $items = Item::where('id', 'like', '%' . request('search') . '%')
                    ->orWhere('name', 'like', '%' . request('search') . '%')
                    ->orWhere('price', 'like', '%' . request('search') . '%')
                    ->orWhere('cost', 'like', '%' . request('search') . '%')
                    ->orWhere('description', 'like', '%' . request('search') . '%')->get();
            } else {
                $items = Item::where(request('filter'), 'like', '%' . request('search') . '%')->get();
            }
        } else {
            $items = Item::all();
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
                        'date' => Carbon::parse($item['updated_at'])->format('d-m-Y'),
                        'name' => $item['name'],
                        'price' => $item['price'],
                        'cost' => $item['cost'],
                        'description' => $item['description'],
                    ];
                }),
                'footer' => [
                    'count' => $items->count(),
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
            ->with('previousUrl', session('item_previous_url'));
    }

    public function store()
    {
        $data = request()->validate([
            'name' => ['required', 'unique:items'],
            'price' => ['required', 'numeric'],
            'cost' => ['required', 'numeric'],
            'description' => ['nullable']
        ]);

        if (empty($data['description'])) {
            $data['description'] = "N/A";
        }

        Item::create($data);

        return redirect(session()->get('item_previous_url', route('Items')))
            ->with('createdItemId', Item::latest()->first()->id);
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
            'name' => ['required', 'unique:items,name,' . $id],
            'price' => ['required', 'numeric'],
            'cost' => ['required', 'numeric'],
            'description' => ['nullable']
        ]);

        if (empty($data['description'])) {
            $data['description'] = "N/A";
        }

        Item::where('id', $id)->update($data);

        return redirect(route('Items'));
    }
}
