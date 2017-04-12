@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Your Quizzes
                    <a class="heading-right" href="">Run Slides</a>
                </div>

                <div class="panel-body">
                    @foreach ($quizzes as $quiz)
                        <div class='row'>
                            <div class='col-xs-8'>
                                <p>{{$quiz->name}}</p>
                            </div>
                            <div class='col-xs-1'>
                                <a href="{{route('quizzes.show', ['id' => $quiz['id']])}}">View</a>
                            </div>
                            <div class="col-xs-1">
                                <a href="{{route('runChoice', ['id' => $quiz['id']])}}" class="btn btn-primary">Run</a>
                            </div>
                            <div class='col-xs-2'>
                                <form method="POST" action="{{route('quizzes.destroy', ['id' => $quiz['id']])}}">
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
                    <a href="{{route('quizzes.create')}}" class='btn'>New Quiz</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
