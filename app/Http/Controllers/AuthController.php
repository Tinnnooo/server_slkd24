<?php

namespace App\Http\Controllers;

use App\Exceptions\ServerBusyException;
use App\Traits\HasResponseHttp;
use App\Traits\HasToken;
use App\Traits\HasUploadImage;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HasResponseHttp, HasUploadImage, HasToken;

    public function register(RegistrationRequest $request)
    {
        $validated = $request->validated();

        $profile_picture = $validated['profile_picture'];

        $imagePath = $this->saveImage($profile_picture, 'user_profiles');

        $imagePath = $this->saveImage($validated['profile_picture'], 'user_profiles');
        $validated['profile_picture'] = $imagePath;
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        $role = Role::find(99);
        $user->roles()->attach($role);

        Auth::login($user);

        return $this->success(['message' => 'User create success.', 'data' => new UserResource($user), 'token' => $this->token($user)], 201);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        if (!Auth::once($validated)) {
            return $this->failedLogin();
        }

        return $this->success(['message' => 'Login success.', 'token' => $this->token(Auth::user())]);
    }
}
