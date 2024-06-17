<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BaseResource extends JsonResource
{

    protected static array $fetchableClasses = [
        EloquentBuilder::class,
        QueryBuilder::class,
        Relation::class,
    ];

    public static function collection($resource, $paginate = false, $page_size = 15)
    {
        if ($paginate) {
            return self::collectionWithSavePagination($resource, $page_size);
        }

        if (self::needsFetching($resource)) {
            $resource = $resource->get();
        }

        return parent::collection($resource);

    }

    private static function collectionWithSavePagination($resource, $page_size)
    {
        logger('needd');
        return $resource->paginate($page_size)
            ->through(function ($item) {
                return self::make($item);
            });
    }

    private static function needsFetching($resource): bool
    {
        foreach (self::$fetchableClasses as $class) {
            if ($resource instanceof $class) {
                return true;
            }
        }
        return false;
    }

}
