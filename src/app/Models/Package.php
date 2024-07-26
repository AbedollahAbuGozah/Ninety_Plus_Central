<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Package extends Model implements HasMedia
{
    use HasFactory, Filterable, InteractsWithMedia;

    const PACKAGE_COVER_IMAGE_MEDIA_COLLECTION = 'package_cover_image';

    protected $guarded = ['id'];

    protected $casts = [
        'properties' => 'json',
    ];

    protected array $filterable = [
        'name',
        'module_id',
    ];

    public function chapters()
    {
        return $this->belongsToMany(Chapter::class, 'chapter_packages');
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function getCoverImageAttribute()
    {
        return $this->properties['cover_image'] ?? '';
    }
    public function getGiftedPointsAttribute()
    {
        return $this->properties['gifted_points'] ?? 0;
    }

}
