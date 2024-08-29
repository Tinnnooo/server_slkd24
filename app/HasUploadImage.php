<?php

namespace App;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasUploadImage
{
    public function saveImage(UploadedFile|null $image, string $folderName)
    {
        $imageName = $image->hashName();
        $image->storeAs('public/' . $folderName, $imageName);

        return $folderName . '/' . $imageName;
    }

    public function deleteImage($user): void
    {
        if ($user->profile_picture) {
            $filePath = $user->profile_picture;

            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }
    }
}
