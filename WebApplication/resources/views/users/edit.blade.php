@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <form method="POST" action="{{route('users.update', ['id' => $user->id])}}">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{$user['name']}}" required>
                    </div>

                    <div class="form-group">
                        <label for="desc">Email:</label>
                        <input type="text" class="form-control" id="email" name="email" value="{{$user['email']}}" required>
                    </div>

                    <div class="form-group">
                        <label for="name">Session Key:</label>
                        <input type="text" class="form-control" id="session_key" name="session_key" value="{{$sessionKey}}" required>
                    </div>

                    <div class='form-group'>
                        <button type="submit" class="btn center-block btn-default">Update</button> 
                    </div>

                    @include('layouts.form-errors')
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
