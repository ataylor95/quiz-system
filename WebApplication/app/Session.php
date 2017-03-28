<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Session extends Model
{
    protected $fillable = [
        'session_key', 'quiz_id', 'position', 'running', 'user_id',
    ];

    /**
     * Gets the user that the session belongs to
     *
     * @return user 
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Gets the session key used in the channel name
     *
     * @param int $userID
     * @return Collection
     */
    public static function getSessionKey($userID)
    {
       return Session::where('user_id', $userID)->get(['session_key']); 
    } 

    /**
     * Checks if a session key exists in the database
     *
     * @param String $key - session key
     * @return Boolean - true if it is present in table
     */
    public static function isASessionKey($key)
    {
        $record = Session::where('session_key', $key)->get();
        return (sizeof($record) > 0);
    }

    /**
     * Gets the session key used in the channel name
     *
     * @param int $quizID
     * @param int $userID
     */
    public static function setQuizRunning($quizID, $userID)
    {
        //Set the quiz, reset the position in case it wasnt earlier
        //All set running to true
        Session::where('user_id', $userID)->update([
            'quiz_id' => $quizID,
            'position' => 0,
            'running' => true
        ]);
    } 

    /**
     * Increments or decrements the question counter in the session
     * 
     * @param int $userID
     * @param boolean $incrementing - true for next question, false for prev
     * @return int $position - position of the quiz
     */
    public static function prevNextQuestion($userID, $incrementing)
    {
        //TODO: Could this be more OOP?
        $position = Session::where('user_id', $userID)->get(['position'])[0]->position;
        if ($incrementing) {
            $newPosition = $position + 1;
        } else {
            $newPosition = $position - 1;
        }

        $newPosition = Session::validatePosition($newPosition, $userID);

        Session::where('user_id', $userID)->update([
            'position' => $newPosition,
            'running' => true
        ]);
        
        return $newPosition;
    }

    /**
     * Validates the position of the quiz, ensuring it is not <0 or >number of questions 
     * 
     * @param int $newPosition - the proposed new position
     * @param int $userID
     * @return int - the position of the quiz
     */
    private static function validatePosition($newPosition, $userID)
    {
        $quizID = Session::where('user_id', $userID)->get(['quiz_id'])[0]->quiz_id;
        $numQuestions = Question::where([['quiz_id', '=', $quizID]])->get()->count();

        if ($newPosition < 0) {
            $newPosition = 0;
        } else if ($newPosition > $numQuestions) {
            $newPosition = $numQuestions;
        }
       return $newPosition; 
    }

    /**
     * Ends the quiz in the session table 
     * 
     * @param int $userID
     */
    public static function endQuiz($userID)
    {
        Session::where('user_id', $userID)->update([
            'position' => 0,
            'running' => false,
            'quiz_id' => null
        ]);
    }

    /**
     * Ends the quiz in the session table 
     * 
     * @param int $userID
     * @param int $position in the quiz
     * @return collection $question
     */
    public static function getQuestionForQuiz($userID, $position)
    {
        //TODO: Limit question going above or below max/ min question num
        $quizID = Session::where('user_id', $userID)->get(['quiz_id'])[0]->quiz_id;
        if ($position == 0) {
            $question = ['question' => null];
        } else {
            $question = Question::where([
                ['quiz_id', '=', $quizID], 
                ['position', '=', $position]
            ])->get()[0];
        }
        return $question;
    }

    /**
     * Ends the quiz in the session table 
     * 
     * @param string $key - the session_key
     * @param int $userID
     */
    public static function updateSessionKey($key, $userID)
    {
        Session::where('user_id', '=', $userID)->update([
            'session_key' => $key
        ]);
    }
}
