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
        factory('App\Quiz', 3)->create(['user_id' => 1]);
        factory('App\Quiz', 17)->create();
    }
}
