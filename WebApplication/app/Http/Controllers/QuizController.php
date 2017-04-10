<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Spatie\PdfToImage;
use App\Quiz;
use App\Events\DisplayQuiz;
use App\Session;
use App\User;
use App\Question;
use App\Answer;

class QuizController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['quiz', 'results']]);
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
     * Gives the user a choice of whether to run quiz with or
     * without slides
     *
     * @param Quiz $quiz
     */
    public function runChoice(Quiz $quiz)
    {
        return view('quizzes.run.choice', compact('quiz')); 
    }

    /**
     * Action that is called via quizzes/run
     * This triggers the broadcast event for WebSockets
     *
     * @param Quiz $quiz
     */
    public function run(Quiz $quiz)
    {
        //TODO: Sanitize what gets sent through the WebSockets
        $user = auth()->user()->id;
        $sessionKey = User::find($user)->session->session_key;

        //We should make sure that the previous data is deleted
        //This could happen if they dont press End Quiz
        //NEVER trust users
        Answer::deleteResultsAtQuizEnd($sessionKey);
        Session::setQuizRunning($quiz->id, $user);

        event(new DisplayQuiz("start", null, $user));
        return redirect()->route('quizSession', ['session_key' => $sessionKey]);
    }

    /**
     * Returns the run slides page where slides can be uploaded
     *
     * @param Quiz $quiz
     */
    public function runSlides(Quiz $quiz)
    {
        return view('quizzes.run.slides', compact('quiz'));
    }

    public function storeSlides(Request $request)
    {
        $user = auth()->user()->id;
        $sessionKey = User::find($user)->session->session_key;
        
        //Save the slides in the storage folder under a sessionkey subfolder
        //Also get the name of the file for later
        $name = $request->file('slides')->store('slides/' . $sessionKey);

        //Get the pdf from above
        $pdf = new PdfToImage\Pdf(storage_path() . '/app/' . $name); 
        $num = $pdf->getNumberOfPages();  
    
        $address = (storage_path() . '/app/slides/' . $sessionKey . '/');
        //Convert each page in the pdf to a png
        for($i=1;$i<=$num;$i++){ 
             $pdf->setPage($i)->saveImage($address . 'slide-' . $i . '.png');
        }

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
            $position = null;
        } else {
            $quiz = Quiz::find($quizID);
            $position = $session->position;
            if ($position == 0) {
                $question = null;
            } else {
                $question = Question::where([
                    ['quiz_id', '=', $quizID], 
                    ['position', '=', $position]
                ])->get()[0];
            }
        }

        return view('quizzes.run.quiz', compact('key', 'quiz', 'question', 'position'));
    }

    /**
     * Decrements the question counter in the session row
     */
    public function prevQuestion()
    {
        $user = auth()->user()->id;
        $position = Session::prevNextQuestion($user, false);

        if ($position == 0) {
            $quizID = Session::where('user_id', $user)->get(['quiz_id'])[0];
            $quiz = Quiz::find($quizID);
            event(new DisplayQuiz("start", $quiz, $user));
        } else {
            $question = Session::getQuestionForQuiz($user, $position);
            event(new DisplayQuiz("question", $question, $user));
        }
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

        event(new DisplayQuiz("question", $question, $user));
    }

    /**
     * Ends the quiz within the session row, also resets the position
     */
    public function endQuiz()
    {
        $user = auth()->user()->id;
        $key = Session::where("user_id", $user)->get()[0]->session_key;
        Answer::deleteResultsAtQuizEnd($key);
        Session::endQuiz($user);
        event(new DisplayQuiz("end", null, $user));
    }

    /**
     * This function saves the results of a quiz question
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  String  $session - key of the session
     */
    public function results(Request $request, $session)
    {
        //We should use the session name stored in the cookie to use an identifier for users
        //We need this to stop users submitting again and again

        Answer::saveResult($session, Cookie::get('laravel_session'), $request->response);
    }

    /**
     * Function to show the results to the admin user
     * 
     * @param  String  $sessionKey - key of the session
     */
    public function showResults($sessionKey)
    {
        $answers = Answer::getResults($sessionKey);
        //We want to return this as json so it can be used in a graph
        return response()->json($answers);
    }
}
