<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    /**
     * Gets the quiz that this answer belongs to
     *
     * @return Collection quiz
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
