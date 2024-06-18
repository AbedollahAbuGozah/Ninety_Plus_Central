<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    protected $filterable = [];

    public function scopeFilter(Builder $builder)
    {
        if (!request()->has('filter')) {
            return $builder;
        }

        $appliedFilters = request('filter');
        $appliableFitlers = array_intersect($this->filterable, array_keys($appliedFilters));

        foreach ($appliableFitlers as $filter) {
            $value = explode(',', $appliedFilters[$filter]);
            $builder->whereIn($filter, $value);
        }

        return $builder;
    }
}
