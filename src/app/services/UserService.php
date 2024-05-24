<?php

namespace App\services;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService extends BaseService
{

    protected function preCreateOrUpdate($data, Model $user)
    {
        if (isset($data['password']))
            $data['password'] = bcrypt($data['password']);

        return $data;
    }

    protected function postCreate($data, Model $user)
    {
        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $data['role_id'],
        ]);

        return $user;
    }

    public static function generatePasswordResetJwtToken($user = null)
    {
        $user = $user ?? CurrentUserService::get();

        $claims = [
            'sub' => $user->id,
            'exp' => Carbon::now()->addMinutes(20)->timestamp,
            'type' => 'password_reset',
        ];

        return JWTAuth::customClaims($claims)->fromUser($user);
    }

    public function resetPassword(User $user, $newPassWord, $oldPassword = null)
    {
        if ($oldPassword && !Hash::check($oldPassword, $user->password)) {
            throw new \Dotenv\Exception\ValidationException('Incorrect old password');
        }
        $user->password = bcrypt($newPassWord);
        $user->save();
    }
}


