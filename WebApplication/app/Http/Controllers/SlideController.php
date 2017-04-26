<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Spatie\PdfToImage;
use App\Slide;
use App\Quiz;
use App\User;

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

    /**
     * Returns the run slides page where slides can be uploaded
     *
     * @param Quiz $quiz
     */
    public function runSlides(Quiz $quiz)
    {
        return view('quizzes.run.slides', compact('quiz'));
    }

	/**
	 * Action to remove the slides
	 *
	 * @param int $quiz - id of the quiz
	 */
	public function removeSlides($quiz)
	{
        $user = auth()->user()->id;
        $sessionKey = User::find($user)->session->session_key;

        $this->removeOldSlides($sessionKey, $quiz);
		return back();
	}

    /**
     * Saves the pdf slides to the storage folder
     * Then converts each slide to an image and saves that
     * Saves information about the images to the db
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function storeSlides(Request $request)
    {
        $this->validate($request, [
            'slides' => 'mimetypes:application/pdf|file',
        ]);


        $user = auth()->user()->id;
        $sessionKey = User::find($user)->session->session_key;

        $this->removeOldSlides($sessionKey, $request->quiz);
        
        //Save the slides in the storage folder under a sessionkey subfolder
        //Also get the name of the file for later
        $name = $request->file('slides')->store('public/slides/'. $sessionKey .'/quiz-' . $request->quiz);

        //Get the pdf from above
        $pdf = new PdfToImage\Pdf(storage_path() . '/app/' . $name); 
        $num = $pdf->getNumberOfPages();  
    
        $address = (storage_path() . '/app/public/slides/' . $sessionKey .'/quiz-' . $request->quiz . '/');
        //Convert each page in the pdf to a jpg and save them
        for($i=1;$i<=$num;$i++){ 
            $pdf->setPage($i)->saveImage($address . 'slide-' . $i . '.jpg');
        }
		//For some reason the first image is converted to the wrong size, but if we run
		//it again its fine
		$pdf->setPage(1)->saveImage($address . 'slide-' . 1 . '.jpg');
        
        //We need to save some information about the slides to the db to use them later
        Slide::saveSlides($num, $request->quiz);

        //Set the quiz running, easiest way is just to call that function
        //This is really dirty but its the quickest way to do this
        app('App\Http\Controllers\QuizController')->run(Quiz::find($request->quiz));

        //For some reason its rediect does not work when called from another function
        //So we do one here anyway
        return redirect()->route('quizzes.show', ['quiz' => $request->quiz]);
    }

    /**
     * Removes the slides if the quiz already had some
     *
     * @param String $sessionKey
     * @param int $quizID
     */
    private function removeOldSlides($sessionKey, $quizID)
    {
        $address = (storage_path() . '/app/public/slides/' . $sessionKey .'/quiz-' . $quizID . '/');
        File::deleteDirectory($address, true);

        Slide::deleteSlides($quizID);
    }

    /**
     * Renders the slide image in a simple img tag
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function getSlide(Request $request)
    {
		$sessionKey = Quiz::find($request->quiz_id)
			->user
			->session
			->session_key;

        $fileName = $request->file_name . '.jpg';
        $location = '/storage/slides/' . $sessionKey . '/quiz-' . $request->quiz_id . '/' . $fileName;
        return view('slides.slide', compact('location'));
    }
}
