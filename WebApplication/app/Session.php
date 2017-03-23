<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Session extends Model
{
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
     */
    public static function prevNextQuestion($userID, $incrementing)
    {
        //TODO: Could this be more OOP?
        //TODO: Stop this from goin above or below max/ min num questions
        $position = Session::where('user_id', $userID)->get(['position'])[0]->position;
        if ($incrementing) {
            $newPosition = $position + 1;
        } else {
            $newPosition = $position - 1;
        }

        Session::where('user_id', $userID)->update([
            'position' => $newPosition,
            'running' => true
        ]);
        
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

    public static function getQuestionForQuiz($userID, $position)
    {
        //TODO: Limit question going above or below max/ min question num
        //TODO: Add a position variable to the questions table
        $quizID = Session::where('user_id', $userID)->get(['quiz_id'])[0]->quiz_id;
        $question = Quiz::find($quizID)->questions[$position - 1];
        return $question;
    }
}
