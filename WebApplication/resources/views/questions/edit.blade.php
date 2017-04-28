@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <form method="POST" action="{{route('questions.update', ['id' => $question['id']])}}">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <input name="quiz_id" type="hidden" value="{{ app('request')->input('quiz') }}">
                    <div class="form-group">
                        <label for="name">Question Text:</label>
                        <input type="text" class="form-control" id="question_text" name="question_text" value="{{$question['question_text']}}" required>
                    </div>

                    <div class="form-group">
                        <label for="type">Type:</label>
                        <select id="type" name="type">
                            @for ($i=0; $i<count($typeKeys); $i++)
                                <option value="{{ $typeKeys[$i] }}">{{ $typeValues[$i] }}</option>
                            @endfor
                        </select>
                    </div>
                        @for ($j=1; $j<=$numberAnswers; $j++)
                            <div class="form-group">
                                <label for="name">Answer {{$j}}:</label>
                                <input type="text" class="form-control" id="answer{{$j}}" name="answer{{$j}}" value="{{$question['answer' . $j]}}">
                            </div>
                        @endfor
                    
                    <div class='form-group' style="padding-left: 5px; padding-right: 5px;">
                        <a href="{{route('quizzes.show', ['quiz' => $question->quiz->id])}}" class="btn btn-danger">Cancel</a> 
                        <button type="submit" class="btn btn-success" style="float: right;">Update</button> 
                    </div>

                    @include('layouts.form-errors')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
    
@section('scripts')
    @include('questions.javascript')
    <script>
        //By fefault we want the select box to show the correct type
        $('#type').val("{{$question['type']}}");

        //For this edit page, we want the boolean options presented as they
        //would be on a create page
        if($('#type').val() == 'boolean'){
            booleanQuestionSelected();
        }
    </script>
@endsection
