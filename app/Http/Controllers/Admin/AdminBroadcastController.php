<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BroadcastMessage;
use App\Services\Notification\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminBroadcastController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    /**
     * List all broadcast campaigns.
     */
    public function index(): View
    {
        $broadcasts = BroadcastMessage::with('sender')->latest()->paginate(15);

        return view('admin.broadcasts.index', compact('broadcasts'));
    }

    /**
     * Create new broadcast form.
     */
    public function create(): View
    {
        return view('admin.broadcasts.create');
    }

    /**
     * Dispatch broadcast campaign.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'message' => 'required|string|max:1000',
            'target_role' => 'required|string|in:all,member,reseller',
            'channel' => 'required|string|in:email,whatsapp,both',
        ]);

        $broadcast = BroadcastMessage::create([
            'sender_id' => auth()->id(),
            'title' => $validated['title'],
            'message' => $validated['message'],
            'target_role' => $validated['target_role'],
            'channel' => $validated['channel'],
            'status' => 'draft',
        ]);

        $dispatchedCount = $this->notificationService->sendBroadcastMessage($broadcast);

        return redirect()->route('admin.broadcasts.index')
            ->with('success', "Pesan siaran '{$broadcast->title}' berhasil didistribusikan ke {$dispatchedCount} penerima.");
    }
}
