<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function handleRedirect(Request $request)
    {
        $shortenedUrl = $request->route('shortened_url');
        $link = Link::where('shortened_url', $shortenedUrl)->first();
        if (is_null($link)) {
            abort(404, 'Link not found');
        }
        return redirect($link->destination_url);
    }
}
