<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();
        return view('profile', compact('user'));
    }
    public function update(Request $request)
    {
        $input = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->user()->id,
            'username' => 'required|string|max:255|unique:users,username,' . $request->user()->id,
        ]);
        if ($request->has('password') && $request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:4',
            ]);
            $input['password'] = $request->password;
        }
        $request->user()->update($input);
        return redirect()->route('profile')->with('success', 'Profile updated successfully');
    }
}
