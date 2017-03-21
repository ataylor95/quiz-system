@extends('layouts.basic')

@section('title')
    <title>Home - Quiz System</title>
@endsection

@section('content')
    <div class="vertical-align">
        <h1 class="text-center">Quiz System</h1>
        <ul id="messages" class="list-group"></ul>
        
        <div class="text-center">
            <form action="">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="session_key">Session Key:</label>
                    <input name="session_key" id="session_key"></input>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn center-block btn-primary">Join</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://js.pusher.com/3.1/pusher.min.js"></script>
    <script>
      //instantiate a Pusher object with our Credential's key
      var pusher = new Pusher('3f21a6176f0f0d31c04c', {
          cluster: 'eu',
          encrypted: true
      });

      //Subscribe to the channel we specified in our Laravel Event
      var channel = pusher.subscribe('quiz_channel');

      //Bind a function to a Event (the full Laravel class)
      channel.bind('App\\Events\\DisplayQuiz', addMessage);

      function addMessage(data) {
        var listItem = $("<li class='list-group-item'></li>");
        listItem.html(data.quiz.name);
        $('#messages').prepend(listItem);
      }
    </script>
@endsection
