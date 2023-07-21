<?php

namespace App\Services;

use App\Models\User;

class ProfileService
{
    public function editProfile($request)
    {
        $user = User::find($request->user()->id);

        $oldAvatar = $user->avatar;
        if ($oldAvatar) {
            Storage::disk('public')->delete($oldAvatar);
        }
        $avatarPath = $request->file('avatar')->store('avatar', 'public');

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->avatar = $avatarPath;
        $user->gender = $request['gender'];
        $user->date_of_birth = $request['date_of_birth'];
        $user->phone_number = $request['phone_number'];

        $user->save();

        return $user;
    }
}
