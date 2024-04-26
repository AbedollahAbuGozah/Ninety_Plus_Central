<?php

namespace App\services;

use App\Models\Role;
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
        $roleId = Role::where([
            'name' => $data['role_name']
        ])->value('id');

        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $roleId,
        ]);

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


