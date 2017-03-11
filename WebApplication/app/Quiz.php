<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Question;
use App\QuizQuestion;

class Quiz extends Model
{
    protected $fillable = [
        'name', 'desc', 'user_id',
    ];

    /*
     * Deletes the quiz and associated questions by quiz id
     *
     * @param int $id - id of quiz
     */
    public static function deleteQuiz($id)
    {
        //First delete the questions, then delete the Quiz
        Question::deleteByQuiz($id);
        Quiz::destroy($id);
    }
}
