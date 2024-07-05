<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\services\UserService;

class RegisterUserController extends BaseController
{

    public function __construct(protected UserService $userService)
    {

    }

    public function __invoke(UserRequest $request)
    {
            $validatedData = $request->safe()->all();
            $user = $this->userService->create($validatedData, new User());
            return $this->success(UserResource::make($user), trans('messages.success.register'), 201);
    }
}
