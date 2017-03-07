<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    //
    protected $table = 'quizzes_questions';
    protected $fillable = [
        'quiz_id', 'question_id'
    ];
}
