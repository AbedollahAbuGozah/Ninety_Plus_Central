<?php

namespace App\Traits;

trait HttpResponse
{
    public function success($data, $message, $code)
    {
        return response()->json([
            'status' => 'requests was success',
            'data' => $data,
            'message' => $message,
        ], $code);
    }
    public function error($message, $code)
    {
        return response()->json([
            'status' => 'error has occurred',
            'message' => $message
        ]);
    }
}
