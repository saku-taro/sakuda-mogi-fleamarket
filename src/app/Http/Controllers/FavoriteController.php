<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\Item;

class FavoriteController extends Controller
{
    public function toggle($item_id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $item = Item::findOrFail($item_id);

        if ($user->favorites()->where('item_id', $item->id)->exists()) {
            $user->favorites()->detach($item->id);
        } else {
            $user->favorites()->attach($item->id);
        }

        return back();
    }
}
