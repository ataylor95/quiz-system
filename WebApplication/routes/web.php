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
Route::get('quizzes/run/{quiz}', 'QuizController@run');
Route::resource('quizzes', 'QuizController');

Route::get('quiz/{session_key}', 'QuizController@quiz');
