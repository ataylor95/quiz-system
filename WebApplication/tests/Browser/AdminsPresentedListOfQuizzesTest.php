<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\Quiz;

class AdminsPresentedListOfQuizzesTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test to see if you are on the quiz page
     *
     * @return void
     */
    public function testOnQuizPage()
    {
        $user = factory(User::class)->create([
            'email' => 'test@example.com',
        ]);

        $this->browse(function ($browser) use ($user) {
            $browser->loginAs($user->id)
                    ->visit('/home')
                    ->assertSee('Your Quizzes');
        });
    }

    /**
     * Test to see if quiz belonging to user is there
     *
     * @return void
     */
    public function testIsQuizPresent()
    {
        //So apparently Chrome remembers that user with id of 1 was logged in
        //To fix this, the new user created should be id of 2
        //I could just look for the test name but it feels more sensible to
        //create a different user in case another test driver is used that
        //doesn't remember
        $user = factory(User::class)->create(['id' => 2]);
        $quiz = factory(Quiz::class)->create([
            'user_id' => $user->id,
            'name' => 'This is a test quiz'
        ]);

        $this->browse(function ($browser) use ($user, $quiz){
            $browser->loginAs($user->id)
                    ->visit('/quizzes')
                    ->assertSee($quiz->name);
        });
    }

    /**
     * Test to see if quiz not belonging to user is not there
     * Also see if their test if there
     *
     * @return void
     */
    public function testQuizIsNotPresent()
    {
        $user = factory(User::class)->create(['id' => 3]);
        $quiz = factory(Quiz::class)->create([
            'user_id' => $user->id,
            'name' => 'This is a another test quiz'
        ]);
        //The quiz factory usually creates a new user with the quiz
        //So if we don't specify, the quiz is created for that user
        $quiz2 = factory(Quiz::class)->create([
            'name' => 'Someone elses quiz'
        ]);

        $this->browse(function ($browser) use ($user, $quiz, $quiz2){
            $browser->loginAs($user->id)
                    ->visit('/quizzes')
                    ->assertSee($quiz->name)
                    ->assertDontSee($quiz2->name);
        });
    }
}
