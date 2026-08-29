<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSettingController extends Controller
{
    /**
     * Display settings form grouped by category.
     */
    public function index(): View
    {
        $settings = Setting::all()->groupBy('group');

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Bulk update platform settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $inputs = $request->except(['_token', '_method']);

        foreach ($inputs as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        ActivityLogService::log('settings_updated', 'Memperbarui konfigurasi sistem & toko');

        return redirect()->route('admin.settings.index')
            ->with('success', 'Konfigurasi platform berhasil diperbarui.');
    }
}
