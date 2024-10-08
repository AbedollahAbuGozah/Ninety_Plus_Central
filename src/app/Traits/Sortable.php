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
        $applicableSorts = array_intersect($this->sortable, array_keys($appliedSorts));
        foreach ($applicableSorts as $sort) {
            $builder->orderBy($sort, $appliedSorts[$sort]);
        }

        return $builder;
    }
}
