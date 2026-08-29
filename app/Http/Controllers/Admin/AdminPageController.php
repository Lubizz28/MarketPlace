<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminPageController extends Controller
{
    public function index(): View
    {
        $pages = Page::latest()->paginate(10);

        return view('admin.cms.pages.index', compact('pages'));
    }

    public function create(): View
    {
        return view('admin.cms.pages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $page = Page::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
            'meta_title' => $validated['meta_title'] ?? $validated['title'],
            'meta_description' => $validated['meta_description'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        ActivityLogService::log('page_created', "Membuat halaman statis baru: {$page->title}");

        return redirect()->route('admin.cms.pages.index')
            ->with('success', "Halaman '{$page->title}' berhasil diterbitkan.");
    }

    public function edit(Page $page): View
    {
        return view('admin.cms.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $page->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'meta_title' => $validated['meta_title'] ?? $validated['title'],
            'meta_description' => $validated['meta_description'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        ActivityLogService::log('page_updated', "Memperbarui halaman statis: {$page->title}");

        return redirect()->route('admin.cms.pages.index')
            ->with('success', "Halaman '{$page->title}' berhasil diperbarui.");
    }

    public function destroy(Page $page): RedirectResponse
    {
        $title = $page->title;
        $page->delete();

        ActivityLogService::log('page_deleted', "Menghapus halaman statis: {$title}");

        return redirect()->route('admin.cms.pages.index')
            ->with('success', "Halaman '{$title}' berhasil dihapus.");
    }
}
