<?php

namespace App\Models;

use App\Traits\HasComments;
use App\Traits\HasRates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Maize\Markable\Markable;
use Maize\Markable\Models\Favorite;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Course extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasComments, HasRates, Markable;

    protected $guarded = ['id'];

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

}
