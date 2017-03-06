<?php

use Illuminate\Database\Seeder;

class QuizzesQuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This created three quizzes for the first user
     * It then also adds an extra question to each of these
     * Then it creates a further 10 users, quizzes and questions
     * @return void
     */
    public function run()
    {
        factory('App\Quiz', 3)->create(['user_id' => 1]);
        factory('App\Question', 3)->create();
        for ($i=1;$i<=3;$i++) {
            factory('App\QuizQuestion')->create([
                'quiz_id' => $i, 
                'question_id' => $i
            ]);
            factory('App\QuizQuestion')->create(['quiz_id' => $i]);
        }
        factory('App\QuizQuestion', 10)->create();
    }
}
