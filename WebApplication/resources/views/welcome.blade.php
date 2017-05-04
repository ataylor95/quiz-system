@extends('layouts.basic')

@section('title')
    <title>Home - Quiz System</title>
@endsection

@section('content')
    <div class="vertical-align welcome-page">
        <h1 class="text-center">Quiz System</h1>
        
        <div class="text-center">
            <div class="form-group session-input">
                <label for="session_key">Session Key:</label>
                <input name="session_key" id="session_key"></input>
                <p>Please note that it is case sensitive</p>
            </div>

            <div class="form-group">
                <button id="join-button" type="submit" class="btn center-block btn-primary">Join</button>
            </div>
            <ul id="session-form-errors" class="list-group">
                @if (app('request')->input('no_session') == "true")
                    <li class="list-group-item-danger">No session found with that id, please try again</li>
                @endif
            </ul>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#join-button').on('click', function(){
            goToQuizSession();
        });

        $('#session_key').keypress(function(e) {
            //Because there is no form, pressing enter does not submit
            //This simulates that for quality of life
            //Key 13 should always be enter key
            if(e.which == 13) {
                goToQuizSession();
            }
        });

        function goToQuizSession(){
            var key = $('#session_key').val();
            var baseUrl = "/quiz";
            var url = baseUrl + '/' + key;

            if (validateSessionKey(key)) {
                window.location.href = url;
            } else {
                $('#session-form-errors').empty();
                var error = $("<li class='list-group-item-danger'>Please enter a session key</li>");
                $('#session-form-errors').append(error);
            }
        }

        function validateSessionKey(key) {
            return key.length > 0;
        }
    </script>
@endsection
