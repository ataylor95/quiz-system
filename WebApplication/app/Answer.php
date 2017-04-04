<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Session;

class Answer extends Model
{
    protected $fillable = ['session_id', 'question', 'user_session', 'answer'];

    /**
     * Gets the session that this answer belongs to
     *
     * @return session
     */
    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    /**
     * Saves the answers to the database 
     *
     * @param String $sessionKey - key of the session
     * @param String $userSession - cookie[laravel_session] value
     * @param String $answer - the answer selected
     */
    public static function saveResult($sessionKey, $userSession, $answer)
    {
        $session = Session::where('session_key', $sessionKey)->get()[0];

        Answer::updateOrCreate(
            [
                'session_id' => $session->id,
                'question' => $session->position,
                'user_session' => $userSession,
            ],
            [
                'answer' => $answer
            ]
        );
    }

    /**
     * Deletes all the results associated with a quiz when it is ended 
     *
     * @param String $session - key of the session
     */
    public static function deleteResultsAtQuizEnd($session)
    {
        $answers = Session::where('session_key', $session)->get()[0]->answers;
        foreach ($answers as $answer) {
            Answer::destroy($answer->id);
        }
    }
}
