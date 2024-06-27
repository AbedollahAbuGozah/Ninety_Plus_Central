<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Sortable
{
    protected $prefixProps = 'properties->';

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
            $builder->orderBy($this->needPrefix['weekly_lectures'] ?? $sort, $appliedSorts[$sort]);
        }

        return $builder;
    }
}
