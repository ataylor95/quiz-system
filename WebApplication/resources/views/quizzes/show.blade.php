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
                <div class='panel-footer text-right'>
                    <a href='quistions/create' class='btn'>Add Question</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
