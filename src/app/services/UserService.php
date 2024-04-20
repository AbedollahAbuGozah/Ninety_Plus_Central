<?php

namespace App\services;

use App\Jobs\SendEmailVerificationJob;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\UserRole;
use Illuminate\Database\Eloquent\Model;

class UserService extends BaseService
{

    protected function preCreate($data, Model $user)
    {
        $data['password'] = bcrypt($data['password']);

        return $data;
    }

    protected function postCreate($data, Model $user)
    {
        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $data['role_id'],
        ]);
        if ($user->isStudent()) {
            Student::create([
                'user_id' => $user->id,
                'branch_id' => $data['branch_id'],
            ]);
        } elseif ($user->isInstructor()) {
            Instructor::create([
                'user_id' => $user->id,
            ]);
            //TODO:insert instructor cv
        }
        return $user;
    }

    public function preUpdate($data, Model $user)
    {
        if (isset($data['password']))
            $data['password'] = bcrypt($data['password']);
        return $data;
    }

//    protected function postUpdate($data, Model $user)
//    {
//
//        if ($data['branch_id'] && $user->isStudent()) {
//            $user->student->update([
//                'branch_id' => $data['branch_id'],
//            ]);
//        }
//        SendEmailVerificationJob::dispatch($user)->afterCommit();
//        return $user;
//    }


}


