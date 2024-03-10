<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    //public function courses(){}

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courses()
    {
        /*
        TODO: Implement Courses relation
        */
        return [
            'math',
            'english',
            'arabic',
        ];
    }
}
