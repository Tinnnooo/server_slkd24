<?php

namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    public function render()
    {
        return response()->json([
            'message' => 'Data not found.',
        ], 404);
    }
}
