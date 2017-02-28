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
		$user = new User;
		$user->name = str_random(10);
		$user->email = 'amt22@aber.ac.uk';
		$user->password = (new BcryptHasher)->make('secret');
		$user->save();

		$user2 = new User;
		$user2->name = str_random(10);
		$user2->email = 'amt23@aber.ac.uk';
		$user2->password = (new BcryptHasher)->make('secret');
		$user2->save();
    }
}