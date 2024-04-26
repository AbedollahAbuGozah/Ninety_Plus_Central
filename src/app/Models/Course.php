<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
