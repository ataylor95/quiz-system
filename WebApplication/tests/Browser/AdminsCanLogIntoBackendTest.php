<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;

class AdminsCanLogIntoBackendTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Tests that a user can log in
     *
     * @return void
     */
    public function testLogin()
    {
        $user = factory(User::class)->create([
            'email' => 'test@example.com',
        ]);

        $this->browse(function ($browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'secret')
                    ->press('Login')
                    ->assertPathIs('/home');
        });
    }

    /**
     * Tests that a user can register
     *
     * @return void
     */
    public function testRegister()
    {
        $this->browse(function ($browser) {
            $browser->visit('/register')
                    ->type('name', 'test@example.com')
                    ->type('email', 'test@example.com')
                    ->type('password', 'secret')
                    ->type('password_confirmation', 'secret')
                    ->press('Register')
                    ->assertPathIs('/home');
        });
    }

    public function testQuizzesRedirect()
    {
        $this->browse(function ($browser) {
            $browser->visit('/quizzes')
                    ->assertPathIs('/login'); 
        });
    }
}
