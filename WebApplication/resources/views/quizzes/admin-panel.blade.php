@if (Auth::check()) 
    {{-- TODO: right now any auth user can see this, only want the owner--}}
    <div class="admin-panel">
        <div class='row'>
            <div class="col-xs-1">
                <button class="btn btn-primary">Prev</button>
            </div>
            <div class="col-xs-1">
                <button class="btn btn-primary">Next</button>
            </div>
            <div class="col-xs-2">
                <button class="btn btn-primary">End Quiz</button>
            </div>
        </div>
    </div>
@endif
