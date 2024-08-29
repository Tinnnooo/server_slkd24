<?php

namespace App\Exceptions;

use Exception;

class ServerBusyException extends Exception
{
    public function render($request)
    {
        return response()->json(['message' => 'Server is wrong. Contact owner to be fixed.']);
    }
}
