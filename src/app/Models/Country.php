<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
