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

    /**
     * Gets all the questions belonging to the quiz
     *
     * @return collection - questions
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Saves the newly created quiz to the db
     *
     * @param string $name
     * @param string $desc
     * @param int $user - id of the user
     * @return int $id - id of the newly created quiz
     */
    public static function saveQuiz($name, $desc, $user)
    {
        $id = Quiz::Create([
            'name' => $name,
            'desc' => $desc,
            'user_id' =>$user 
        ])->id;

        return $id;
    }

    /**
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
