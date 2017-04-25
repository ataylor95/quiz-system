<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\Quiz;
use App\Question;
use App\Session;

class CreateNewQuizTest extends DuskTestCase
{
    use DatabaseMigrations;
//fail
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
        factory(Session::class)->create(['user_id' => $user->id]);

        $this->browse(function ($browser) use ($user, $quizName, $quizDesc) {
            $browser->loginAs($user->id)
                    ->visit('/quizzes')
                    ->clickLink('New Quiz')
                    ->type('name', $quizName)
                    ->type('desc', $quizDesc)
                    ->press('Create')
                    ->assertSee($quizName)
                    ->assertSee($quizDesc)
                    ->assertSee('Add Question');
        });
    }

//pass
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
        factory(Session::class)->create(['user_id' => $user->id]);

        /*So we check to see that the browser keeps you on the same page
          This is because html validation is hard to see, also it might
          be different on different drivers like PhantomJS vs Chrome*/
        $this->browse(function ($browser) use ($user, $quizName, $quizDesc) {
            $browser->loginAs($user->id)
                    ->visit('/quizzes')
                    ->clickLink('New Quiz')
                    ->type('name', $quizName)
                    ->type('desc', $quizDesc)
                    ->press('Create')
                    ->assertPathIs('/quizzes/create');
        });
    }

//pass
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
        factory(Session::class)->create(['user_id' => $user->id]);

        $this->browse(function ($browser) use ($user, $quizName, $quizDesc) {
            $browser->loginAs($user->id)
                    ->visit('/quizzes')
                    ->clickLink('New Quiz')
                    ->type('name', $quizName)
                    ->type('desc', $quizDesc)
                    ->press('Create')
                    ->assertSee('The name must be at least 5 characters');
        });
    }

//fail
    /**
     * Test whether a quiz owns a question
     *
     * @return void
     */
    public function testQuizContainsQuestions()
    {
        $user = factory(User::class)->create(['id' => 4]);
        factory(Session::class)->create(['user_id' => $user->id]);
        $quiz = factory(Quiz::class)->create(['user_id' => 4]);
        $question = factory(Question::class)->create(['quiz_id' => $quiz->id]);

        $this->browse(function ($browser) use ($user, $question) {
            $browser->loginAs($user->id)
                    ->visit('/quizzes')
                    ->clickLink('View') //Only one quiz so it will be the only 'View' link
                    ->assertSee($question->question_text);
        });
    }

//error
    /**
     * Test adding a question to a quiz
     *
     * @return void 
     */
    public function testAddQuestion()
    {
        $user = factory(User::class)->create(['id' => 5]);
        factory(Session::class)->create(['user_id' => $user->id]);
        $quiz = factory(Quiz::class)->create(['user_id' => 5]);

        $this->browse(function ($browser) use ($user, $quiz) {
            $browser->loginAs($user->id)
                    ->visit('/quizzes')
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

//error
    /**
     * Test the question page
     *
     * @return void
     */
    public function testQuestionPage()
    {
        $user = factory(User::class)->create(['id' => 6]);
        factory(Session::class)->create(['user_id' => $user->id]);
        $quiz = factory(Quiz::class)->create(['user_id' => $user->id]);
        $question = factory(Question::class)->create(['quiz_id' => $quiz->id]);

        $this->browse(function ($browser) use ($user, $question) {
            $browser->loginAs($user->id)
                    ->visit('/quizzes')
                    ->clickLink('View') //Only one quiz so it will be the only 'View' link
                    ->clickLink('View') //Only one question so it will be the only 'View' link
                    ->assertSee($question->question_text)
                    ->assertSee(config('questions')['types'][$question->type])
                    ->assertSee($question->answer1)
                    ->assertSee($question->answer2);
        });
    }
}
