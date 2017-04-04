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

    public static function saveResult($session, $userSession, $answer)
    {
        $session = Session::where('session_key', $session)->get()[0];

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

    public static function deleteResultsAtQuizEnd($session)
    {
        $answers = Session::where('session_key', $session)->get()[0]->answers;
        foreach ($answers as $answer) {
            Answer::destroy($answer->id);
        }
    }
}
