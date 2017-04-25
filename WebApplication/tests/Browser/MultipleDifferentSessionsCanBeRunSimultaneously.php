<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\Session;
use App\Quiz;
use App\Question;

class MultipleDifferentSessionsCanBeRunSimultaneously extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test if two lecturers can both run session independent of each other
     */
    public function testTwoAdminsRunTwoSessions()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        factory(Session::class)->create(['user_id' => $user1->id]);
        factory(Session::class)->create(['user_id' => $user2->id]);
        $quiz1 = factory(Quiz::class)->create(['user_id' => $user1->id]);
        $quiz2 = factory(Quiz::class)->create(['user_id' => $user2->id]);

        $this->browse(function ($first, $second) use ($user1, $user2, $quiz1, $quiz2) {
            $first->loginAs($user1->id)
                ->visit('/quizzes')
                ->press('#quiz-' . $quiz1->id);

            $second->loginAs($user2->id)
                ->visit('/quizzes')
                ->press('#quiz-' . $quiz2->id); 

            $first->assertSee($quiz1->name)
                ->assertPathIs('/quiz/' . $user1->session->session_key)
                ->assertDontSee($quiz2->name);

            $second->assertSee($quiz2->name)
                ->assertPathIs('/quiz/' . $user2->session->session_key)
                ->assertDontSee($quiz1->name);
        });
    }

    /**
     * Test two users joining different sessions
     */
    public function testTwoUsersJoinDifferentSessions()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $quiz1 = factory(Quiz::class)->create(['user_id' => $user1->id]);
        $quiz2 = factory(Quiz::class)->create(['user_id' => $user2->id]);
        factory(Session::class)->create([
            'user_id' => $user1->id,
            'quiz_id' => $quiz1->id,
            'running' => true,
        ]);
        factory(Session::class)->create([
            'user_id' => $user2->id,
            'quiz_id' => $quiz2->id,
            'running' => true,
        ]);
        
        $this->browse(function ($first, $second) use ($user1, $user2, $quiz1, $quiz2) {
            $first->visit('/quiz/' . $user1->session->session_key)
                ->assertSee($quiz1->name)
                ->assertDontSee($quiz2->name);

            $second->visit('/quiz/' . $user2->session->session_key)
                ->assertSee($quiz2->name)
                ->assertDontSee($quiz1->name);
        });
    }
}
