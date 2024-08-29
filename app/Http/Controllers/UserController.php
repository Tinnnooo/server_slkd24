<?php

namespace App\Http\Controllers;

use App\HasResponseHttp;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use HasResponseHttp;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function updateProfile(Request $request)
    {
        if ($request->hasFile('profile_picture')) {
            $user = Auth::user();

            $this->userService->deleteExistFile($user);

            $newProfilePath = $this->userService->storeProfile($request->file('profile_picture'));

            $user->profile_picture = $newProfilePath;
            $user->save();
        }

        return $this->success(['message' => 'Updated profile successfully.']);
    }

    public function getProfile()
    {
        $user = Auth::user();
        $profilePicturePath = $user->profile_picture;

        if (!Storage::disk('public')->exists($profilePicturePath)) {
            return $this->notFound();
        }

        return response()->file(Storage::disk('public')->path($profilePicturePath));
    }
}
