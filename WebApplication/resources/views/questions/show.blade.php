@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"> {{ $question['question_text'] }}</div>

                <div class="panel-body">
                    <p>Type: {{ config('questions')['types'][$question['type']] }}</p>
                    <p>Position in Quiz: {{ $question->position }}</p>
                </div>
                <p>Answers:</p>
                @for ($i = 1; $i <= config('questions')['numAnswers']; $i++)      
                    @if (strlen($question['answer' . $i]) > 0)
                        <div class="row">
                            <div class="col-xs-12">
                                <p>{{ $question['answer' . $i] }}</p> 
                            </div>
                        </div>
                    @endif
                @endfor
                
            </div>
        </div>
    </div>
</div>
@endsection
