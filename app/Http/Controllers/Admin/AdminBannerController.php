<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminBannerController extends Controller
{
    public function index(): View
    {
        $banners = Banner::orderBy('sort_order')->latest()->paginate(10);

        return view('admin.cms.banners.index', compact('banners'));
    }

    public function create(): View
    {
        return view('admin.cms.banners.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'subtitle' => 'nullable|string|max:255',
            'image_path' => 'required|url|max:255',
            'button_text' => 'nullable|string|max:50',
            'button_url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $banner = Banner::create([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'image_path' => $validated['image_path'],
            'button_text' => $validated['button_text'] ?? null,
            'button_url' => $validated['button_url'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        ActivityLogService::log('banner_created', "Membuat hero banner baru: {$banner->title}");

        return redirect()->route('admin.cms.banners.index')
            ->with('success', "Banner '{$banner->title}' berhasil disimpan.");
    }

    public function edit(Banner $banner): View
    {
        return view('admin.cms.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'subtitle' => 'nullable|string|max:255',
            'image_path' => 'required|url|max:255',
            'button_text' => 'nullable|string|max:50',
            'button_url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $banner->update([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'image_path' => $validated['image_path'],
            'button_text' => $validated['button_text'] ?? null,
            'button_url' => $validated['button_url'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        ActivityLogService::log('banner_updated', "Memperbarui banner: {$banner->title}");

        return redirect()->route('admin.cms.banners.index')
            ->with('success', "Banner '{$banner->title}' berhasil diperbarui.");
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $title = $banner->title;
        $banner->delete();

        ActivityLogService::log('banner_deleted', "Menghapus banner: {$title}");

        return redirect()->route('admin.cms.banners.index')
            ->with('success', "Banner '{$title}' berhasil dihapus.");
    }
}
