<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\ResetPasswordReqeust;
use App\Http\Requests\VerifyPasswordResetCode;
use App\Http\Resources\UserResource;
use App\Jobs\DeleteExpiredPasswordResetCode;
use App\Models\PasswordResetCode;
use App\Models\User;
use App\Notifications\SendResetPasswordcodeNotification;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Mockery\Generator\StringManipulation\Pass\Pass;

class AuthController extends Controller
{
    use HttpResponse;

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

    public function sendCode(Request $request, $email)
    {
        $user = User::where('email', '=', $email)->firstOrFail();
        $passwordReset = PasswordResetCode::create([
            'code' => PasswordResetCode::generateCode(),
            'email' => $email
        ]);
        $user->notify(new SendResetPasswordcodeNotification($passwordReset->code));
        DeleteExpiredPasswordResetCode::dispatch($passwordReset);
    }

    public function verifyCode(VerifyPasswordResetCode $request)
    {
        $validatedData = $request->safe()->all();

        if (!$passwordReset = PasswordResetCode::where($validatedData)->first()) {
            return $this->error(trans('messages.error.code_verification'), 403);
        }
        $passwordReset->delete();
        return $this->success([
            'token' => PasswordResetCode::generateToken($validatedData['email']),
            'email' => $validatedData['email'],
        ], trans('messages.success.code_verification'), 200);

    }

    public function resetPassword(ResetPasswordReqeust $request)
    {
        $validatedData = $request->safe()->all();

        if (!PasswordResetCode::ValidateToken($validatedData['email'], $validatedData['token'])) {
            return $this->error(trans('messages.error.reset_password'), 400);
        }

        $user = User::where('email', '=', $validatedData['email'])->first();
        $user->update([
            'password' => bcrypt($validatedData['password']),
        ]);

        return $this->success(UserResource::make($user), trans('message.success.password_reset'), 200);

    }

    public function logout()
    {
        auth()->logout();
        return $this->success([], trans('messages.success.logout'), 200);
    }
}
