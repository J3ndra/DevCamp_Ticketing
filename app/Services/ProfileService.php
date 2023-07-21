<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    public function editProfile($id, $request)
    {
        $user = User::findOrFail($id);
        $inputs = $request->all();

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store avatar
            $avatarPath = $request->file('avatar')->store('avatar', 'public');

            // Add storage path to avatar
            $avatarPath = 'storage/' . $avatarPath;

            $inputs['avatar'] = $avatarPath;
        }

        $user->update($inputs);

        return $user;
    }
}
