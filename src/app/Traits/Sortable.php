<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Sortable
{
    /**
     * @throws \Exception
     */
    public function scopeSort(Builder $builder)
    {
        if (!isset($this->sortable)) {
            throw new \Exception('sortable array should added as property in the model');
        }
        if (!request()->has('sort')) {
            return $builder;
        }

        $appliedSorts = request('sort');
        $applicableSorts = array_intersect($this->sortable, array_keys(array_merge($appliedSorts, ['properties->weekly_lectures'])));

        foreach ($applicableSorts as $sort) {
            $value = explode(',', $appliedSorts[$sort]);
            $builder->orderBy($sort, $value);
        }

        return $builder;
    }
}
