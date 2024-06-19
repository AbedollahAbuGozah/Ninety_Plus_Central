<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'role_permissions_assign';

    public function resource(){
        return $this->belongsTo(Resource::class);
    }
}
