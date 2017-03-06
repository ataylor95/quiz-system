@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <form method="POST" action="/quizzes">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name">Name of Quiz:</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>

                    <div class="form-group">
                        <label for="desc">Description:</label>
                        <textarea type="text" class="form-control" id="desc" name="desc"></textarea>
                    </div>
                    
                    <p>Questions are added in the next step</p>

                    <button type="submit" class="btn center-block btn-default">Create</button> 
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
