<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class VerifyEmailController extends BaseController
{
    public function __invoke(Request $request, $userId, $hash)
    {
        $user = User::findOrFail($userId);
        if (!URL::hasValidSignature($request) or !sha1($user->getEmailForVerification()) == $hash) {
            return $this->error('Invalid or expired verification link.', 500);
        }

        $user->markEmailAsVerified();

        $token = auth('api')->login($user);

        return $this->success([
            'user' => UserResource::make($user),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ], trans('messages.success.verification'), 200);
    }
}
