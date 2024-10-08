<?php

namespace App\services;

use App\Models\Lecture;
use App\Models\Student;
use App\Models\User;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService extends BaseService
{

    protected function preCreateOrUpdate($data, Model $user)
    {
        if (isset($data['password']))
            $data['password'] = bcrypt($data['password']);


        return $data;
    }

    public function postCreateOrUpdate($data, Model $user)
    {
        if (request()->hasFile('profile_image')) {
            $user->resolveUser()->clearMediaCollection(User::PROFILE_IMAGE_MEDIA_COLLECTION);
            $profileImage = $user->addMediaFromRequest('profile_image')
                ->toMediaCollection(User::PROFILE_IMAGE_MEDIA_COLLECTION);
            Storage::disk('s3')->setVisibility($profileImage->getPath(), 'public');
        }
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
        $user = $user ?? (new CurrentUserService())->get();

        $claims = [
            'sub' => $user->id,
            'exp' => Carbon::now()->addMinutes(20)->timestamp,
            'type' => 'password_reset',
        ];

        return JWTAuth::customClaims($claims)->fromUser($user);
    }

    public function resetPassword(User $user, $newPassWord, $currentPassword = null)
    {
        if ($currentPassword && !Hash::check($currentPassword, $user->password)) {
            throw new \Dotenv\Exception\ValidationException('Incorrect old password');
        }
        $user->password = bcrypt($newPassWord);
        $user->save();
    }

    public function generateLectureToken(Lecture $lecture, Student $student)
    {

        $claims = [
            'sub' => $student->id,
            'user_id' => $student->id,
            'exp' => Carbon::now()->addMinutes(60 * 5)->timestamp,
            'type' => 'lecture_joined',
        ];

        return JWTAuth::customClaims($claims)->fromUser($student);
    }

}


