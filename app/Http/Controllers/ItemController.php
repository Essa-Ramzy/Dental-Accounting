<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::withCount("entries");
        $all = $items->get();
        $footer = [
            'count' => $all->count(),
        ];
        $items = $items->paginate(50);
        if (request()->ajax()) {
            $body = view('pages.partials.items-body', compact('items'))->render();
            $footer = view('pages.partials.items-footer', compact('items', 'footer'))->render();
            $links = $items->appends(request()->except('page'))->links()->toHtml();
            return response()->json(compact('body', 'footer', 'links'));
        } else {
            $view = "Item";
            return view('pages.items', compact('items', 'view', 'footer'));
        }
    }

    private function searchFunc()
    {
        $query = Item::withCount("entries");

        if (request('search')) {
            if (request('filter') == 'all') {
                $query->where(
                    fn($q) =>
                    $q->where('id', 'like', '%' . request('search') . '%')
                        ->orWhere('name', 'like', '%' . request('search') . '%')
                        ->orWhere('price', 'like', '%' . request('search') . '%')
                        ->orWhere('cost', 'like', '%' . request('search') . '%')
                        ->orWhere('description', 'like', '%' . request('search') . '%')
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
            $items = $this->searchFunc();
            $all = $items->get();
            $footer = [
                'count' => $all->count(),
            ];
            $items = $items->paginate(50);
            $body = view('pages.partials.items-body', compact('items'))->render();
            $footer = view('pages.partials.items-footer', compact('items', 'footer'))->render();
            $links = $items->appends(request()->except('page'))->links()->toHtml();
            return response()->json(compact('body', 'footer', 'links'));
        }
        return redirect(route('Items'));
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
            'name' => 'required|unique:items,name,NULL,id,deleted_at,NULL',
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'description' => 'nullable'
        ]);

        Item::create($data);
        $previous_url = session()->get('item_previous_url', route('Items'));
        if ($previous_url == route('Items') or $previous_url == Str::finish(route('Home'), '/')) {
            $redirect = redirect(route('Item.create'))
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
        if (request('filter') == 'single') {
            Item::findOrFail(request('search'))->delete();
        } else {
            $this->searchFunc()->get()->each->delete();
        }
        return redirect(route('Items'));
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id);
        return view('forms.edit-item', compact('item'));
    }

    public function update($id)
    {
        $data = request()->validate([
            'name' => 'required|unique:items,name,' . $id . ',id,deleted_at,NULL',
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'description' => 'nullable'
        ]);

        Item::findOrFail($id)->update($data);

        return redirect(route('Items'));
    }

    public function records($id)
    {
        $item = Item::findOrFail($id)->name;
        return redirect(route('Entries'))->with(compact('item'));
    }
}
