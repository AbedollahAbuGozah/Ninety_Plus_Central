<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\services\UserService;
use App\Traits\HttpResponse;
use Illuminate\Support\Facades\DB;

class RegisterUserController extends Controller
{
    use HttpResponse;

    public function __construct(protected UserService $userService)
    {

    }

    public function __invoke(StoreUserRequest $request)
    {

            DB::beginTransaction();
            $validatedData = $request->safe()->all();
            $user = $this->userService->create($validatedData, new User());
            DB::commit();
            return $this->success(UserResource::make($user), trans('messages.success.register'), 201);
    }
}
