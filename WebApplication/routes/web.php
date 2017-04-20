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

//The landing page with the form for entering a session
Route::get('/', function () {
    return view('welcome');
});

//Generate the default authentication stuff, login register etc
Auth::routes();

//Backend home for lecturers
Route::get('/home', 'QuizController@index');

//Resources routes for users, questions and quizzes
Route::resource('users', 'UserController');
Route::resource('questions', 'QuestionController');
//These routes are for running a quiz, including uploading slides
Route::get('quizzes/run-choice/{quiz}', 'QuizController@runChoice')->name('runChoice');
Route::get('quizzes/run-slides/{quiz}', 'SlideController@runSlides')->name('runSlides');
Route::post('quizzes/run-slides/{quiz}', 'SlideController@storeSlides')->name('storeSlides');
Route::get('quizzes/run/{quiz}', 'QuizController@run')->name('runQuiz');
Route::resource('quizzes', 'QuizController');

Route::group(['prefix' => 'quiz'], function () {
    //Base used in some javascript stuff for constructing links
    Route::get('/')->name('quizBase');
    //Quiz control routes
    Route::get('prev', 'QuizController@prevQuestion')->name('prevQuiz');
    Route::get('next', 'QuizController@nextQuestion')->name('nextQuiz');
    Route::get('end', 'QuizController@endQuiz')->name('endQuiz');
    //Results routes for getting and saving results
    Route::post('results/{session_key}', 'QuizController@results')->name('results');
    Route::get('results/{session_key}', 'QuizController@showResults')->name('showResults');
    //Goes to the quiz session for a lecturer
    Route::get('{session_key}', 'QuizController@quiz')->name('quizSession');
});

//Generates the question for rendering on the quiz
Route::get('questions/type/{type}', 'QuestionController@getQuestion')->name('questionType');
//For moving the question position in the backend
Route::post('question/{id}/{direction}', 'QuestionController@changePosition')->name('changePosition');
//Gets the slide for the quiz
Route::get('slide', 'SlideController@getSlide')->name('slide');
