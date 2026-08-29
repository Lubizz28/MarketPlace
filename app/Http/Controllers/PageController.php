<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Display a public static page by slug.
     */
    public function show(string $slug): View
    {
        $page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();

        return view('pages.show', compact('page'));
    }
}
