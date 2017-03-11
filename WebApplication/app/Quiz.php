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
     * Get the questions associated with the Quiz via its id 
     *
     * @param int $quizID - id of quiz
     * @return collection $questions
     */
    public static function getQuestions($quizID)
    {
		$questions = QuizQuestion::leftJoin('questions', function($join){
				$join->on('quizzes_questions.question_id', '=', 'questions.id');
			})->where('quiz_id', $quizID)->get();

        return $questions;
    }

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
