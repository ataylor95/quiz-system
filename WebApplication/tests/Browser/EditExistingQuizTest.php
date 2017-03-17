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
        $quiz = factory(Quiz::class)->create(['user_id' => $user->id]);

        $this->browse(function ($browser) use ($user, $quiz) {
            $browser->loginAs($user->id)
                    ->visit('/quizzes')
                    ->assertSee($quiz->name)
                    ->press('Delete')
                    ->assertPathIs('/quizzes') //Make sure directed to same page
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
        $quiz = factory(Quiz::class)->create(['user_id' => $user->id]);
        $question = factory(Question::class)->create(['quiz_id' => $quiz->id]);

        $this->browse(function ($browser) use ($user, $quiz, $question) {
            $browser->loginAs($user->id)
                    ->visit('/quizzes')
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
        $user = factory(User::class)->create(['id' => 3]);
        $quiz = factory(Quiz::class)->create(['user_id' => $user->id]);
        $message = "42 is the answer to life, the universe and everything";

        $this->browse(function ($browser) use ($user, $quiz, $message) {
            $browser->loginAs($user->id)
                    ->visit('/quizzes')
                    ->clickLink('View')
                    ->clickLink('Edit')
                    ->assertInputValue('name', $quiz->name)
                    ->assertInputValue('desc', $quiz->desc)
                    ->type('name', $message)
                    ->press('Update')
                    ->assertPathIs('/quizzes/' . $quiz->id) //Make sure directed to quiz
                    ->assertSee($message);
        });
    }

    /**
     * Tests editing a question
     *
     * @return void
     */
    public function testEditQuestion()
    {
        $user = factory(User::class)->create(['id' => 4]);
        $quiz = factory(Quiz::class)->create(['user_id' => $user->id]);
        $question = factory(Question::class)->create(['quiz_id' => $quiz->id]);
        $message = "Its turtles all the way down";

        $this->browse(function ($browser) use ($user, $quiz, $question, $message) {
            $browser->loginAs($user->id)
                    ->visit('/questions/' . $question->id . '/edit?quiz=' . $quiz->id)
                    ->assertInputValue('question_text', $question->question_text)
                    ->assertSelected('type', $question->type)
                    ->assertInputValue('answer1', $question->answer1)
                    ->assertInputValue('answer2', $question->answer2)
                    ->type('question_text', $message)
                    ->press('Update')
                    ->assertPathIs('/quizzes/' . $quiz->id) //Make sure directed to quiz
                    ->assertSee($message);
        });
    }
}
