<?php

namespace App;

use Illuminate\Http\JsonResponse;

trait HasResponseHttp
{
    public function success($data, $code = 200): JsonResponse
    {
        return response()->json([
            'message' => $data,
        ], $code);
    }
}
