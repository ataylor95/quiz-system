@extends('layouts.basic')

@section('title')
    <title>Quiz - {{$key}}</title>
@endsection

@section('content')
    <div id="default-content">
        @if (is_null($quiz))
            <div class="vertical-align">
                <h2 class="text-center">No quiz running for: {{$key}}</h2>
                @if (Auth::check())
                    <div class="text-center">
                        <a href="{{route('quizzes.index')}}">Start a session?</a>
                    </div>
                @endif
            </div>
        @elseif (is_null($question))
            <div class="vertical-align">
                <h1 class="text-center">{{$quiz['name']}}</h1>
                <h3 class="text-center">{{$quiz['desc']}}</h3>
                <h4 class="text-center">Session: {{$key}}</h4>
            </div>
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
        var sessionKey = '{{$key}}';
        var channelName = 'quiz_' + sessionKey;
        var channel = pusher.subscribe(channelName);

        //Bind a function to a Event (the full Laravel class)
        channel.bind('App\\Events\\DisplayQuiz', changeContent);

        /**
         * Function that is called when an event is fired by WebSockets
         * In here we want to change the content of the page based on
         * the type of response data we get
         *
         * @param JSON data from WebSockets
         */
        function changeContent(response){
            $('#default-content').empty(); //Remove previous stuff
            switch(response.type){
                case "start":
                    var startContent = '<div class="vertical-align">';
                    startContent += '<h1 class="text-center">' + response.data.name + '</h1>';
                    startContent += '<h3 class="text-center">' + response.data.desc + '</h3>';
                    startContent += '<h4 class="text-center">Session: ' + sessionKey + '</h4>';
                    startContent += '</div>';
                    $('#default-content').append(startContent);
                    break;
                case "question":
                    changeQuestion(response);
                    break;
                case "slide":
                    renderSlide(response.data);
                    break;
                case "end":
                    var endContent = '<div class="vertical-align"><h2 class="text-center">End of the Quiz</h2></div>';
                    $('#default-content').append(endContent);
                    break;
            }
        }

        /**
         * Switch on the type of question to call the appropriate question to render
         *
         * @param JSON response from websockets
         */
        function changeQuestion(response){
            switch (response.data.type) {
                case "multi_choice":
                    renderQuestion(response.data, "{{route('questionType', ['type' => 'multi_choice'])}}");
                    break;
                case "multi_select":
                    renderQuestion(response.data, "{{route('questionType', ['type' => 'multi_select'])}}");
                    break;
                case "boolean":
                    renderQuestion(response.data, "{{route('questionType', ['type' => 'boolean'])}}");
                    break;
                case "number_range":
                    renderQuestion(response.data, "{{route('questionType', ['type' => 'number_range'])}}");
                    break;
                case "text":
                    renderQuestion(response.data, "{{route('questionType', ['type' => 'text'])}}");
                    break;
            }
        }

        /**
         * Performs ajax request to a slide page then copies that
         * content onto this page
         *
         * @param JSON response from websockets
         */
        function renderSlide(response) {
            console.log(response);
            $.ajax({
                url: "{{route('slide')}}",
                data: {
                    'file_name': response.file_name,
                    'quiz_id': response.quiz_id, 
                },
                success: function(data){
                    $('#default-content').append($(data)[0]);
                },
            });
        }

        /**
         * Performs ajax request on question type page and then copies the
         * content of that page onto the page
         *
         * @param JSON question - the question and its data
         * @param String url - url of the question type for the ajax call
         */
        function renderQuestion(question, url){
            $.ajax({
                url: url,
                data: {
                    'quiz_id': question.quiz_id, 
                    'position': question.position
                },
                success: function(data){
                    $('#default-content').append($(data)[0]);
                },
            });
        }
    </script>

    @include('quizzes.admin-panel.javascript')
    @include('questions.type.javascript')
@endsection
