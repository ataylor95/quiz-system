<?php

use Illuminate\Database\Seeder;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Quiz', 3)->create(['user_id' => 1]);
        for ($i=1;$i<=3;$i++) {
            factory('App\Question')->create([
                'quiz_id' => $i, 
            ]);
            factory('App\Question')->create([
                'quiz_id' => $i, 
                'position' => 2
            ]);
        }
        factory('App\Question', 10)->create();
        factory('App\Session')->create([
            'user_id' => 1, 
            'session_key' => 'testy'
        ]);
    }
}
