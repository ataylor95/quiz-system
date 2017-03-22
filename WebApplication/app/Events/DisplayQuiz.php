<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Session;

class DisplayQuiz implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    //Public variables are broadcast
    public $quiz;
    private $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($quiz, $user)
    {
        $this->quiz = $quiz;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        //We broadcast on the quiz_*session_key* channel so we 
        //can have many channels for each user
        $userKey = Session::getSessionKey($this->user)[0];
        return ['quiz_' . $userKey->session_key];
    }
}
