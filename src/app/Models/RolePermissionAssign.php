<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermissionAssign extends Model
{
    use HasFactory;

    protected $table = 'role_permissions_assign';

    protected $guarded = ['id'];

}
