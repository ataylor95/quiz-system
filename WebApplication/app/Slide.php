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

    public static function saveSlides($numSlides, $quizID)
    {
        $questions = Quiz::find($quizID)->questions;
        $positions = [];
        foreach ($questions as $question) {
            $positions[] = $question->position;
        }

        $count = 1; //Use the count for the slide number
        for ($i=1; $i<=$numSlides; $i++) {
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
        dd($numSlides, $positions);        
    }
}
