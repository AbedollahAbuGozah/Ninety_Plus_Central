<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }


    public function lectures()
    {
        return $this->hasMany(Lecture::class);
    }

}
