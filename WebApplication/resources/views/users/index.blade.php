@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Users</div>

                <div class="panel-body">
                    @foreach ($users as $user)
                        <div class='row'>
                            <div class='col-md-4'>
                                <p>{{$user->name}}</p>
                            </div>
                            <div class='col-md-5'>
                                <p>{{$user->email}}</p>
                            </div>
                            <div class='col-md-1'>
                                <a href="{{route('users.show', ['id' => $user->id])}}">View</a>
                            </div>
                            <div class='col-md-1'>
                                <p>Edit</p>
                            </div>
                            <div class='col-md-1'>
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
