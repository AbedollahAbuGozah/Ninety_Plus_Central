<?php

namespace App\Http\Controllers\Api\V1;

use App\constants\CourseStatusOptions;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return CourseStatusOptions::options();
    }
}
