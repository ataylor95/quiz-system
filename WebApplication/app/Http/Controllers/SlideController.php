<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SlideController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['getSlide']]);
    }

    public function getSlide(Request $request)
    {
        $fileName = $request->file_name . '.png';
        $location = '/storage/slides/quiz-' . $request->quiz_id . '/' . $fileName;
        return view('slides.slide', compact('location'));
    }
}
