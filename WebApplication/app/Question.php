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
        $nextPosition = count(Question::where('quiz_id', $dataToSave['quiz_id'])->get()) + 1;

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
     * Also shift all the positions of subsequent questions
     *
     * @param int $questionID - id of question
     */
    public static function deleteQuestion($questionID)
    {
        $question = Question::find($questionID);
        
        //Get all the questions with a position < question being deleted
        $questionsLaterInQuiz = Question::where('quiz_id', '=', $question->quiz_id)
            ->where('position', '>', $question->position)
            ->get();

        //Loop over the questions and move reduce their position
        //It does kind of assume quizzes have a sensible number of questions
        //Not like say >100 questions
        //It probably still wouldnt matter that much but you never know...
        foreach ($questionsLaterInQuiz as $question) {
            Question::find($question->id)->update([
                'position' => $question->position - 1
            ]);
        }

        Question::destroy($questionID);
    }

    public static function changePosition($question, $direction)
    {
        $currentPosition = $question->position;
        $quizID = $question->quiz->id;
        if ($direction == "up") {
            $newPosition = $currentPosition + 1;
            
            $positionOccupied = Question::checkPosition($currentPosition, $newPosition, $quizID);
            Question::swapOrUpdate($positionOccupied, $question, $newPosition);
        } else if ($direction == "down") {
            $newPosition = $currentPosition - 1;    

            $positionOccupied = Question::checkPosition($currentPosition, $newPosition, $quizID);
            Question::swapOrUpdate($positionOccupied, $question, $newPosition);
        }
    }

    public static function swapOrUpdate($positionOccupied, $question, $newPosition)
    {
        if ($positionOccupied[0]) {
            Question::swapPositions($positionOccupied[2], $positionOccupied[1], $question);            
        } else {
            Question::find($question->id)->update([
                'position' => $newPosition,
            ]);
        }
    }

    public static function checkPosition($currentPosition, $newPosition, $quizID)
    {
        $questionAtNewPos = Question::where('position', $newPosition)
            ->where('quiz_id', $quizID)
            ->get();
        $slideAtNewPos = Slide::where('position', $newPosition)
            ->where('quiz_id', $quizID)
            ->get();

        if (sizeof($questionAtNewPos)) {
            return [true, $questionAtNewPos[0], "question"];
        }
        if (sizeof($slideAtNewPos)) {
            return [true, $slideAtNewPos[0], "slide"];
        }

        return [false];
    }

    public static function swapPositions($type, $currentItem, $movingQuestion)
    {
        $newPosition = $currentItem->position;
        $oldPosition = $movingQuestion->position;

        if ($type == "question") {
            //Update item in the wanted position to filler position
            Question::find($currentItem->id)->update([
                'position' => 9999,
            ]);

            //Update item you want to move
            Question::find($movingQuestion->id)->update([
                'position' => $newPosition,
            ]);

            //Update the item that used to occupy the spot
            Question::find($currentItem->id)->update([
                'position' => $oldPosition,
            ]);
        } else if ($type == "slide") {
            //Update item in the wanted position to filler position
            Slide::find($currentItem->id)->update([
                'position' => 9999,
            ]);

            //Update item you want to move
            Question::find($movingQuestion->id)->update([
                'position' => $newPosition,
            ]);

            //Update the item that used to occupy the spot
            Slide::find($currentItem->id)->update([
                'position' => $oldPosition,
            ]);
        }
    }
}
