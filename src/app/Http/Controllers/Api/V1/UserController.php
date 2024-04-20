<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\services\UserService;
use App\Traits\HttpResponse;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use HttpResponse;

    public function __construct(protected UserService $userService)
    {

    }

    public function index()
    {
        $users = UserResource::collection(User::all());
        return $this->success($users, trans('messages.index.success'), 200);
    }

    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->safe()->all();

        return $validatedData;

        DB::beginTransaction();
        $user = $this->userService->create($validatedData, new User());
        DB::commit();
        return $this->success(UserResource::make($user), trans('messages.create.success'), 201);
    }


    public function show(User $user)
    {
        return $this->success(UserResource::make($user), trans('messages.show.success'), 200);

    }


    public function update(UpdateUserRequest $request, User $user)
    {
        $validatedData = $request->safe()->all();
        DB::beginTransaction();
        $user = $this->userService->update($validatedData, $user);
        DB::commit();
        return $this->success(UserResource::make($user), trans('messages.success.update'), 200);

    }


    public function destroy(User $user)
    {
        $this->userService->delete($user);
        return $this->success([], trans('messages.success.delete'), 200);
    }
}
