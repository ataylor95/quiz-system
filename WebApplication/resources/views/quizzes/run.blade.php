@extends('layouts.basic')

@section('title')
    <title>Quiz - {{$key}}</title>
@endsection

@section('content')
    <div id="default-content" class="vertical-align">
        @if (is_null($quiz))
            <h2 class="text-center">No quiz running for: {{$key}}</h2>
			@if (Auth::check())
				<div class="text-center">
					<a href="{{route('quizzes.index')}}">Start a session?</a>
				</div>
			@endif
        @elseif (is_null($question))
            <h1 class="text-center">{{$quiz['name']}}</h1>
            <h3 class="text-center">{{$quiz['desc']}}</h3>
            <h4 class="text-center">Session: {{$key}}</h4>
        @else
            @foreach (getQuestionsData()[1] as $type) {{-- use the helper function --}}
                @if ($question->type == $type)
                    @include('questions.type.' . $type)
                @endif
            @endforeach
        @endif
    </div>
    
    @include('quizzes.admin-panel.panel')
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
        channel.bind('App\\Events\\DisplayQuiz', changeContent);

        function addMessage(data) {
            var listItem = $("<li class='list-group-item'></li>");
            listItem.html(data.data.question_text);
            $('#messages').prepend(listItem);
        }

        function changeContent(response){
            $('#default-content').empty(); //Remove previous stuff
            switch(response.type){
                case "start":
                    break;
                case "question":
                    changeQuestion(response);
                    break;
                case "end":
                    var endContent = '<h2 class="text-center">End of the Quiz</h2>';
                    $('#default-content').append(endContent);
                    break;
            }
        }

        function changeQuestion(response){
            if(response.data.type == "multi_choice"){
                $.ajax({
                    url: "{{route('questionType', ['type' => 'multi_choice'])}}",
                    data: {
                        'quiz_id': response.data.quiz_id, 
                        'position': response.data.position
                    },
                    success: function(data){
                        $('#default-content').append($(data)[0]);
                    },
                });
            }
        }
    </script>

    @include('quizzes.admin-panel.javascript')
    @include('questions.type.javascript')
@endsection
