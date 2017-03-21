<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DisplayQuiz implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $quiz;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($quiz)
    {
        $this->quiz = $quiz;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        //We broadcast on the quiz_session channel so we 
        //can have many channels
        $user_key = auth()->user()->session_key;
        return ['quiz_' . $user_key];
    }
}
