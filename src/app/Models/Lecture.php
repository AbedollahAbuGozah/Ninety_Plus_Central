<?php

namespace App\Models;

use App\Traits\Filterable;
use App\Traits\HasComments;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Lecture extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Filterable, Sortable, HasComments;

    const LECTURE_RECORD_COLLECTION = 'lecture-record';

    protected $guarded = ['id'];

    protected $filterable = [
        'created_at',
    ];
    protected $sortable = [
        'created_at'
    ];


    public function record()
    {
        return $this->getFirstMediaUrl(self::LECTURE_RECORD_COLLECTION);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function scopeByRole($query, $user = null, $course = null)
    {
        if ($course) {
            return $query->where('course_id', $course->id);
        }

        if ($user->isAdmin()) {
            return $query;
        }

        return match (true) {
            $user->isStudent() => $query->whereHas('course.students', function ($query) use ($user) {
                $query->where('student_id', $user->id);
            }),
            $user->isInstructor() => $query->whereHas('course', function ($query) use ($user) {
                $query->where('instructor_id', $user->id);
            }),
            default => $query
        };

    }
}
