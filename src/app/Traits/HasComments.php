<?php

namespace App\Traits;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasComments
{
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function addComment(array $commentData)
    {
        $comment = new Comment($commentData);
        return $this->comments()->save($comment);
    }

}
