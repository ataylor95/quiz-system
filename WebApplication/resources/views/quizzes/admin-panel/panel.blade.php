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
                <button id="prev-quiz" name="prev-quiz" class="btn btn-primary">Prev</button>
            </div>
            <div class="col-xs-2">
                <button id="next-quiz" name="next-quiz" class="btn btn-primary">Next</button>
            </div>
            <div class="col-xs-2">
                <button id="show-results" name="show-results" class="btn btn-success">Results</button>
            </div>
            <div class="col-xs-2">
                <button id="end-quiz" name="end-quiz" class="btn btn-danger">End Quiz</button>
            </div>
        </div>
    </div>
@endif
