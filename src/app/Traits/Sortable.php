<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Sortable
{
    public function scopeSort(Builder $builder)
    {
        if (!isset($this->sortable)) {
            throw new \Exception('sortable array should added as property in the model');
        }
        if (!request()->has('sort')) {
            return $builder;
        }

        $appliedSorts = request('sort');
        $appliableSorts = array_intersect($this->sortable, array_keys($appliedSorts));

        foreach ($appliableSorts as $sort) {
            $value = explode(',', $appliedSorts[$sort]);
            $builder->whereIn($sort, $value);
        }

        return $builder;
    }
}
