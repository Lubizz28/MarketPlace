<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminPostController extends Controller
{
    public function index(): View
    {
        $posts = Post::with('author')->latest()->paginate(10);

        return view('admin.cms.posts.index', compact('posts'));
    }

    public function create(): View
    {
        return view('admin.cms.posts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'thumbnail_path' => 'nullable|url|max:255',
            'excerpt' => 'nullable|string|max:300',
            'body' => 'required|string',
            'is_published' => 'boolean',
        ]);

        $post = Post::create([
            'author_id' => auth()->id(),
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . Str::random(5),
            'thumbnail_path' => $validated['thumbnail_path'] ?? 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?q=80&w=800&auto=format&fit=crop',
            'excerpt' => $validated['excerpt'] ?? Str::limit(strip_tags($validated['body']), 150),
            'body' => $validated['body'],
            'is_published' => $request->has('is_published'),
            'published_at' => $request->has('is_published') ? now() : null,
        ]);

        ActivityLogService::log('post_created', "Menerbitkan artikel blog: {$post->title}");

        return redirect()->route('admin.cms.posts.index')
            ->with('success', "Artikel '{$post->title}' berhasil disimpan.");
    }

    public function edit(Post $post): View
    {
        return view('admin.cms.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'thumbnail_path' => 'nullable|url|max:255',
            'excerpt' => 'nullable|string|max:300',
            'body' => 'required|string',
            'is_published' => 'boolean',
        ]);

        $wasPublished = $post->is_published;
        $nowPublished = $request->has('is_published');

        $post->update([
            'title' => $validated['title'],
            'thumbnail_path' => $validated['thumbnail_path'] ?? $post->thumbnail_path,
            'excerpt' => $validated['excerpt'] ?? Str::limit(strip_tags($validated['body']), 150),
            'body' => $validated['body'],
            'is_published' => $nowPublished,
            'published_at' => (!$wasPublished && $nowPublished) ? now() : $post->published_at,
        ]);

        ActivityLogService::log('post_updated', "Memperbarui artikel blog: {$post->title}");

        return redirect()->route('admin.cms.posts.index')
            ->with('success', "Artikel '{$post->title}' berhasil diperbarui.");
    }

    public function destroy(Post $post): RedirectResponse
    {
        $title = $post->title;
        $post->delete();

        ActivityLogService::log('post_deleted', "Menghapus artikel blog: {$title}");

        return redirect()->route('admin.cms.posts.index')
            ->with('success', "Artikel '{$title}' berhasil dihapus.");
    }
}
