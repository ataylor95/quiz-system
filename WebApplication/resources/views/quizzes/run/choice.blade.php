@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    {{$quiz->name}}
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class='text-center'>
                            <a href="{{route('runQuiz', ['id' => $quiz->id])}}" class="btn btn-primary">Run Without Slides</a>
                        </div>
                    </div>
                    </p>                    
                    <div class="row">
                        <div class='text-center'>
                            <a href="{{route('runSlides', ['id' => $quiz->id])}}" class="btn btn-primary">Run With Slides</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
