<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Session;
use App\Answer;

class SessionController extends Controller
{
    /**
     * Function to download the results from a quiz session
     * TODO: refactor this out a little
     *
     * @param String $sessionKey
     * @return downloads a csv
     */
    public function downloadResults($sessionKey)
    {
        $session = Session::where('session_key', $sessionKey)->get()[0];
        $quizName = $session->quiz->name;
        $quizName = str_replace(' ', '_', $quizName);
        $quizName = str_replace('.', '', $quizName);

        //The array to use on the creation of spreadsheets
        $answersArray = []; 

        $questions = $session->quiz->questions;

        //loop over each question and add the relevant data to the csv
        foreach ($questions as $question) {
            //Add the title of each question
            $answersArray[] = [$question->question_text];
            
            //Get all the answers from the session
            $answers = Answer::where('question', $question->position)
                ->where('session_id', $session->id)->get();

            //Get the answers and how many times they were answered
            //Loop over all of them and add them to an array, if 
            //the item already exists, increment
            $answerValues = [];
            foreach($answers as $answer) {
                if(array_key_exists($answer->answer, $answerValues)) {
                    $answerValues[$answer->answer]++;
                } else {
                    $answerValues[$answer->answer] = 1;
                }
            }
            
            //Now to add two arrays to the csv
            //The first is the keys, the answers themselves that people click
            //The second is the number of times each was answered
            //This is so each array goes on a line in the csv
            $keys = [];
            $values = [];
            foreach($answerValues as $key=>$value) {
                //So if its a multi selection question, need to change answer1, answer2
                //to the various answers, split the string and loop over replacing with
                //the actual names
                if (strpos($key, ',')) {
                    $multiSelectKeys = explode(', ', $key);
                    $answerNames = '';
                    foreach($multiSelectKeys as $multiKey) {
                        $answerNames .= $question[$multiKey] . ', ';
                    }
                    $answerNames = rtrim($answerNames, ', ');
                    $keys[] = $answerNames;
                    $values[] = $value;
                } else {
                    $keys[] = $question[$key];
                    $values[] = $value;
                }
            }
            $answersArray[] = $keys;
            $answersArray[] = $values;
        }

        Excel::create($quizName . "-results", function($excel) use ($answersArray, $quizName) {
            // Set the spreadsheet title, creator, and description
            $excel->setTitle($quizName);

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function($sheet) use ($answersArray) {
                $sheet->fromArray($answersArray, null, 'A1', false, false);
            });
        })->download('csv');

        return back();
    }
}
