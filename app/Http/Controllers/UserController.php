<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\UserCollection;
use App\Traits\HasResponseHttp;
use App\Traits\HasUploadImage;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use HasResponseHttp, HasUploadImage;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function get()
    {
        return $this->success(['data' => new UserCollection(User::all())]);
    }

    public function create(RegistrationRequest $request)
    {
        $validated = $request->validated();

        $imagePath = $this->saveImage($validated['profile_picture'], 'user_profiles');
        $validated['profile_picture'] = $imagePath;
        $validated['password'] = Hash::make($validated['password']);

        $data = User::create($validated);

        return $this->success(['message' => 'User created successfully', 'data' => new UserResource($data)], 201);
    }

    public function update(Request $request, int $id)
    {
        $user = User::find($id);

        if (!$user) throw new NotFoundException();

        $user->update($request->all());
        $user->save();

        return $this->success(['message' => 'User updated successfully', 'data' => new UserResource($user)]);
    }

    public function delete(int $id)
    {
        $user = User::find($id);

        if (!$user) throw new NotFoundException();

        $user->delete();

        return $this->success(['message' => 'User deleted successfully']);
    }

    public function updateProfile(Request $request)
    {
        if ($request->hasFile('profile_picture')) {
            $user = Auth::user();

            if ($user->profile_picture) {
                $this->deleteImage($user->profile_picture);
            }

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
