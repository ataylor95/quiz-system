<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\Quiz;
use App\Question;
use App\Session;

class SecurityTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Tests sql injection during quiz creation
     *
     * @return void
     */
    public function testSqlInjectionQuiz()
    {
        $quizName = "SQL Injection";
        $quizDesc = "INSERT INTO `quizzes` (`name`, `desc`, `user_id`, `created_at`, `updated_at`) VALUES ('SQL Injection', 'this is an attack', '1', now(), now());";

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

        //Check the database too
        $this->assertDatabaseMissing("quizzes", [
          'name' => 'SQL Injection', 
          'desc' => 'this is an attack'
        ], $connection = null);

            //As the text should be escaped, it will be in the database as the description, not a new row
        $this->assertDatabaseHas("quizzes", [
          'name' => $quizName, 
          'desc' => $quizDesc
        ], $connection = null);
    }

    /**
     * Tests sql injection during question creation
     *
     * @return void
     */
    public function testSqlInjectionQuestion()
    {
        $questionName = "INSERT INTO `quizzes` (`name`, `desc`, `user_id`, `created_at`, `updated_at`) VALUES ('SQL Injection', 'this is an attack', '1', now(), now());";

        $user = factory(User::class)->create(['id' => 2]);
        factory(Session::class)->create(['user_id' => $user->id]);
        $quiz = factory(Quiz::class)->create(['user_id' => $user->id]);

        $this->browse(function ($browser) use ($user, $quiz, $questionName) {
            $browser->loginAs($user->id)
                    ->visit('/quizzes')
                    ->clickLink('View') //Only one quiz so it will be the only 'View' link
                    ->clickLink('Add Question')
                    ->type('question_text', $questionName)
                    ->select('type', 'multi_select')
                    ->type('answer1', 'Hello')
                    ->type('answer2', 'Boop')
                    ->press('Create')
                    ->assertSee($quiz->name)
                    ->assertSee($questionName);
        });

        //Check the database too
        $this->assertDatabaseMissing("quizzes", [
          'name' => 'SQL Injection', 
          'desc' => 'this is an attack'
        ], $connection = null);

            //As the text should be escaped, it will be in the database as the name, not a new row
        $this->assertDatabaseHas("questions", [
          'question_text' => $questionName, 
        ], $connection = null);
    }

    /**
     * Tests sql injection when a user searches for a session
     *
     * @return void
     */
    public function testSqlInjectionSessionSearch()
    {
        $injection = "INSERT INTO `quizzes` (`name`, `desc`, `user_id`, `created_at`, `updated_at`) VALUES ('SQL Injection', 'this is an attack', '1', now(), now());";

        $this->browse(function ($browser) use ($injection) {
            $browser->visit('/')
                ->type('session_key', $injection)
                ->press('Join');
        });

        $this->assertDatabaseMissing("quizzes", [
          'name' => 'SQL Injection', 
          'desc' => 'this is an attack'
        ], $connection = null);
    }

    /**
     * Tests xss attack when a lecturer creates a quiz and
     * attempts to add some javascript
     *
     * @return void
     */
    public function testXSSAttackQuiz()
    {
        $quizName = "XSS test";
        $quizDesc = "<script>alert('hey xss');</script>";

        $user = factory(User::class)->create();
        factory(Session::class)->create(['user_id' => $user->id]);

        $this->browse(function ($browser) use ($user, $quizName, $quizDesc) {
            $browser->loginAs($user->id)
                    ->visit('/quizzes')
                    ->clickLink('New Quiz')
                    ->type('name', $quizName)
                    ->type('desc', $quizDesc)
                    ->press('Create')
          ->assertSourceMissing($quizDesc);
        });
        //Whilst the code is technically on the page, it is escaped so in the
        //source code looks like &lt;script&gt;alert('hey xss');&lt;/script&gt;
    }

    /**
     * Tests xss attack when a user searches for a session and
     * attempts to add some javascript
     *
     * @return void
     */
    public function testXSSAttackSessionSearch()
    {
        $xss = "<script>alert('hey xss');</script>";

        $this->browse(function ($browser) use ($xss) {
            $browser->visit('/')
                ->type('session_key', $xss)
                ->press('Join')
        ->assertSourceMissing($xss);
        });
    }
}
