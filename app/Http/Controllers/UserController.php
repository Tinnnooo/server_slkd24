<?php

namespace App\Http\Controllers;

use App\HasResponseHttp;
use App\HasUploadImage;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use HasResponseHttp, HasUploadImage;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function updateProfile(Request $request)
    {
        if ($request->hasFile('profile_picture')) {
            $user = Auth::user();

            $this->deleteImage($user);

            $newProfilePath = $this->saveImage($request->file('profile_picture'), 'user_profiles');

            $user->profile_picture = $newProfilePath;
            $user->save();
        }

        return $this->success(['message' => 'Updated profile successfully.']);
    }

    public function me()
    {

        return $this->success([new UserResource(Auth::user())]);
    }
}
