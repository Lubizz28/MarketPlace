<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    /**
     * Record an audit activity log entry.
     */
    public static function log(string $action, string $description, ?User $user = null): ActivityLog
    {
        $userId = $user ? $user->id : auth()->id();

        return ActivityLog::create([
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'ip_address' => Request::ip() ?? '127.0.0.1',
            'user_agent' => Request::userAgent() ?? 'CLI/System',
        ]);
    }
}
