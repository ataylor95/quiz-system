@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"> {{ $quiz['name'] }}</div>

                <div class="panel-body">
                    <p>{{ $quiz['desc'] }}</p>
                </div>
                @foreach ($questions as $question)
                    <div class='row'>
                        <div class='col-xs-6'>
                            <p>{{$question->question_text}}</p>
                        </div>
                        <div class='col-xs-2'>
                            <p>{{$question->type}}</p>
                        </div>
                        <div class='col-xs-1'>
                            <a href='question/{{$question->id}}'>View</a>
                        </div>
                        <div class='col-xs-1'>
                            <a href='question/{{$question->id}}'>Edit</a>
                        </div>
                        <div class='col-xs-1'>
                            <p>Delete</p>
                        </div>
                    </div>
                @endforeach
                <div class='panel-footer text-right'>
                    <a href='questions/create' class='btn'>Add Question</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
