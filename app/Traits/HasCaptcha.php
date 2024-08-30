<?php

namespace App\Traits;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cache;

trait HasCaptcha
{
    public function verifyCaptcha($request)
    {
        $request->validate([
            'captcha' => 'required|string',
            'token' => 'required|string'
        ]);

        if ($request['captcha'] !==  Cache::get($request['token'])) {
            return false;
        }

        Cache::forget($request['token']);
        return true;
    }
}
