<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\Session;
use App\Quiz;
use App\Question;

class SlidesTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test that positions are changed correctly
     *
     * @return void
     */
    public function testChangePosition()
    {
        $user = factory(User::class)->create();
        factory(Session::class)->create(['user_id' => $user->id]);
        $quiz = factory(Quiz::class)->create([
            'user_id' => $user->id,
            'name' => 'This is a test quiz'
        ]);
        $question1 = factory(Question::class)->create([
            'quiz_id' => $quiz->id,
            'position' => 1
        ]);
        $question2 = factory(Question::class)->create([
            'quiz_id' => $quiz->id,
            'position' => 2
        ]);

        $this->browse(function ($browser) use ($user, $quiz, $question1, $question2) {
            $i = Question::find($question2->id);
            $this->assertEquals($i->position, 2);

            $browser->loginAs($user->id)
                ->visit('/quizzes/' . $quiz->id)
                ->assertSee($question1->question_text)
                ->assertSee($question2->question_text)
                ->press('#question-' . $question2->position . '-up')->pause(100)
                ->refresh();

            $j = Question::find($question2->id);
            $this->assertEquals($j->position, 1);
        });


    
    }
}
