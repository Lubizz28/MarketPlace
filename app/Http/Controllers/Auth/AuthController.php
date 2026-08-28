<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\AuthenticateUserAction;
use App\Actions\Auth\RegisterUserAction;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (auth()->check()) {
            return $this->redirectBasedOnRole(auth()->user());
        }

        return view('auth.login');
    }

    public function login(LoginRequest $request, AuthenticateUserAction $action): RedirectResponse
    {
        $user = $action->execute($request->validated());

        return $this->redirectBasedOnRole($user)
            ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
    }

    public function showRegister(Request $request): View|RedirectResponse
    {
        if (auth()->check()) {
            return $this->redirectBasedOnRole(auth()->user());
        }

        $isReseller = $request->query('type') === 'reseller';

        return view('auth.register', compact('isReseller'));
    }

    public function register(RegisterRequest $request, RegisterUserAction $action): RedirectResponse
    {
        $user = $action->execute($request->validated());

        auth()->login($user);

        return $this->redirectBasedOnRole($user)
            ->with('success', 'Pendaftaran berhasil! Selamat datang di platform kami.');
    }

    public function logout(Request $request): RedirectResponse
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar.');
    }

    private function redirectBasedOnRole($user): RedirectResponse
    {
        return match ($user->role) {
            UserRole::ADMIN => redirect()->intended(route('admin.dashboard')),
            UserRole::RESELLER => redirect()->intended(route('reseller.dashboard')),
            default => redirect()->intended(route('member.dashboard')),
        };
    }
}
