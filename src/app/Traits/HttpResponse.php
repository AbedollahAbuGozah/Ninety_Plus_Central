<?php

namespace App\Traits;

trait HttpResponse
{
    public function success($data, $message, $code, $key = 'data')
    {
        return response()->json([
            'status' => 'requests was success',
            $key => $data,
            'message' => $message,
        ], $code);
    }
    public function error($message, $code)
    {
        return response()->json([
            'status' => 'error has occurred',
            'message' => $message
        ], $code);
    }
}
