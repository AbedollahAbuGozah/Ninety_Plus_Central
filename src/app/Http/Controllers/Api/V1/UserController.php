<?php

namespace App\Http\Controllers\Api\V1;

use App\constants\RoleOptions;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\User;
use App\services\UserService;
use App\Traits\HttpResponse;
use Illuminate\Support\Facades\DB;

class UserController extends BaseController
{
    public function __construct(protected UserService $userService)
    {

    }

    public function index(UserRequest $request)
    {

        $users = match ($request->query('role')) {
            RoleOptions::STUDENT => Student::query(),
            RoleOptions::INSTRUCTOR => Instructor::query(),
            default => User::query(),
        };

        $users = UserResource::collection($users->paginate($request->get('per_page') ?? 10));
        return $this->success($users, trans('messages.index.success'), 200);
    }

    public function store(UserRequest $request)
    {
        $validatedData = $request->safe()->all();
        DB::beginTransaction();
        $user = $this->userService->create($validatedData, new User());
        DB::commit();
        return $this->success(UserResource::make($user), trans('messages.create.success'), 201);
    }

    public function show(UserRequest $request, User $user)
    {
        return $this->success(UserResource::make($user), trans('messages.show.success'), 200);
    }

    public function update(UserRequest $request, User $user)
    {
        $validatedData = $request->safe()->all();

        DB::beginTransaction();
        $user = $this->userService->update($validatedData, $user);
        $this->userService->resetPassword($user, $validatedData['password'], $validatedData['old_password']);
        DB::commit();

        return $this->success(UserResource::make($user), trans('messages.success.update'), 200);
    }

    public function destroy(UserRequest $request, User $user)
    {
        $this->userService->delete($user);
        return $this->success([], trans('messages.success.delete'), 200);
    }
}
