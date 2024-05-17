<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\services\UserService;
use Illuminate\Support\Facades\DB;

class RegisterUserController extends BaseController
{

    public function __construct(protected UserService $userService)
    {

    }

    public function __invoke(UserRequest $request)
    {
            DB::beginTransaction();
            $validatedData = $request->safe()->all();
            $user = $this->userService->create($validatedData, new User());
            DB::commit();
            return $this->success(UserResource::make($user), trans('messages.success.register'), 201);
    }
}
