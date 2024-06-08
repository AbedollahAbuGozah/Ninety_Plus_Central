<?php

namespace App\Traits;

use App\Models\Comment;
use App\Models\Rate;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasRates
{
    public function rates(): MorphMany
    {
        return $this->morphMany(Rate::class, 'ratable');
    }

    public function addRate(array $rateData)
    {
        $rate = new Rate($rateData);
        return $this->rates()->save($rate);
    }

}
