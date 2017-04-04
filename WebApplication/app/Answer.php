<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Session;
use App\Quiz;

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

    /**
     * Gets the results of a question from the db 
     *
     * @param String $sessionKey - key of the session
     */
	public static function getResults($sessionKey)
	{
        $session = Session::where('session_key', $sessionKey)->get()[0];
        $answers = Answer::where('session_id', $session->id)
			->where('question', $session->position)
			->get();
		$listOfAnswers = array();

		foreach ($answers as $answer) {
			$listOfAnswers[] = $answer->answer;
		}

		$occurences = array_count_values($listOfAnswers);

        $finalList = Answer::mergeAnswersArrays($session, $occurences);

        return $finalList;    
	}

    /**
     * Change the answer array keys to be the actual answers for readability
     *
     * @param Collection $session
     * @param array $listOfAnswers key-value list of answers like [answer1 => 1, answer2 => 3]
     * @return array of answers
     */
    private static function mergeAnswersArrays($session, $listOfAnswers)
    {
        /*$question = Quiz::find($session->quiz_id)
            ->questions;
        ->where('position', 3);*/
        //We cant use quiz->questions because doing a where on that
        //returns the questions as a collection, filtering it down
        //with another where returns the question but at the array index
        //it was in the collection. Theres no way to get that index
        //so we have to use a Question::where
        //TODO: add this to report
        $question = Question::where("quiz_id", $session->quiz_id)
            ->where('position', $session->position)->get()[0];

        //Loop over all the answers and set a new array item with the value
        //of the old one
        //Then unset the old array item
        foreach ($listOfAnswers as $key => $value) {
            $listOfAnswers[$question->$key] = $listOfAnswers[$key];
            unset($listOfAnswers[$key]);
        }

        return $listOfAnswers;
    }
}
