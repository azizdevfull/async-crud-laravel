<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessItem;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);
        $item = new Item();
        $item->name = $request->input('name');
        $item->save();

        ProcessItem::dispatch($item)->onQueue('items');

        return response()->json($item, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);
        $item = Item::findOrFail($id);
        $item->name = $request->input('name');
        $item->save();

        ProcessItem::dispatch($item)->onQueue('items');

        return response()->json($item, 200);
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return response()->json(null, 204);
    }
}
