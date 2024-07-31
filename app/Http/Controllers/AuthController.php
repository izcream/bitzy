<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $input = $request->validate([
            'username' => 'required|min:4|max:255',
            'password' => 'required|min:4',
        ]);
        $user = User::select('id', 'username', 'email', 'password', 'role')
            ->where('username', $input['username'])
            ->orWhere('email', $input['username'])
            ->first();
        if (is_null($user)) {
            return back()->withErrors([
                'username' => 'We cannot find an account with that username/email',
            ])->onlyInput('username');
        }
        if (!password_verify($input['password'], $user->password)) {
            return back()->withErrors([
                'password' => 'Looks like you entered the wrong password',
            ])->onlyInput('username');
        }
        Auth::login($user);
        return redirect()->intended('/');
    }
    public function showRegister()
    {
        return view('auth.register');
    }
    public function register(Request $request)
    {
        $input = $request->validate([
            'email' => 'required|email|unique:users,email',
            'username' => 'required|min:4|max:255|unique:users,username',
            'password' => 'required|min:4',
            'name' => 'required|min:2|max:255',
        ]);
        $user = User::create($input);
        Auth::login($user);
        return redirect()->route('index')->with('success', 'Welcome ' . $input['name']);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth.login');
    }
}
