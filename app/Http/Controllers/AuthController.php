<?php

namespace App\Http\Controllers;

use App\HasResponseHttp;
use App\Http\Requests\RegistrationRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
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
        }



        return $this->success($user);
    }
}
