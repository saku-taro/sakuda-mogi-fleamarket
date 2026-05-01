<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProfileEditController extends Controller
{
    public function index()
    {
        return view('index');
    }
    public function edit()
    {
        $user = Auth::user();
        return view('mypage.edit', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();

        $imagePath = $user->profile_image;
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $imagePath = $request->file('profile_image')->store('profiles', 'public');
        }

        $user->update([
            'name' => $request->name,
            'profile_image' => $imagePath,
            'postcode' => $request->postcode,
            'address' => $request->address,
            'building' => $request->building,
            'is_profile_completed' => true,
        ]);

        return redirect('/');
    }
}
