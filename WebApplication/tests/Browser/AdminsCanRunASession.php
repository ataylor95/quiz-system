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
		//The reason for using press over clicklink is that clicklink
		//just clicks the first matching item, and cannot click based on id
		//Dumb but this works
    }
}
