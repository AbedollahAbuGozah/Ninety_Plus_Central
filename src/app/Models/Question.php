<?php

namespace App\Models;

use App\Traits\Filterable;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory, Filterable, Sortable;

    protected $guarded = ['id'];

    protected $filterable = [
        'text',
    ];
    protected $sortable = [
        'crated_at',
    ];


    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function answerOptions()
    {
        return $this->hasMany(AnswerOption::class, 'question_id', 'id');
    }

    public function studentAnswer()
    {
        return $this->hasMany(StudentAnswer::class);
    }
}
