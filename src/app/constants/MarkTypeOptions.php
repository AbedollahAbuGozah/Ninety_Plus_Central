<?php

namespace App\constants;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Lecture;
use Maize\Markable\Models\Bookmark;
use Maize\Markable\Models\Favorite;
use Maize\Markable\Models\Like;
use Maize\Markable\Models\Reaction;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use function Laravel\Prompts\select;

class MarkTypeOptions
{
    const BOOKMARK = 'bookmark';
    const REACTION = 'reaction';
    const FAVORITE = 'favorite';
    const LIKE = 'like';

    private static $markClasses = [
        self::BOOKMARK => Bookmark::class,
        self::REACTION => Reaction::class,
        self::FAVORITE => Favorite::class,
        self::LIKE => Like::class,
    ];

    private static $markableClasses = [
        self::BOOKMARK => [
            'course' => Course::class,
            'lecture' => Lecture::class,
            'instructor' => Instructor::class,
        ],
        self::REACTION => [
            'course' => Course::class,
        ],
        self::FAVORITE => [
            'course' => Course::class,
            'instructor' => Instructor::class,
        ],
        self::LIKE => [
            'like' => Course::class,
        ]
    ];

    public static function options()
    {
        $reflection = new \ReflectionClass(__CLASS__);
        return array_values($reflection->getConstants());
    }

    public static function getMarkClass($markType)
    {
        return self::$markClasses[$markType] ?? false;
    }

    public static function getMarkableClass($markType, $markableType)
    {
        return self::$markableClasses[$markType][$markableType] ?? false;
    }

}
