<?php

namespace App\services;


use App\constants\CourseStatusOptions;
use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class CourseService extends BaseService
{
    public function changeStatus(Course $course, $data)
    {
        $course->update([
            'status' => $data['status'],
        ]);
    }

    public function preUpdate($data, $course)
    {
        CourseChapter::query()->where([
            'course_id' => $course->id,
        ])->delete();
    }

    public function preCreateOrUpdate($data, $course)
    {
        $props = [];
        $keys = ['welcome_message', 'ending_message', 'cover_image', 'weekly_lectures', 'intro_video'];


        foreach ($keys as $key) {
            request()->whenHas($key, function () use (&$props, $key) {
                $props[$key] = request($key);
            });
        }

        $data['properties'] = $props;
        return $data;

    }

    public function postCreateOrUpdate($data, $course)
    {
        $courseProps = $course->properties;

        if (request()->hasFile('cover_image')) {
            $course->clearMediaCollection(Course::COURSE_COVER_IMAGE_MEDIA_COLLECTION);
            $coverImage = $course->addMediaFromRequest('cover_image')
                ->withCustomProperties(['visibility' => 'public'])
                ->toMediaCollection(Course::COURSE_COVER_IMAGE_MEDIA_COLLECTION, 's3');
            Storage::disk('s3')->setVisibility($coverImage->getPath(), 'public');

            $courseProps['cover_image'] = $coverImage->getUrl();;
        }

        if (request()->hasFile('intro_video')) {
            $course->clearMediaCollection(Course::COURSE_INTRO_VIDEO_MEDIA_COLLECTION);
            $introVideo = $course->addMediaFromRequest('intro_video')
                ->toMediaCollection(Course::COURSE_INTRO_VIDEO_MEDIA_COLLECTION);
            Storage::disk('s3')->setVisibility($introVideo->getPath(), 'public');

            $courseProps['intro_video'] = $introVideo->getUrl();
        }

        $course->properties = $courseProps;
        $course->save();

        if (request()->has('chapters')) {
            $course->chapters()->sync($data['chapters']);
        } else {
            $chapters = $course->module->chapters->pluck('id')->toArray();
            $course->chapters()->sync($chapters);
        }

    }

}


