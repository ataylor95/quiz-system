<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    protected $fillable = [
        'file_name', 'quiz_id', 'position'
    ];

    /**
     * Gets the quiz that this answer belongs to
     *
     * @return Collection quiz
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Gets the quiz that this answer belongs to
     *
     * @param int $numSlides - number of slides in the pdf 
     * @param int $quizID
     */
    public static function saveSlides($numSlides, $quizID)
    {
        $questions = Quiz::find($quizID)->questions;
        $positions = [];
        foreach ($questions as $question) {
            $positions[] = $question->position;
        }

        $numQuestions = $questions->count();
        $total = $numSlides + $numQuestions;

        $count = 1; //Use the count for the slide number
        for ($i=1; $i<=$total; $i++) {
            if (in_array($i, $positions)) {
                continue; //Skip if this is true
            } else {
                Slide::Create([
                    'file_name' => 'slide-' . $count,
                    'quiz_id' => $quizID,
                    'position' => $i
                ]);

                $count++;
            }
        }
    }
}
