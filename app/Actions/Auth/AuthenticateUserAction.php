<?php

namespace App\Actions\Auth;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticateUserAction
{
    /**
     * Authenticate user with credentials and enforce security checks.
     *
     * @param  array{email: string, password: string, remember?: bool}  $credentials
     */
    public function execute(array $credentials): User
    {
        $user = User::where('email', strtolower($credentials['email']))
            ->orWhere('phone', $credentials['email'])
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Kredensial yang diberikan tidak cocok dengan data kami.'],
            ]);
        }

        if ($user->status === UserStatus::BANNED) {
            throw ValidationException::withMessages([
                'email' => ['Akun Anda telah diblokir. Silakan hubungi dukungan pelanggan.'],
            ]);
        }

        Auth::login($user, (bool) ($credentials['remember'] ?? false));

        if ($sessionId = session()->get('cart_session_id')) {
            app(\App\Services\CartService::class)->migrateGuestCartToUser($sessionId, $user);
        }

        session()->regenerate();

        return $user;
    }
}
