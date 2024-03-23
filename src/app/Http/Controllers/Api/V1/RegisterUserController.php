<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Jobs\SendEmailVerificationJob;
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
        try {
            $validatedData = $request->safe()->all();
            $validatedData['password'] = bcrypt($validatedData['password']);
            $user = UserService::create($validatedData);
            SendEmailVerificationJob::dispatch($user);
            return $this->success([], trans('messages.success.register'), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), 404);
        }
    }
}
