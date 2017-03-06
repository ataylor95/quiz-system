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
                            <div class='col-md-8'>
                                <p>{{$quiz->name}}</p>
                            </div>
                            <div class='col-md-2'>
                                <p>View/ Edit</p>
                            </div>
                            <div class='col-md-2'>
                                <p>Delete</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
