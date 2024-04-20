<?php

namespace App\Http\Controllers\Api\V1;

use App\constants\Roles;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\Student;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    use HttpResponse;

    public function index()
    {
        $students = Role::firstWhere('name', Roles::STUDENT)->users;
        return $this->success(UserResource::collection($students), trans('messages.success.index'), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        //
    }
}
