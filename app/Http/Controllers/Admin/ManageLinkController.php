<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Http\Request;

class ManageLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $links = Link::with('user:id,name');
        if ($request->has('q') && $request->filled('q')) {
            $links = $links->where(function ($query) use ($request) {
                $query->where('destination_url', 'like', "%{$request->q}%")
                    ->orWhere('title', 'like', "%{$request->q}%");
            });
        }
        $links = $links->orderBy('created_at', 'desc')->paginate();
        return view('admin.links.index', compact('links'));
    }
    public function edit(Link $link)
    {
        return view('admin.links.edit', compact('link'));
    }

    public function update(Request $request, Link $link)
    {
        $link->update($request->validate([
            'shortened_url' => 'required|max:255|unique:links,shortened_url,' . $link->id,
            'destination_url' => 'required|url|max:255',
        ]));
        return redirect()->route('admin.links.index')->with('success', 'Link updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Link $link)
    {
        $link->delete();
        return redirect()->route('admin.links.index')->with('success', 'Link deleted successfully');
    }
}
