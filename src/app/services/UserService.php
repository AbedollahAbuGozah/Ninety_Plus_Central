<?php

namespace App\services;

use App\Models\Instructor;
use App\Models\Student;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\DB;

class UserService
{
    public static function create($validatedData)
    {
        DB::beginTransaction();
        $user = User::create($validatedData);
        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $validatedData['role_id'],
        ]);
        if ($user->isStudent()) {
            Student::create([
                'user_id' => $user->id,
                'branch_id' => $validatedData['branch_id'],
            ]);
        } elseif ($user->isInstructor()) {
            Instructor::create([
                'user_id' => $user->id,
            ]);
            //TODO:insert instructor cv
        }
        DB::commit();
        return $user;
    }



}


