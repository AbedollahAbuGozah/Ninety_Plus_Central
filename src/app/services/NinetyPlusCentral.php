<?php

namespace App\services;


use App\constants\CommentableTypeOptions;
use App\constants\FavorableTypeOptions;
use App\constants\RatableTypeOptions;
use Illuminate\Database\Schema\Blueprint;

class NinetyPlusCentral
{

    public function getCommentableModel($type)
    {
        $modelConst = strtoupper($type);
        $commentableOptions = CommentableTypeOptions::options();
        return $commentableOptions[$modelConst] ?? false;
    }

    public function getRatableModel($type)
    {
        $modelConst = strtoupper($type);
        $ratableOptions = RatableTypeOptions::options();
        return $ratableOptions[$modelConst] ?? false;
    }

    public function getFavoriteModel($type)
    {
        $modelConst = strtoupper($type);
        $ratableOptions = FavorableTypeOptions::options();
        return $ratableOptions[$modelConst] ?? false;
    }


    public function calcRatableRate($ratable)
    {
        $ratesAvg = $ratable->rates()->avg('rate_value');
        $this->roundRate($ratesAvg);
        return $ratesAvg;
    }

    public function roundRate(&$rate)
    {
        $whole = floor($rate);
        $fraction = $rate - $whole;

        if ($fraction > 0 && $fraction <= 0.5) {
            $rate = $whole + 0.5;
        } elseif ($fraction > 0.5) {
            $rate = $whole + 1;
        } else {
            $rate = $whole;
        }
    }

    public function addPropsColumn(Blueprint $table): void
    {
        $table->json('properties')->nullable()->default(null);
    }

    public function getModelResource($modelClass)
    {
        $nameSpace = 'App\Http\Resources\\';
        return $nameSpace . class_basename($modelClass) . 'Resource';
    }

    private static function getBaseModelClassName($model)
    {
    }

}
