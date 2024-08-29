<?php

namespace App;

use Illuminate\Http\JsonResponse;

trait HasResponseHttp
{
    public function success(array $data, int $code = 200): JsonResponse
    {
        return response()->json([
            $data,
        ], $code);
    }

    public function failedLogin(): JsonResponse
    {
        return response()->json([
            'message' => 'Wrong username or password',
        ], 401);
    }

    public function notFound(): JsonResponse
    {
        return response()->json([
            'message' => 'Not found.',
        ], 404);
    }

    public function unprocess(string $message): JsonResponse
    {
        return response()->json([
            'message' => $message,
        ], 422);
    }
}
