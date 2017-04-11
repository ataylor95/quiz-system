<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Question;

class Quiz extends Model
{
    protected $fillable = [
        'name', 'desc', 'user_id',
    ];

    /**
     * Gets the user that this quiz belongs to
     *
     * @return collection - user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Gets all the questions belonging to the quiz
     *
     * @return collection - questions
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Gets all the slides belonging to the quiz
     *
     * @return collection - slides
     */
    public function slides()
    {
        return $this->hasMany(Slide::class);
    }

    /**
     * Saves the newly created quiz to the db
     *
     * @param string $name
     * @param string $desc
     * @param int $user - id of the user
     * @return int $id - id of the newly created quiz
     */
    public static function saveQuiz($name, $desc, $user)
    {
        $id = Quiz::Create([
            'name' => $name,
            'desc' => $desc,
            'user_id' =>$user 
        ])->id;

        return $id;
    }

    /**
     * Saves the newly created quiz to the db
     *
     * @param string $name - name of the quiz
     * @param string $desc - description
     * @param int $id
     */
    public static function updateQuiz($name, $desc, $id)
    {
        //TODO: If you try and do this with an empty desc it errors
        Quiz::find($id)->update([
            'name' => $name,
            'desc' => $desc,     
        ]);
    }

    /**
     * Deletes the quiz
     *
     * @param int $id - id of quiz
     */
    public static function deleteQuiz($id)
    {
        Quiz::destroy($id);
    }
}
