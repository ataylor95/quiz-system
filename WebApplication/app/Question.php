<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'quiz_id', 'position', 'question_text', 'type', 'answer1', 'answer2', 'answer3', 'answer4', 'answer5', 'answer6', 'answer7', 'answer8', 'answer9', 'answer10', 
    ];

    /**
     * Gets the quiz that the question belongs to
     *
     * @return quiz 
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Saves a question to the table
     *
     * @param collection $dataToSave - request data from the form
     */
    public static function saveQuestion($dataToSave)
    {
        $nextPosition = count(Question::where('quiz_id', 1)->get()) + 1;

        Question::Create([
            'quiz_id' => $dataToSave['quiz_id'],
            'position' => $nextPosition,
            'question_text' => $dataToSave['question_text'],
            'type' => $dataToSave['type'],
            'answer1' => (is_null($dataToSave['answer1']) ? "" : $dataToSave['answer1']),
            'answer2' => (is_null($dataToSave['answer2']) ? "" : $dataToSave['answer2']),
            'answer3' => (is_null($dataToSave['answer3']) ? "" : $dataToSave['answer3']),
            'answer4' => (is_null($dataToSave['answer4']) ? "" : $dataToSave['answer4']),
            'answer5' => (is_null($dataToSave['answer5']) ? "" : $dataToSave['answer5']),
            'answer6' => (is_null($dataToSave['answer6']) ? "" : $dataToSave['answer6']),
            'answer7' => (is_null($dataToSave['answer7']) ? "" : $dataToSave['answer7']),
            'answer8' => (is_null($dataToSave['answer8']) ? "" : $dataToSave['answer8']),
            'answer9' => (is_null($dataToSave['answer9']) ? "" : $dataToSave['answer9']),
            'answer10' => (is_null($dataToSave['answer10']) ? "" : $dataToSave['answer10']),
        ]);
    }

    /**
     * Updates the db record of the question
     *
     * @param collection $dataToSave  - request data from the form
     * @param int $id
     */
    public static function updateQuestion($dataToSave, $id)
    {
        Question::find($id)->update([
            'question_text' => $dataToSave['question_text'],
            'type' => $dataToSave['type'],
            'answer1' => (is_null($dataToSave['answer1']) ? "" : $dataToSave['answer1']),
            'answer2' => (is_null($dataToSave['answer2']) ? "" : $dataToSave['answer2']),
            'answer3' => (is_null($dataToSave['answer3']) ? "" : $dataToSave['answer3']),
            'answer4' => (is_null($dataToSave['answer4']) ? "" : $dataToSave['answer4']),
            'answer5' => (is_null($dataToSave['answer5']) ? "" : $dataToSave['answer5']),
            'answer6' => (is_null($dataToSave['answer6']) ? "" : $dataToSave['answer6']),
            'answer7' => (is_null($dataToSave['answer7']) ? "" : $dataToSave['answer7']),
            'answer8' => (is_null($dataToSave['answer8']) ? "" : $dataToSave['answer8']),
            'answer9' => (is_null($dataToSave['answer9']) ? "" : $dataToSave['answer9']),
            'answer10' => (is_null($dataToSave['answer10']) ? "" : $dataToSave['answer10']),
        ]);
    }

    /*
     * Deletes the questions by id
     *
     * @param int $questionID - id of question
     */
    public static function deleteQuestion($questionID)
    {
        Question::destroy($questionID);
    }
}
