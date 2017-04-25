<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\Session;
use App\Quiz;
use App\Question;
use App\Answer;

class UsersAnswersTheQuestionsBeingDisplayed extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test of a user submitting an answer
     */
    public function testUserSubmittingAMultipleChoiceAnswer()
    {
        $user = factory(User::class)->create();
        $quiz = factory(Quiz::class)->create(['user_id' => $user->id]);
        $question = factory(Question::class)->create([
            'quiz_id' => $quiz->id,
            'type' => 'multi_choice'
        ]);
        factory(Session::class)->create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'running' => true,
            'position' => 1
        ]);

        $this->browse(function ($browser) use ($user, $question) {
            $browser->visit('/quiz/' . $user->session->session_key)
                ->assertSee($question->question_text)
                ->press($question->answer2)
                ->press('Submit')->pause(100);
            //Pause because as for some reason manages to race to the assertion before
            //inserting the whole thing
        });

        $this->assertDatabaseHas('answers', [
            'answer' => 'answer2',
            'question' => $question->position
        ]);
    }

    /**
     * Test a user changing their answer
     */
    public function testUserChangingTheirAnswer()
    {
        $user = factory(User::class)->create();
        $quiz = factory(Quiz::class)->create(['user_id' => $user->id]);
        $question = factory(Question::class)->create([
            'quiz_id' => $quiz->id,
            'type' => 'multi_choice'
        ]);
        factory(Session::class)->create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'running' => true,
            'position' => 1
        ]);

        $this->browse(function ($browser) use ($user, $question) {
            $browser->visit('/quiz/' . $user->session->session_key)
                ->assertSee($question->question_text)
                ->press($question->answer2)
                ->press('Submit')->pause(1000);
            //Need the pause or the second database assertions doesnt work

            $this->assertDatabaseHas('answers', [
                'answer' => 'answer2',
                'question' => $question->position
            ]);
            
            $browser->press($question->answer1)
                ->press('Submit')->pause(1000);

            $this->assertDatabaseHas('answers', [
                'answer' => 'answer1',
                'question' => $question->position
            ]);
        });

        //We now need to make sure theire is only one result int he table
        $count = count(Answer::where('session_id', $user->session->id)
            ->where('question', $question->position)
            ->get());
        $this->assertEquals($count, 1);
    }

    /**
     * Test multiple users answering the same thing
     */
    public function testMultipleUsersAnswerSame()
    {
        $user = factory(User::class)->create();
        $quiz = factory(Quiz::class)->create(['user_id' => $user->id]);
        $question = factory(Question::class)->create([
            'quiz_id' => $quiz->id,
            'type' => 'multi_choice'
        ]);
        factory(Session::class)->create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'running' => true,
            'position' => 1
        ]);
    
        $this->browse(function ($first, $second) use ($user, $question) {
            $first->visit('/quiz/' . $user->session->session_key)
                ->assertSee($question->question_text)
                ->press($question->answer2)
                ->press('Submit')->pause(100);
            $second->visit('/quiz/' . $user->session->session_key)
                ->assertSee($question->question_text)
                ->press($question->answer2)
                ->press('Submit')->pause(100);
        });
        $count = count(Answer::where('answer', 'answer2')
            ->where('question', $question->position)
            ->get());

        $this->assertEquals($count, 2);
    }

    /**
     * Test users answering different things
     */
    public function testMultipleDifferentAnswers()
    {
        $user = factory(User::class)->create();
        $quiz = factory(Quiz::class)->create(['user_id' => $user->id]);
        $question = factory(Question::class)->create([
            'quiz_id' => $quiz->id,
            'type' => 'multi_choice'
        ]);
        factory(Session::class)->create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'running' => true,
            'position' => 1
        ]);
    
        $this->browse(function ($first, $second) use ($user, $question) {
            $first->visit('/quiz/' . $user->session->session_key)
                ->assertSee($question->question_text)
                ->press($question->answer2)
                ->press('Submit')->pause(100);
            $second->visit('/quiz/' . $user->session->session_key)
                ->assertSee($question->question_text)
                ->press($question->answer1)
                ->press('Submit')->pause(100);
        });

        $this->assertDatabaseHas('answers', [
            'answer' => 'answer2',
            'question' => $question->position
        ]);
        $this->assertDatabaseHas('answers', [
            'answer' => 'answer1',
            'question' => $question->position
        ]);
    }
}
