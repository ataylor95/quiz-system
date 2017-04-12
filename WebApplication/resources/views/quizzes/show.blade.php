@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"> 
                    {{ $quiz['name'] }}
                    <a class="heading-right" href="{{route('quizzes.edit', ['id' => $quiz['id']])}}">Edit</a>
                </div>

                <div class="panel-body">
                    <p>{{ $quiz['desc'] }}</p>
                </div>
                @foreach ($questions as $question)
                    <div class='row'>
                        <div class='col-xs-3'>
                            <p>{{$question->question_text}}</p>
                        </div>
                        <div class='col-xs-2'>
                            <p>{{config('questions')['types'][$question->type]}}</p>
                        </div>
                        <div class='col-xs-1'>
                            <p>{{$question->position}}</p>
                        </div>
                        <div class='col-xs-1'>
                            <form method="post" action="{{route('changePosition', ['id' => $question->id, 'direction' => 'up'])}}">
                                {{ csrf_field() }}
                                <div class='form-group'>
                                    <button type="submit" class="glyphicon glyphicon-chevron-down"></button> 
                                </div>
                            </form>
                        </div>
                        <div class='col-xs-1'>
                            <form method="post" action="{{route('changePosition', ['id' => $question->id, 'direction' => 'down'])}}">
                                {{ csrf_field() }}
                                <div class='form-group'>
                                    <button type="submit" class="glyphicon glyphicon-chevron-up"></button> 
                                </div>
                            </form>
                        </div>
                        <div class='col-xs-1'>
                            <a href="{{route('questions.show', ['id' => $question['id']])}}">View</a>
                        </div>
                        <div class='col-xs-1'>
                            <a href="{{route('questions.edit', ['id' => $question['id'], 'quiz' => $quiz['id']])}}">Edit</a>
                        </div>
                        <div class='col-xs-1'>
                            <form method="POST" action="{{route('questions.destroy', ['id' => $question['id']])}}">
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
                    <a href="" class="btn">Add Slides</a>
                </div>
                <div class="panel-footer text-right">
                    <a href="{{route('questions.create', ['quiz' => $quiz['id']])}}" class="btn">Add Question</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
