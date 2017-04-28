@if (Auth::check()) 
    {{-- TODO: right now any auth user can see this, only want the owner--}}
    <div class="admin-panel">
        <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12" id="results-box">
                <canvas id="myChart"></canvas>
            </div>
        </div>
        <div class='row'>
            <div class="col-xs-2">
                @if ($position == 0)
                    <button id="quiz-prev" name="quiz-prev" class="btn btn-info" disabled>Prev</button>
                @else
                    <button id="quiz-prev" name="quiz-prev" class="btn btn-info">Prev</button>
                @endif
            </div>
            <div class="col-xs-2">
                @if ($position == $totalNumItems)
                    <button id="quiz-next" name="quiz-next" class="btn btn-info" disabled>Next</button>
                @else
                    <button id="quiz-next" name="quiz-next" class="btn btn-info">Next</button>
                @endif
            </div>
            <div class="col-xs-2">
                <button id="show-results" name="show-results" class="btn btn-primary">Results</button>
            </div>
            <div class="col-xs-3">
                <a href="{{route('downloadResults', ['session_key' => $quiz->user->session->session_key])}}" class="btn btn-primary">Download Quiz Results</a>
            </div>
            <div class="col-xs-2">
                <button id="end-quiz" name="end-quiz" class="btn btn-danger">End Quiz</button>
            </div>
        </div>
    </div>
@endif
