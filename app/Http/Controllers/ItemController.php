<?php

namespace App\Http\Controllers;

use App\Models\Item;

class ItemController extends Controller
{
    public function index()
    {
        $entries = Item::all();
        $view = "Item";
        return view('entries.items', compact('entries', 'view'));
    }

    public function create()
    {
        return view('forms.add_item');
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

        return redirect('/items');
    }

    public function delete($id)
    {
        Item::destroy($id);
        return redirect('/items');
    }

    public function edit($id)
    {
        $item = Item::find($id);
        return view('forms.edit_item', compact('item'));
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

        return redirect('/items');
    }
}
