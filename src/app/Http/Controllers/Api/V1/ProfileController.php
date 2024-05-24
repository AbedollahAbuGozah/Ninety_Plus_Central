<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\User;
use App\services\UserService;
use Illuminate\Http\Request;

class ProfileController extends BaseController
{

    public function __construct(protected UserService $userService)
    {

    }

    public function show(Request $request, User $user)
    {
        $user = Student::find($user->id) ?? Instructor::find($user->id);
        return $this->success(UserResource::make($user->load(['courses.instructor', 'courses.module'])), trans('messages.success.index'), 200);
    }

    public function update(UpdateProfileRequest $request, User $user)
    {
        $validatedData = $request->safe()->all();
        $this->userService->update($validatedData, $user);
        return $this->success(UserResource::make($user), trans('messages.success.index'), 200);
    }

    public function changePassword(ChangePasswordRequest $request, User $user)
    {
        $validatedData = $request->safe()->all();
        $this->userService->resetPassword($user, $validatedData['password'], $validatedData['old_password']);
        return $this->success([], 'messages.success.change_password', 200);
    }
}
