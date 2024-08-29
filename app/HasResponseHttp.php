<?php

namespace App;

use Illuminate\Http\JsonResponse;

trait HasResponseHttp
{
    public function success(array $data,  $code = 200): JsonResponse
    {
        return response()->json([
            $data,
        ], $code);
    }
}
