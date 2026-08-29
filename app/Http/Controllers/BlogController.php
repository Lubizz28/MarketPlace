<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * Display public blog articles index.
     */
    public function index(): View
    {
        $posts = Post::with('author')
            ->where('is_published', true)
            ->latest('published_at')
            ->paginate(9);

        return view('blog.index', compact('posts'));
    }

    /**
     * Display a single blog article and increment view counter.
     */
    public function show(string $slug): View
    {
        $post = Post::with('author')
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $post->increment('view_count');

        $relatedPosts = Post::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }
}
