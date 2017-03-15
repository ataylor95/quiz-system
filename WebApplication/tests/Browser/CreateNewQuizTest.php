<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\Quiz;
use App\Question;

class CreateNewQuizTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test to create a new quiz
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

    /**
     * Test the frontend validation when creating new quiz
     * This test is not fantastic, but it kind of works
     *
     * @return void
     */
    public function testHTMLValidationQuiz()
    {
        $quizName = "";
        $quizDesc = "";

        $user = factory(User::class)->create(['id' => 2]);

        /*So we check to see that the browser keeps you on the same page
          This is because html validation is hard to see, also it might
          be different on different drivers like PhantomJS vs Chrome*/
        $this->browse(function ($browser) use ($user, $quizName, $quizDesc) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'secret')
                    ->press('Login')
                    ->clickLink('New Quiz')
                    ->type('name', $quizName)
                    ->type('desc', $quizDesc)
                    ->press('Create')
                    ->assertPathIs('/quizzes/create');
        });
    }

    /**
     * Test the backend validation when creating new quiz
     * Name should be minimum 5 characters
     *
     * @return void
     */
    public function testValidationQuiz()
    {
        $quizName = "Test"; //Lets try a 4 letter name to fail
        $quizDesc = "Test"; //None on the length of the description

        $user = factory(User::class)->create(['id' => 3]);

        $this->browse(function ($browser) use ($user, $quizName, $quizDesc) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'secret')
                    ->press('Login')
                    ->clickLink('New Quiz')
                    ->type('name', $quizName)
                    ->type('desc', $quizDesc)
                    ->press('Create')
                    ->assertSee('The name must be at least 5 characters');
        });
    }

    /**
     * Test whether a quiz owns a question
     *
     * @return null
     */
    public function testQuizContainsQuestions()
    {
        $user = factory(User::class)->create(['id' => 4]);
        $quiz = factory(Quiz::class)->create(['user_id' => 4]);
        $question = factory(Question::class)->create(['quiz_id' => $quiz->id]);

        $this->browse(function ($browser) use ($user, $question) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'secret')
                    ->press('Login')
                    ->clickLink('View') //Only one quiz so it will be the only 'View' link
                    ->assertSee($question->question_text);
        });
    }

    /**
     * Test adding a question to a quiz
     *
     * @return null
     */
    public function testAddQuestion()
    {
        $user = factory(User::class)->create(['id' => 5]);
        $quiz = factory(Quiz::class)->create(['user_id' => 5]);

        $this->browse(function ($browser) use ($user, $quiz) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'secret')
                    ->press('Login')
                    ->clickLink('View') //Only one quiz so it will be the only 'View' link
                    ->clickLink('Add Question')
                    ->type('question_text', 'Test Question')
                    ->select('type', 'multi_select')
                    ->type('answer1', 'Hello')
                    ->type('answer2', 'Boop')
                    ->press('Create')
                    ->assertSee($quiz->name)
                    ->assertSee('Test Question');
        });
    }
}
