<?php

namespace App\services;


use App\Models\Course;
use App\Models\CourseChapter;
use Illuminate\Support\Facades\Storage;

class CourseService extends BaseService
{
    public function changeStatus(Course $course, $data)
    {
        $course->update([
            'status' => $data['status'],
        ]);
    }

    public function preUpdate($data, $model)
    {
        CourseChapter::query()->where([
            'course_id' => $model->id,
        ])->delete();
    }

    public function preCreateOrUpdate($data, $model)
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

    public function postCreateOrUpdate($data, $model)
    {
        $courseProps = $model->properties;

        if (request()->hasFile('cover_image')) {
            $coverImage = $model->addMediaFromRequest('cover_image')
                ->withCustomProperties(['visibility' => 'public'])
                ->toMediaCollection(Course::COURSE_COVER_IMAGE_MEDIA_COLLECTION, 's3');
            Storage::disk('s3')->setVisibility($coverImage->getPath(), 'public');

            $courseProps['cover_image'] = $coverImage->getUrl();;
        }

        if (request()->hasFile('intro_video')) {
            $introVideo = $model->addMediaFromRequest('intro_video')
                ->toMediaCollection(Course::COURSE_INTRO_VIDEO_MEDIA_COLLECTION);
            Storage::disk('s3')->setVisibility($introVideo->getPath(), 'public');

            $courseProps['intro_video'] = $introVideo->getUrl();
        }

        $model->properties = $courseProps;
        $model->save();

        if (isset($data['chapters'])) {
            $chapterIds = $data['chapters'];

            $insertData = array_map(function ($chapterId) use ($model) {
                return [
                    'chapter_id' => $chapterId,
                    'course_id' => $model->id,
                ];
            }, $chapterIds);

            CourseChapter::insert($insertData);
        }

    }

}


