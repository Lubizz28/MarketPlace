<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminActivityLogController extends Controller
{
    /**
     * Audit Trail of Platform Activity Logs.
     */
    public function index(Request $request): View
    {
        $query = ActivityLog::with('user')->latest();

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('description', 'like', "%{$q}%")
                    ->orWhere('ip_address', 'like', "%{$q}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$q}%"));
            });
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('admin.activity_logs.index', compact('logs'));
    }
}
