<?php

namespace App\services;


use App\constants\CommentableTypeOptions;

class NinetyPlusCentral
{
    public function getCommentableModel($type)
    {
        $modelConst = strtoupper($type);
        $commentableOptions = CommentableTypeOptions::options();
        return $commentableOptions[$modelConst] ?? false;
    }

}
