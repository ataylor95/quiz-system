@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"> 
                    {{ $user->name }}
                </div>
                <p></p>
                <div class='row'>
                    <div class='col-xs-2'>
                        <p>Email: </p>
                    </div>
                    <div class='col-xs-10'>
                        <p>{{$user->email}}</p>
                    </div>
                </div>
                <div class='row'>
                    <div class='col-xs-2'>
                        <p>Session Key: </p>
                    </div>
                    <div class='col-xs-10'>
                        <p>{{$sessionKey}}</p>
                    </div>
                </div>
                <div class="panel-footer text-right">
                    <a href="{{route('users.edit', ['id' => $user->id])}}" class="btn">Edit User Details</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
