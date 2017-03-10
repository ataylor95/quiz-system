<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\QuizQuestion;

class QuestionController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $questionsData = config('questions');
        $numberAnswers = $questionsData['numAnswers'];
        $types = $questionsData['types'];
        $typeKeys = [];
        $typeValues = [];
        foreach ($types as $key => $value) {
            $typeKeys[] = $key;
            $typeValues[] = $value;
        }
    
        return view('questions.create', compact('typeKeys', 'typeValues', 'numberAnswers')); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Some simple validation
        //Currently just require one answer, this may change
        $this->validate($request, [
            'question_text' => 'required',
            'type' => 'required',
            'answer1' => 'required'
        ]);

        //Create the new Question 
        $questionId = Question::Create([
            'question_text' => $request['question_text'],
            'type' => $request['type'],
            'answer1' => (is_null($request['answer1']) ? "" : $request['answer1']),
            'answer2' => (is_null($request['answer2']) ? "" : $request['answer2']),
            'answer3' => (is_null($request['answer3']) ? "" : $request['answer3']),
            'answer4' => (is_null($request['answer4']) ? "" : $request['answer4']),
            'answer5' => (is_null($request['answer5']) ? "" : $request['answer5']),
            'answer6' => (is_null($request['answer6']) ? "" : $request['answer6']),
            'answer7' => (is_null($request['answer7']) ? "" : $request['answer7']),
            'answer8' => (is_null($request['answer8']) ? "" : $request['answer8']),
            'answer9' => (is_null($request['answer9']) ? "" : $request['answer9']),
            'answer10' => (is_null($request['answer10']) ? "" : $request['answer10']),
        ])->id;

        QuizQuestion::Create([
            'quiz_id' => $request['quiz_id'],
            'question_id' => $questionId,
        ]);

        return redirect('/quizzes/' . $request['quiz_id']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        return view('questions.show', compact('question')); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
