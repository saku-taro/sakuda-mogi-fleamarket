<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function store($item_id)
    {
        request()->user()->favorites()->attach($item_id);
        return back();
    }

    public function destroy($item_id)
    {
        request()->user()->favorites()->detach($item_id);
        return back();
    }
}
