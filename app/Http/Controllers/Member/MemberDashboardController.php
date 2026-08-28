<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MemberDashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user()->load(['profile', 'addresses']);

        return view('member.dashboard', compact('user'));
    }

    public function profile(): View
    {
        $user = auth()->user()->load('profile');

        return view('member.profile', compact('user'));
    }

    public function updateProfile(UpdateProfileRequest $request): RedirectResponse
    {
        $user = auth()->user();

        $user->update([
            'name' => $request->validated('name'),
            'phone' => $request->validated('phone'),
        ]);

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'bio' => $request->validated('bio'),
                'gender' => $request->validated('gender'),
                'birthdate' => $request->validated('birthdate'),
            ]
        );

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
