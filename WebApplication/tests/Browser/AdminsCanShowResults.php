<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\Session;
use App\Quiz;
use App\Question;
use App\Answer;

class AdminsCanShowResults extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Testing that the admin user can show a result
     * Also test the showing the number of results
     * NOTE: This test does not work - removing from run
     * Looks like the asserts cannot see inside a canvas tag...
     */
    public function /*test*/AdminCanShowOneResult()
    {
        $user = factory(User::class)->create();
        $quiz = factory(Quiz::class)->create(['user_id' => $user->id]);
        $question = factory(Question::class)->create([
            'quiz_id' => $quiz->id,
            'type' => 'multi_choice'
        ]);
        $session = factory(Session::class)->create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'running' => true,
            'position' => 1
        ]);
        $answer = Answer::create(['session_id' => $session->id,
            'question' => $question->position, 
            'user_session' => 'random', 
            'answer' =>'answer1'
        ]);

        $this->browse(function ($browser) use ($user, $question) {
            $browser->loginAs($user->id)
                ->visit('/quiz/' . $user->session->session_key)
                ->assertSee($question->question_text)
                ->press('Results')
                ->assertSee('Number of responses: 1')
                ->assertSeeIn('#results-box', $question->answer2);
        });
    }

    /**
     * Testing that the Results button adds the hidden iframe
     * Only real thing that Dusk can test with these results
     */
    public function testResultsButton()
    {
        $user = factory(User::class)->create();
        $quiz = factory(Quiz::class)->create(['user_id' => $user->id]);
        $question = factory(Question::class)->create([
            'quiz_id' => $quiz->id,
            'type' => 'multi_choice'
        ]);
        $session = factory(Session::class)->create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'running' => true,
            'position' => 1
        ]);
        $answer = Answer::create(['session_id' => $session->id,
            'question' => $question->position, 
            'user_session' => 'random', 
            'answer' =>'answer1'
        ]);

        $this->browse(function ($browser) use ($user, $question) {
            $browser->loginAs($user->id)
                ->visit('/quiz/' . $user->session->session_key)
                ->assertMissing('.chartjs-hidden-iframe')
                ->press('Results')
                ->pause(100)
                ->assertVisible('.chartjs-hidden-iframe');
        });
    }
}
