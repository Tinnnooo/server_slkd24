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
}
