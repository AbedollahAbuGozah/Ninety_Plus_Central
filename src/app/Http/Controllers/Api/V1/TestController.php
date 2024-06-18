<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     *
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return $request->file('file')->storePu('public/images');
    }
}
