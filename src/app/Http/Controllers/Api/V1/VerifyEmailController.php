<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class VerifyEmailController extends Controller
{
    use HttpResponse;

    public function __invoke(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);
        if (!URL::hasValidSignature($request) or !sha1($user->getEmailForVerification()) == $hash) {
            return $this->error('Invalid or expired verification link.', 500);
        }
        $user->markEmailAsVerified();
        $token = auth('api')->login($user);

        return $this->success([
            'data' => $user,
            'token' => $token
        ], 'Email verified successfully', 200);
    }
}
