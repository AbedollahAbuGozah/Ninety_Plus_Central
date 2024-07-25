<?php

namespace App\services;


use App\Models\User;

class ExamService extends BaseService
{
    public function generateRandomQuestions($chapters, $labels)
    {

    }

    public function correctExam($exam, $questionAnswers, User $user = null)
    {
        $user = $user?->resolveUser() ?? auth()->user()->resolveUser();
    }
}


