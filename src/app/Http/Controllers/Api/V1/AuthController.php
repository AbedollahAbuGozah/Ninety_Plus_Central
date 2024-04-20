<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetPasswordResetCodeRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\ResetPasswordReqeust;
use App\Http\Requests\VerifyPasswordResetCode;
use App\Http\Resources\UserResource;
use App\Jobs\DeleteExpiredPasswordResetCode;
use App\Models\PasswordResetCode;
use App\Models\User;
use App\Notifications\SendResetPasswordcodeNotification;
use App\Traits\HttpResponse;
use Carbon\Carbon;

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

    public function sendCode(GetPasswordResetCodeRequest $request)
    {
        $email = $request->safe()->all();
        $user = User::where($email)->firstOrFail();
        $passwordReset = PasswordResetCode::create([
            'code' => PasswordResetCode::generateCode(),
            'email' => $email['email']
        ]);
        $user->notify(new SendResetPasswordcodeNotification($passwordReset->code));
        DeleteExpiredPasswordResetCode::dispatch($passwordReset)->delay(Carbon::now()->addMinutes(5));
        return $this->success([], trans('messages.send_code.success'), 200);
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
            'expire_in' => auth()->factory()->getTTL() * 60 * 2,
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
