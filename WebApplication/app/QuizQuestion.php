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

    /**
     * Saves the quizquestion record for the questions that been created
     *
     * @param int $quizID
     * @param int $questionID
     */
    public static function saveQuizQuestion($quizID, $questionID)
    {
        QuizQuestion::Create([
            'quiz_id' => $quizID,
            'question_id' => $questionID,
        ]);
    } 
}
