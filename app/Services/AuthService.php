<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthService
{
    public function register($request)
    {
        // Store avatar
        $avatarPath = $request->file('avatar')->store('avatar', 'public');

        // Store user
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'avatar' => $avatarPath,
            'role_id' => $request['roleId'],
            'gender' => $request['gender'],
            'date_of_birth' => $request['dateOfBirth'],
            'phone_number' => $request['phone'],
        ]);

        // Login the user
        Auth::login($user);

        return $user;
    }
}
