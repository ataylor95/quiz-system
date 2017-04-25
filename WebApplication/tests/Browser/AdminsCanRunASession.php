<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\Session;
use App\Quiz;
use App\Question;

class AdminsCanRunASession extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Tests if an admin can hit run and if it redirects to the session
     * page and the title and desc of quiz are present
     */
    public function testAdminCanRunQuiz()
    {
        $user = factory(User::class)->create();
        factory(Session::class)->create(['user_id' => $user->id]);
        $quiz = factory(Quiz::class)->create(['user_id' => $user->id]);

        $this->browse(function ($browser) use ($user, $quiz) {
            $browser->loginAs($user->id)
                    ->visit('/quizzes')
                    ->clickLink('Run') //Only one quiz so it will be the only 'View' link
                    ->assertSee($quiz->name)
                    ->assertSee($quiz->desc)
                    ->assertPathIs('/quiz/' . $user->session->session_key);
        });
    }

    /**
     * Tests the admin changing quizzes
     */
    public function testAdminChangesWhichQuizIsRunning()
    {
        $user = factory(User::class)->create();
        factory(Session::class)->create(['user_id' => $user->id]);
        $quiz1 = factory(Quiz::class)->create(['user_id' => $user->id]);
        $quiz2 = factory(Quiz::class)->create(['user_id' => $user->id]);

        //The reason for using press over clicklink is that clicklink
        //just clicks the first matching item, and cannot click based on id
        //Dumb but this works
        $this->browse(function ($browser) use ($user, $quiz1, $quiz2) {
            $browser->loginAs($user->id)
                    ->visit('/quizzes')
                    ->press('#quiz-' . $quiz1->id) 
                    ->assertSee($quiz1->name)
                    ->assertSee($quiz1->desc)
                    ->visit('/quizzes')
                    ->press('#quiz-' . $quiz2->id)
                    ->assertSee($quiz2->name)
                    ->assertSee($quiz2->desc);
        });
    }

    /**
     * This tests the next and prev buttons
     * NOTE: Not working
     * Dusk appears to not work with Pusher/ WebSockets
     * Therefore removed from execution by removing 'test' 
     * from start of function name
     */
    public function NextAndPrevButtons()
    {
        $user = factory(User::class)->create();
        factory(Session::class)->create(['user_id' => $user->id]);
        $quiz = factory(Quiz::class)->create(['user_id' => $user->id]);
        $question = factory(Question::class)->create(['quiz_id' => $quiz->id]);

        $this->browse(function ($browser) use ($user, $quiz, $question) {
            $browser->loginAs($user->id)
                ->visit('/quizzes')
                ->clickLink('View')
                ->assertSee($quiz->name)
                ->assertSee($quiz->desc)
                ->assertSee($question->question_text)
                ->visit('/quizzes')
                ->press('#quiz-' . $quiz->id) 
                ->assertSee($quiz->name)
                ->assertSee($quiz->desc)
                ->press('#quiz-next')
                ->pause(2000)
                ->assertSee($question->question_text)
                ->assertSee('Submit');
        });
    }

    /**
     * Use a refresh instead of waiting for the WebSockets
     * Proves that the prev/ next button actually does update
     * in the database and that new users land on the correct
     * page
     */
    public function testSecondUserWithRefresh()
    {
        $user = factory(User::class)->create();
        factory(Session::class)->create(['user_id' => $user->id]);
        $quiz = factory(Quiz::class)->create(['user_id' => $user->id]);
        $question = factory(Question::class)->create(['quiz_id' => $quiz->id]);

        $this->browse(function ($first, $second) use ($user, $quiz, $question) {
            $first->loginAs($user->id)
                ->visit('/quizzes')
                ->press('#quiz-' . $quiz->id) 
                ->assertSee($quiz->name)
                ->assertSee($quiz->desc);

            $second->visit('/quiz/' . $user->session->session_key)
                ->assertSee($quiz->name);

            $first->press('#quiz-next')->pause(1000);

            //Seeing as WebSockets dont seem to work, refresh it
            //Users dont have to do this though
            $second->refresh()
                ->assertSee($question->question_text);
        });
    }
}
