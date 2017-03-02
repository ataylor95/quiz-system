<?php

use Illuminate\Database\Seeder;
use Illuminate\Hashing\BcryptHasher;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\User')->create(['email' => 'amt22@aber.ac.uk']);
    }
}
