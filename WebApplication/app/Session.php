<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Session extends Model
{
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
        Session::where('user_id', $userID)->update([
            'quiz_id' => $quizID
        ]);
    } 
}
