<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\User;
use Illuminate\Http\Request;

class ManageUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $users = User::where('role', 'user')->withCount('links');
        if ($request->has('q') && $request->filled('q')) {
            $users = $users->where(function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->query('q')}%")
                    ->orWhere('email', 'like', "%{$request->query('q')}%")
                    ->orWhere('username', 'like', "%{$request->query('q')}%");
            });
        }
        $users = $users->orderBy('created_at', 'desc')->paginate();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:4',
        ]);
        User::create($input);
        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, User $user)
    {
        $links = Link::where('user_id', $user->id);
        if ($request->has('q') && $request->filled('q')) {
            $links = $links->where(function ($query) use ($request) {
                $query->where('destination_url', 'like', "%{$request->q}%")
                    ->orWhere('title', 'like', "%{$request->q}%");
            });
        }
        $links = $links->orderBy('created_at', 'desc')->paginate();
        return view('admin.users.show', compact('user', 'links'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $input = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);
        if ($request->has('password') && $request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:4',
            ]);
            $input['password'] = $request->password;
        }
        $user->update($input);
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }
}
