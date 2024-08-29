<?php

namespace App;

use App\Models\User;

trait HasToken
{
    public function token(User $user): string
    {
        return $user->createToken('access_token')->plainTextToken;
    }
}
