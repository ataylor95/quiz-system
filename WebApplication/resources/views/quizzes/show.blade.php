@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"> 
                    {{ $quiz['name'] }}
                    <a class="heading-right" href="/quizzes/{{ $quiz['id'] }}/edit">Edit</a>
                </div>

                <div class="panel-body">
                    <p>{{ $quiz['desc'] }}</p>
                </div>
                @foreach ($questions as $question)
                    <div class='row'>
                        <div class='col-xs-5'>
                            <p>{{$question->question_text}}</p>
                        </div>
                        <div class='col-xs-3'>
                            <p>{{config('questions')['types'][$question->type]}}</p>
                        </div>
                        <div class='col-xs-1'>
                            <a href='/questions/{{$question->id}}'>View</a>
                        </div>
                        <div class='col-xs-1'>
                            <a href="/questions/{{$question->id}}/edit?quiz={{$quiz['id']}}">Edit</a>
                        </div>
                        <div class='col-xs-1'>
                            <form method="POST" action="/questions/{{$question->id}}">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <div class='form-group'>
                                    <button type="submit" class="btn btn-default btn-danger">Delete</button> 
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
                <div class="panel-footer text-right">
                    <a href="/questions/create?quiz={{ $quiz['id'] }}" class="btn">Add Question</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
