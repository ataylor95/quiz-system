@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Your Quizzes</div>

                <div class="panel-body">
                    @foreach ($quizzes as $quiz)
                        <div class='row'>
                            <div class='col-xs-8'>
                                <p>{{$quiz->name}}</p>
                            </div>
                            <div class='col-xs-1'>
                                <a href='/quizzes/{{$quiz->id}}'>View</a>
                            </div>
                            <div class="col-xs-1">
                                <button type="submit" class="btn btn-primary">Run</button>
                            </div>
                            <div class='col-xs-2'>
                                <form method="POST" action="/quizzes/{{$quiz->id}}">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <div class='form-group'>
                                        <button type="submit" class="btn btn-default btn-danger">Delete</button> 
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class='panel-footer text-right'>
                    <a href='/quizzes/create' class='btn'>New Quiz</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
