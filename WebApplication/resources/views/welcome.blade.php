@extends('layouts.basic')

@section('title')
    <title>Home - Quiz System</title>
@endsection

@section('content')
    <div class="vertical-align">
        <h1 class="text-center">Quiz System</h1>
        <ul id="messages" class="list-group"></ul>
        
        <div class="text-center">
            <div class="form-group">
                <label for="session_key">Session Key:</label>
                <input name="session_key" id="session_key"></input>
            </div>

            <div class="form-group">
                <button id="join-button" type="submit" class="btn center-block btn-primary">Join</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#join-button').on('click', function(){
            var key = $('#session_key').val();
            var baseUrl = "/quiz/";
            var url = baseUrl + key;
            window.location.href = url;
        });
    </script>
@endsection
