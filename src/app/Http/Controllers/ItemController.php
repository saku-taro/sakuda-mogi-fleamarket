<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ExhibitionRequest;

use App\Models\Item;
use App\Models\Category;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $currentTab = $request->query('tab', 'all');
        $keyword = $request->query('keyword');
        $allQuery = Item::where('user_id', '!=', $user?->id)->with('item_images');
        $mylistQuery = $user ? $user->favorites()->with('item_images') : null;
        if (!empty($keyword)) {
            $allQuery->where('name', 'LIKE', "%{$keyword}%");
            if ($mylistQuery) {
                $mylistQuery->where('name', 'LIKE', "%{$keyword}%");
            }
        }

        $allItems = $allQuery->get();
        $mylistItems = $mylistQuery ? $mylistQuery->get() : collect();

        return view('index', compact('allItems', 'mylistItems', 'currentTab'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('item.sell', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        $user = $request->user();
        $imagePath = null;
        if ($request->hasFile('item_image')) {
            $imagePath = $request->file('item_image')->store('item_images', 'public');
        }

        $item = Item::create([
            'user_id'        => $user->id,
            'name'           => $request->name,
            'brand_name'     => $request->brand_name,
            'description'    => $request->description,
            'price'          => $request->price,
            'status'         => $request->status,
        ]);

        $item->item_images()->create(['image_path' => $imagePath]);

        $item->categories()->attach($request->category_ids);

        return redirect()->route('item.index');
    }

    public function show($item_id)
    {
        $item = Item::with([
            'item_images',
            'categories',
            'comments.user',
            'favoritedBy'
        ])->findOrFail($item_id);

        return view('item.show', compact('item'));
    }
}
