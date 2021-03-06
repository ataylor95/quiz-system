<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Session;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Gets the session that belongs to this quiz 
     *
     * @return session
     */
    public function session()
    {
        return $this->hasOne(Session::class);
    }

    /**
     * Ends the quiz in the session table 
     * 
     * @param string $name
     * @param string $email
     * @param string $sessionKey - the session_key
     * @param int $id - of user
     */
    public static function updateUser($name, $email, $sessionKey, $id)
    {
        User::find($id)->update([
            'name' => $name,
            'email' => $email,
        ]);
        
        Session::updateSessionKey($sessionKey, $id);
    }
}
