<?php

namespace App\Rules;

use App\Models\Chapter;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CourseChapterRule implements ValidationRule
{
    public function __construct(protected $courseModuleId)
    {

    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $chapterModuleIds = Chapter::query()
            ->whereIn('id', $value)
            ->select('module_id')
            ->distinct()
            ->get();


        if ($chapterModuleIds->count() > 1 || ($chapterModuleIds->first()->module_id != $this->courseModuleId)) {
            $fail(':attribute should be belong to chapters module');
        }

    }
}
