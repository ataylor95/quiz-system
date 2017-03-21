@extends('layouts.basic')

@section('title')
    <title>Quiz</title>
@endsection

@section('content')
    <h1>Quiz: {{$key}}</h1>
    <ul id="messages" class="list-group"></ul>
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
      var channelName = 'quiz_' + '{{$key}}';
      var channel = pusher.subscribe(channelName);

      //Bind a function to a Event (the full Laravel class)
      channel.bind('App\\Events\\DisplayQuiz', addMessage);

      function addMessage(data) {
        var listItem = $("<li class='list-group-item'></li>");
        listItem.html(data.quiz.name);
        $('#messages').prepend(listItem);
      }
    </script>
@endsection
