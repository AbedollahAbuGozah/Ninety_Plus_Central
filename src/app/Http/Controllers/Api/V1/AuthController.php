<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\LoginUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\services\CurrentUserService;
use App\Traits\HttpResponse;

class AuthController extends BaseController
{
    use HttpResponse;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(LoginUserRequest $request)
    {
        $credentials = $request->safe()->all();

        if (!$token = auth()->attempt($credentials)) {
            return $this->error(trans('messages.error.login'), 404);
        }

        return $this->success([
            'user' => UserResource::make(User::where('email', '=', $credentials['email'])->first()),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ], trans('messages.success.login'), 200);
    }

    public function me()
    {
        $user = (new CurrentUserService())::get();
        $user->permission = [
            'user' => [
                'can_update' => rand(1, 0),
                'can_delete' => rand(1, 0),
                'can_modify' => rand(1, 0),
                'can_view' => rand(1, 0),
            ],
            'course' => [
                'can_update' => rand(1, 0),
                'can_delete' => rand(1, 0),
                'can_modify' => rand(1, 0),
                'can_view' => rand(1, 0),
            ],
            'module' => [
                'can_update' => rand(1, 0),
                'can_delete' => rand(1, 0),
                'can_modify' => rand(1, 0),
                'can_view' => rand(1, 0),
            ]
        ];
        return $user;
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function logout()
    {
        (new CurrentUserService())::logout();
        return $this->success([], trans('messages.success.logout'), 200);
    }

}
