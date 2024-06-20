<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    public function scopeFilter(Builder $builder)
    {
        if (!isset($this->filterable)) {
            throw new \Exception('filterable array should added as property in the model');
        }
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
