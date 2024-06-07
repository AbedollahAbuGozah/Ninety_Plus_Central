<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'properties' => 'json',
    ];

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
    public function students()
    {
        return $this->belongsToMany(Student::class, 'course_students');
    }

    public function chapters(){
        return $this->belongsToMany(Chapter::class, 'course_chapters');
    }
}
