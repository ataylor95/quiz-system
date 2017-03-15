<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\Quiz;
use App\Question;

class EditExistingQuizTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Tests deleting a quiz
     *
     * @return void
     */
    public function testDeleteQuiz()
    {
        $user = factory(User::class)->create();
        $quiz = factory(Quiz::class)->create(['user_id' => 1]);

        $this->browse(function ($browser) use ($user, $quiz) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'secret')
                    ->press('Login')
                    ->assertSee($quiz->name)
                    ->press('Delete')
                    ->assertPathIs('/home') //Make sure directed to same page
                    ->assertDontSee($quiz->name);
        });
    }

    /**
     * Tests deleting a question
     *
     * @return void
     */
    public function testDeleteQuestion()
    {
        $user = factory(User::class)->create(['id' => 2]);
        $quiz = factory(Quiz::class)->create(['user_id' => 2]);
        $question = factory(Question::class)->create(['quiz_id' => $quiz->id]);

        $this->browse(function ($browser) use ($user, $quiz, $question) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'secret')
                    ->press('Login')
                    ->clickLink('View')
                    ->assertSee($question->question_text)
                    ->press('Delete')
                    ->assertPathIs('/quizzes/' . $quiz->id) //Make sure directed to quiz
                    ->assertDontSee($question->question_text);
        });
    }

    /**
     * Tests editing a quiz
     *
     * @return void
     */
    public function testEditQuiz()
    {
    }

    /**
     * Tests editing a question
     *
     * @return void
     */
    public function testEditQuestion()
    {
    }
}
