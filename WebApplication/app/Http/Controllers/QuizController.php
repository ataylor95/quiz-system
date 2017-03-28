<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Quiz;
use App\Events\DisplayQuiz;
use App\Session;
use App\User;

class QuizController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['quiz']]);
        $this->middleware('session-key', ['only' => ['quiz']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = auth()->user()->id;
        $quizzes = Quiz::where('user_id', $user_id)->get();
        return view('quizzes.index', compact('quizzes')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('quizzes.create'); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Some simple validation, currently just require the two fields
        $this->validate($request, [
            'name' => 'required|min:5',
            'desc' => 'required'
        ]);

        $id = Quiz::saveQuiz($request['name'], $request['desc'], 
                auth()->user()->id);

        return redirect()->route('quizzes.show', ['id' => $id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Quiz $quiz)
    {
        $questions = $quiz->questions;
        return view('quizzes.show', compact('quiz', 'questions')); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Quiz $quiz)
    {
        return view('quizzes.edit', compact('quiz')); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'min:5',
        ]);
        Quiz::updateQuiz($request['name'], $request['desc'], $id);

        return redirect()->route('quizzes.show', ['id' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Quiz::deleteQuiz($id);
        return back();
    }

    /**
     * Action that is called via quizzes/run
     * This triggers the broadcast event for WebSockets
     *
     * @param String $name of quiz
     */
    public function run(Quiz $quiz)
    {
        //TODO: Sanitize what gets sent through the WebSockets
        $user = auth()->user()->id;
        $sessionKey = User::find($user)->session->session_key;

        Session::setQuizRunning($quiz->id, $user);

        event(new DisplayQuiz($quiz, $user));
        return redirect()->route('quizSession', ['session_key' => $sessionKey]);
    }

    /**
     * Action for the quizzes that are running
     * Passes the quiz, session key and question to the view
     * 
     * @param  String  $key - key of the session
     * @return \Illuminate\Http\Response
     */
    public function quiz($key)
    {
        $session = Session::where('session_key', $key)->get()[0];
        $quizID = $session->quiz_id;
        if (is_null($quizID)) {
            $quiz = null;
            $question = null;
        } else {
            $quiz = Quiz::find($quizID)->get()[0];
            $position = $session->position;
            $question = $quiz->questions[$position];
        }
        return view('quizzes.run', compact('key', 'quiz', 'question'));
    }

    /**
     * Decrements the question counter in the session row
     */
    public function prevQuestion()
    {
        $user = auth()->user()->id;
        $position = Session::prevNextQuestion($user, false);
        $question = Session::getQuestionForQuiz($user, $position);

        event(new DisplayQuiz($question, $user));
    }

    /**
     * Incerements the question counter in the session row
     * TODO: Could these be done by API routing?
     */
    public function nextQuestion()
    {
        //Send the next question to the event
        $user = auth()->user()->id;
        $position = Session::prevNextQuestion($user, true);
        $question = Session::getQuestionForQuiz($user, $position);

        event(new DisplayQuiz($question, $user));
    }

    /**
     * Ends the quiz within the session row, also resets the position
     */
    public function endQuiz()
    {
        $user = auth()->user()->id;
        Session::endQuiz($user);
        event(new DisplayQuiz(['end' => true], $user));
    }
}
