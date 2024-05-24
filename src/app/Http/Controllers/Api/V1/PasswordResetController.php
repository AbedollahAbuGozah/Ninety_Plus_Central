<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\GetPasswordResetCodeRequest;
use App\Http\Requests\ResetPasswordReqeust;
use App\Http\Requests\VerifyPasswordResetCode;
use App\Jobs\ExpirePasswordResetCodeJob;
use App\Models\PasswordResetCode;
use App\Models\User;
use App\Notifications\SendResetPasswordcodeNotification;
use App\services\UserService;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
class PasswordResetController extends BaseController
{
    public function sendCode(GetPasswordResetCodeRequest $request)
    {
        $email = $request->safe()->all();
        $user = User::where($email)->firstOrFail();

        $passwordReset = PasswordResetCode::create([
            'code' => PasswordResetCode::generateCode(),
            'email' => $email['email']
        ]);

        $user->notify(new SendResetPasswordcodeNotification($passwordReset->code));

        ExpirePasswordResetCodeJob::dispatch($passwordReset)->delay(Carbon::now()->addMinutes(5));
        return $this->success([], trans('messages.send_code.success'), 200);
    }

    public function verifyCode(VerifyPasswordResetCode $request)
    {
        $validatedData = $request->safe()->all();

        if (!PasswordResetCode::where($validatedData)?->delete()) {
            return $this->error(trans('messages.error.code_verification'), 403);
        }
        $user = User::where('email', $validatedData['email'])->firstOrFail();
        logger($user);
        return $this->success([
            'token' => UserService::generatePasswordResetJwtToken($user),
            'expire_in' => 20,
            'email' => $validatedData['email'],
        ], trans('messages.success.code_verification'), 200);

    }

    public function resetPassword(ResetPasswordReqeust $request)
    {
        $validatedData = $request->safe()->all();
        $decoded = JWTAuth::parseToken()->authenticate();

        if ($decoded?->type === 'password_reset' && $user = User::find($decoded['sub'])) {
            (new UserService())->resetPassword($user, $validatedData['password']);
            return $this->success([], trans('messages.success.reset'), 200);
        }

        return $this->error('The Token Is Invalid', 401);
    }

}
