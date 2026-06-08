<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Item;

class ProfileController extends Controller
{

    public function show(Request $request)
    {
        $user = $request->user();
        $sellItems = Item::where('user_id', $user->id)->with('item_images')->get();

        $buyItems = Item::whereHas('order_items.order', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('item_images')->get();

        $currentPage = $request->query('page', 'sell');

        return view('mypage.profile', compact('user', 'sellItems', 'buyItems', 'currentPage'));
    }

    public function edit(Request $request)
    {
        $user = $request->user();
        return view('mypage.edit', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        $user = $request->user();

        $data = $request->validated();

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
        }

        $data['is_profile_completed'] = true;

        $user->update($data);

        return redirect('/');
    }
}
