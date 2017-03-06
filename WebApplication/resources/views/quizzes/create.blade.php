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
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="desc">Description:</label>
                        <textarea type="text" class="form-control" id="desc" name="desc" required></textarea>
                    </div>
                    
                    <p>Questions are added in the next step</p>

                    <div class='form-group'>
                        <button type="submit" class="btn center-block btn-default">Create</button> 
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
