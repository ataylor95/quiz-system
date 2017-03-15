<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;

class CreateNewQuizTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testCreateNewQuiz()
    {
        $quizName = "Test Quiz";
        $quizDesc = "Test Quiz Please Ignore";

        $user = factory(User::class)->create();

        $this->browse(function ($browser) use ($user, $quizName, $quizDesc) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'secret')
                    ->press('Login')
                    ->clickLink('New Quiz')
                    ->type('name', $quizName)
                    ->type('desc', $quizDesc)
                    ->press('Create')
                    ->assertSee($quizName)
                    ->assertSee($quizDesc)
                    ->assertSee('Add Question');
        });
    }
}
