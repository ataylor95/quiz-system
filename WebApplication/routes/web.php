<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'QuizController@index');

Route::resource('users', 'UserController');
Route::resource('questions', 'QuestionController');
Route::get('quizzes/run/{quiz}', 'QuizController@run')->name('runQuiz');
Route::resource('quizzes', 'QuizController');

Route::group(['prefix' => 'quiz'], function () {
    Route::get('/')->name('quizBase');
    Route::get('prev', 'QuizController@prevQuestion');
    Route::get('next', 'QuizController@nextQuestion');
    Route::get('end', 'QuizController@endQuiz');
    Route::get('{session_key}', 'QuizController@quiz')->name('quizSession');
});
