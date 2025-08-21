<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Str;
use App\Traits\ProvidesTrashedCount;

class ItemController extends Controller
{
    use ProvidesTrashedCount;

    protected $model = Item::class;

    public function __construct()
    {
        $this->shareTrashedCount();
    }

    public function index()
    {
        $items = Item::withCount("entries")->paginate(50);
        $trash = false;
        if (request()->ajax()) {
            $body = view('partials.items-body', compact('items', 'trash'))->render();
            $footer = view('partials.items-footer', compact('items', 'trash'))->render();
            $links = $items->appends(request()->except('page'))->links()->toHtml();
            return response()->json(compact('body', 'footer', 'links'));
        } else {
            return view('pages.items', compact('items', 'trash'));
        }
    }

    private function searchFunc($trash = false)
    {
        if ($trash) {
            $query = Item::onlyTrashed();
        } else {
            $query = Item::withCount("entries");
        }

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
            $trash = url()->previous() == route('Item.trash');
            $items = $this->searchFunc(trash: $trash)->paginate(50);
            $body = view('partials.items-body', compact('items', 'trash'))->render();
            $footer = view('partials.items-footer', compact('items', 'trash'))->render();
            $links = $items->appends(request()->except('page'))->links()->toHtml();
            return response()->json(compact('body', 'footer', 'links'));
        }
        return redirect()->back();
    }

    public function create()
    {
        if (url()->previous() != route('Item.create')) {
            session()->put('item_previous_url', url()->previous());
        }
        return view('forms.add-item')
            ->with('previousUrl', session('item_previous_url', route('Item.index')));
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
        $previous_url = session()->get('item_previous_url', route('Item.index'));
        if ($previous_url == route('Item.index') or $previous_url == Str::finish(route('Home'), '/')) {
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
        return redirect(route('Item.index'));
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

        return redirect(route('Item.index'));
    }

    public function records($id)
    {
        $item = Item::findOrFail($id)->name;
        return redirect(route('Entry.index'))->with(compact('item'));
    }

    public function trash()
    {
        $items = Item::onlyTrashed()->paginate(50);
        $trash = true;
        if (request()->ajax()) {
            $body = view('partials.items-body', compact('items', 'trash'))->render();
            $footer = view('partials.items-footer', compact('items', 'trash'))->render();
            $links = $items->appends(request()->except('page'))->links()->toHtml();
            return response()->json(compact('body', 'footer', 'links'));
        } else {
            return view('pages.items', compact('items', 'trash'));
        }
    }

    public function restore()
    {
        $failed = [];
        $restored = 0;

        try {
            if (request('filter') == 'single') {
                $item = Item::onlyTrashed()->findOrFail(request('search'));
                if ($this->canRestore($item)) {
                    $item->restore();
                    $restored++;
                } else {
                    $failed[] = $item->name . ' - Name already exists';
                }
            } else {
                $items = $this->searchFunc(trash: true)->get();
                foreach ($items as $item) {
                    if ($this->canRestore($item)) {
                        $item->restore();
                        $restored++;
                    } else {
                        $failed[] = $item->name . ' - Name already exists';
                    }
                }
            }
        } catch (\Exception $e) {
            if (request('filter') == 'single') {
                $failed[] = 'Unknown item - Error occurred during restore';
            } else {
                $failed[] = 'Multiple items failed to restore due to errors';
            }
        }

        $message = '';
        if ($restored > 0) {
            $message .= $restored . ' item(s) restored successfully.';
        }

        return redirect(route('Item.trash'))
            ->with('success', $message)
            ->with('failed_items', $failed);
    }

    private function canRestore(Item $item)
    {
        // Check if another item with same name exists (not trashed)
        return !Item::where('name', $item->name)
            ->whereNull('deleted_at')
            ->where('id', '!=', $item->id)
            ->exists();
    }

    public function forceDelete()
    {
        if (request('filter') == 'single') {
            Item::onlyTrashed()->findOrFail(request('search'))->forceDelete();
        } else {
            $this->searchFunc(trash: true)->get()->each->forceDelete();
        }
        return redirect(route('Item.trash'));
    }
}
