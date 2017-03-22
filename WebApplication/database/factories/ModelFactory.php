<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Quiz::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->sentence,
        'desc' => $faker->text,
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Question::class, function (Faker\Generator $faker) {
    return [
        'quiz_id' => function () {
            return factory(App\Quiz::class)->create()->id;
        },
        'question_text' => $faker->sentence,
        'type' => $faker->randomElement(['multi_choice', 'multi_select']),
        'answer1' => $faker->word,
        'answer2' => $faker->word,
        'answer3' => $faker->word,
        'answer4' => $faker->word,
    ];
});

$factory->define(App\Session::class, function (Faker\Generator $faker) {
    return [
        'session_key' => str_random(8),
        'quiz_id' => null,
        'position' => 0,
        'running' => false,
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
    ];
});
