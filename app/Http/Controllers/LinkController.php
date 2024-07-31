<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Services\ShortLinkService;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $links = Link::where('user_id', $user->id);
        if ($request->has('q') && strlen($request->get('q')) > 0) {
            $links = $links->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->get('q') . '%')
                    ->orWhere('destination_url', 'like', '%' . $request->get('q') . '%');
            });
        }
        $links = $links->orderBy('created_at', 'desc')->simplePaginate();
        return view('index', compact('links'));
    }
    public function store(Request $request)
    {
        $input = $request->validate([
            'destination_url' => 'required|url|max:255',
        ]);
        if (!preg_match('/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/', $input['destination_url'])) {
            return back()->withInput()->withErrors(['destination_url' => 'Invalid URL']);
        }
        try {
            $data = file_get_contents($input['destination_url']);
            $title = preg_match('/<title[^>]*>(.*?)<\/title>/ims', $data, $matches) ? $matches[1] : null;
            $input['title'] = $title ?? $input['destination_url'];
        } catch (\Exception $e) {
            $input['title'] = $input['destination_url'];
        }
        Link::create([
            'destination_url' => $input['destination_url'],
            'title' => $input['title'],
            'shortened_url' => ShortLinkService::generate($input['destination_url']),
            'user_id' => auth()->id()
        ]);
        return redirect()->route('index')->with('success', 'Link created successfully');
    }
    public function destroy(Request $request, Link $link)
    {
        $request->user()->can('delete-link', $link);
        $link->delete();
        return redirect()->route('index')->with('success', 'Link deleted successfully');
    }
}
