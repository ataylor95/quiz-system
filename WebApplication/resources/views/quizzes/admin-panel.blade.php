@if (Auth::check()) 
    {{-- TODO: right now any auth user can see this, only want the owner--}}
    <div class="admin-panel">
        <div class='row'>
            <div class="col-xs-1">
                <button id="prev-quiz" name="prev-quiz" class="btn btn-primary">Prev</button>
            </div>
            <div class="col-xs-1">
                <button id="next-quiz" name="next-quiz" class="btn btn-primary">Next</button>
            </div>
            <div class="col-xs-2">
                <button id="end-quiz" name="end-quiz" class="btn btn-danger">End Quiz</button>
            </div>
        </div>
    </div>
@endif