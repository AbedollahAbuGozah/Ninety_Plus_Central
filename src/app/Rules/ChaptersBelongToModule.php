<?php

namespace App\Rules;

use App\Models\Chapter;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ChaptersBelongToModule implements ValidationRule
{
    public function __construct(protected $relatedModuleId)
    {

    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $chapterModuleIds = Chapter::query()
            ->whereIn('id', $value)
            ->select('module_id')
            ->distinct()
            ->get();


        if ($chapterModuleIds->count() > 1 || ($chapterModuleIds->first()->module_id != $this->relatedModuleId)) {
            $fail(':attribute should be belong to chapters module');
        }

    }

    private function extractChapterIds($value): array
    {
        if (!is_array($value[0])) {
            return $value;
        }

        return array_column($value, 'id');
    }


}
