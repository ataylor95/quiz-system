<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
