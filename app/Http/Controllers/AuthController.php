<?php

namespace App\Http\Controllers;

use App\Exceptions\ServerBusyException;
use App\HasResponseHttp;
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
    use HasResponseHttp;

    public function register(RegistrationRequest $request)
    {
        $validated = $request->validated();

        $profile_picture = $validated['profile_picture'];

        $imageName = time() . '_' . $profile_picture->getClientOriginalName();
        $profile_picture->storeAs('public/user_profiles', $imageName);

        $imagePath = 'storage/user_profiles' . $imageName;

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'date_of_birth' => $validated['date_of_birth'],
                'phone_number' => $validated['phone_number'],
                'profile_picture' => $imagePath,
            ]);

            $role = Role::find(1);
            $user->roles()->attach($role);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return new ServerBusyException();
        }

        $user['token'] = $user->createToken('access_token')->plainTextToken;
        Auth::login($user);

        return $this->success(['message' => 'User create success.', 'data' => new UserResource($user)]);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        if (!Auth::once($validated)) {
            return $this->failedLogin();
        }

        return $this->success(['message' => 'Login success.', 'token' => Auth::user()->createToken('access_token')->plainTextToken]);
    }
}
