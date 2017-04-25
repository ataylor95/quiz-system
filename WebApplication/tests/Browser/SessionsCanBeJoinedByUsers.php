<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\Session;
use App\Quiz;
use App\Question;

class SessionsCanBeJoinedByUsers extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test the user joining the start of a session
     */
    public function testUserJoinsStartOfSession()
    {
        $user = factory(User::class)->create();
        $quiz = factory(Quiz::class)->create(['user_id' => $user->id]);
        factory(Session::class)->create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'running' => true
        ]);
        
        $this->browse(function ($browser) use ($user, $quiz) {
            $browser->visit('/')
                ->type('session_key', $user->session->session_key)
                ->press('Join')
                ->assertPathIs('/quiz/' . $user->session->session_key)
                ->assertSee($quiz->name);
        });
    }

    /**
     * Test the user joining midway through a session
     */
    public function testUserJoinsRunningSession()
    {
        $user = factory(User::class)->create();
        $quiz = factory(Quiz::class)->create(['user_id' => $user->id]);
        $question = factory(Question::class)->create(['quiz_id' => $quiz->id]);
        factory(Session::class)->create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'running' => true,
            'position' => 1
        ]);
        
        $this->browse(function ($browser) use ($user, $quiz, $question) {
            $browser->visit('/')
                ->type('session_key', $user->session->session_key)
                ->press('Join')
                ->assertPathIs('/quiz/' . $user->session->session_key)
                ->assertSee($question->question_text)
                ->assertDontSee($quiz->name);
        });
    }

    /**
     * Test the user running a session that is not running
     */
    public function testUserJoinsSessionNotRunning()
    {
        $user = factory(User::class)->create();
        $quiz = factory(Quiz::class)->create(['user_id' => $user->id]);
        factory(Session::class)->create(['user_id' => $user->id]);
        
        $this->browse(function ($browser) use ($user, $quiz) {
            $browser->visit('/')
                ->type('session_key', $user->session->session_key)
                ->press('Join')
                ->assertPathIs('/quiz/' . $user->session->session_key)
                ->assertDontSee($quiz->name)
                ->assertSee('No quiz running for: ' . $user->session->session_key);
        });
    }

    /**
     * Test a user trying to join a session that doesnt exist
     */
    public function testUserJoinsSessionThatDoesNotExist()
    {
        $this->browse(function ($browser) {
            $browser->visit('/')
                ->type('session_key', 'wolf-359')
                ->press('Join')
                ->assertPathIs('/')
                ->assertSee('No session found with that id, please try again');
        });
    
    }
}
