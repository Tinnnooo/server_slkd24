<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function deleteExistFile($user): void
    {
        if ($user->profile_picture) {
            $filePath = $user->profile_picture;

            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }
    }

    public function storeProfile($profile_picture): string
    {
        $imageName = time() . '_' . $profile_picture->getClientOriginalName();
        $profile_picture->storeAs('public/user_profiles', $imageName);

        return 'user_profiles/' . $imageName;
    }
}
