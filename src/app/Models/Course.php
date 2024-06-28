<?php

namespace App\Models;

use App\Traits\Filterable;
use App\Traits\HasComments;
use App\Traits\HasRates;
use App\Traits\Invoiceable;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maize\Markable\Markable;
use Maize\Markable\Models\Favorite;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Course extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasComments, HasRates, Markable, Filterable, Sortable, Invoiceable;
    use SoftDeletes;


    protected $guarded = ['id'];

    protected array $filterable = [
        'title',
        'instructor_id',
        'module_id',
        'weekly_lectures',
    ];

    protected array $sortable = [
        'created_at',
        'weekly_lectures',
    ];

    protected array $needPrefix = [
        'weekly_lectures' => 'properties->weekly_lectures',
    ];
    protected $casts = [
        'properties' => 'json',
    ];

    protected static $marks = [
        Favorite::class,
    ];

    const COURSE_COVER_IMAGE_MEDIA_COLLECTION = 'course_cover_image';
    const COURSE_INTRO_VIDEO_MEDIA_COLLECTION = 'course_intro_video';

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
        return $this->belongsToMany(Student::class, 'course_students')->withTimestamps();
    }

    public function chapters()
    {
        return $this->belongsToMany(Chapter::class, 'course_chapters');
    }

    public function hasStudent(Student $student = null)
    {

        $student = $student ?? auth()->user()->resolveUser();

        return $this->students()->where('users.id', $student->id)->exists();
    }

}
