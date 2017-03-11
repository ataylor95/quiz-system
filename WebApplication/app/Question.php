<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\QuizQuestion;

class Question extends Model
{
    protected $fillable = [
        'question_text', 'type', 'answer1', 'answer2', 'answer3', 'answer4', 'answer5', 'answer6', 'answer7', 'answer8', 'answer9', 'answer10', 
    ];

    /*
     * Deletes the questions by Quiz id
     *
     * @param int $quizID - id of quiz
     */
    public static function deleteByQuiz($quizID)
    {
        $questions = QuizQuestion::where('quiz_id', '=', $quizID)->get();
        $questionIDs = [];
        foreach ($questions as $quiz) {
            $questionIDs[] = $quiz['question_id'];
        }
        Question::destroy($questionIDs);
    }
}
