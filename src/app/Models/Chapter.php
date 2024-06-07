<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function lectures()
    {
        return [
            "title" => fake()->text(),
            "record" => fake()->url(),
            "comments" => [
                "id" => 1,
                "user_id" => 1,
                "lecture_id" => 1,
                "text" => "fuck imam"
            ]
        ];
    }
}
