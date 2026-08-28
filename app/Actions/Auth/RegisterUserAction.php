<?php

namespace App\Actions\Auth;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction
{
    /**
     * Create a new user with associated profile.
     *
     * @param  array{name: string, email: string, phone: string, password: string, role?: string}  $data
     */
    public function execute(array $data): User
    {
        return DB::transaction(function () use ($data) {
            // Default role is member unless explicitly requested as reseller
            $requestedRole = isset($data['role']) && $data['role'] === UserRole::RESELLER->value
                ? UserRole::RESELLER
                : UserRole::MEMBER;

            // If reseller, status might be pending admin approval, member is active
            $status = $requestedRole === UserRole::RESELLER
                ? UserStatus::PENDING
                : UserStatus::ACTIVE;

            $user = User::create([
                'name' => $data['name'],
                'email' => strtolower($data['email']),
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password']),
                'role' => $requestedRole,
                'status' => $status,
            ]);

            // Create initial profile
            Profile::create([
                'user_id' => $user->id,
            ]);

            return $user;
        });
    }
}
