<?php

namespace App\services;


use App\constants\CommentableTypeOptions;
use App\constants\RatableTypeOptions;

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


}
