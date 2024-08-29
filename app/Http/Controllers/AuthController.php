<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegistrationRequest $request)
    {
        $validated = $request->validated();

        $profile_picture = $validated->profile_picture;

        if ($profile_picture) {
            $imageName = time() . $profile_picture->getClientOriginalName() . '.' . $profile_picture->getClientOriginalExtension();
            $profile_picture->storeAs('images', $imageName);
        }
        return;
    }
}
