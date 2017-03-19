<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Quiz;
use App\Events\DisplayQuiz;

class QuizController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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

    public function run($name)
    {
        event(new DisplayQuiz($name));
        return back();
    }
}
