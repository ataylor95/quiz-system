<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;

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
        $questionData = $this->getQuestionsData();
        $numberAnswers = $questionData[0];
        $typeKeys = $questionData[1];
        $typeValues = $questionData[2];
    
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
        $questionID = Question::saveQuestion($request);

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
    public function edit(Question $question)
    {
        $questionData = $this->getQuestionsData();
        $numberAnswers = $questionData[0];
        $typeKeys = $questionData[1];
        $typeValues = $questionData[2];
        return view('questions.edit', compact('question', 'typeKeys', 'typeValues', 'numberAnswers')); 
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
        Question::deleteQuestion($id);
        return back();
    }

    private function getQuestionsData()
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
        return [$numberAnswers, $typeKeys, $typeValues];
    }
}
