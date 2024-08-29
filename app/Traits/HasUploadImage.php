<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
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

    public function deleteImage(string $path): void
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
