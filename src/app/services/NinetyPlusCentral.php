<?php

namespace App\services;


use App\constants\CommentableTypeOptions;
use App\constants\FavorableTypeOptions;
use App\constants\PurchasableTypeOptions;
use App\constants\RatableTypeOptions;
use App\Models\Course;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;

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

    public function getPurchasableModel($type)
    {
        $modelConst = strtoupper($type);
        $ratableOptions = PurchasableTypeOptions::options();
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

    public function generateRelationName($model, $plural = 0)
    {
        $relation = Str::lower(class_basename($model));

        return !$plural ? $relation : Str::plural($relation);
    }

    public function postPurchaseCourse(Course $course)
    {
        $instructor = $course->instructor;
        $instructorProps = $instructor->properties ?? [];
        $instructorProps['balance_info'] = array_merge($instructorProps['balance_info'] ?? [], [
            'balance' => ($instructorProps['balance_info']['balance'] ?? 0) + $course->price,
        ]);

        $instructor->properties = $instructorProps;
        $instructor->save();
    }

}
