<?php

use Illuminate\Database\Seeder;
use App\Quiz;

class QuizzesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $quiz1 = new Quiz;
        $quiz2 = new Quiz;
        $quiz3 = new Quiz;
        $quizzes = [$quiz1, $quiz2, $quiz3];

        $quiz1->user_id = 1;
        $quiz2->user_id = 1;
        $quiz3->user_id = 2;

        foreach ($quizzes as $quiz) {
            $quiz->name = str_random(5);
            $quiz->save();
        }
    }
}
