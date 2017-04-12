@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <form method="POST" action="{{route('storeSlides', ['id' => $quiz->id])}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group text-center">
                        <label class="text-center" for="slides">Slides in pdf format:</label>
                        <input id="slides-upload" type="file" name="slides" accept="application/pdf">
                    </div>

                    <div class='form-group'>
                        <button type="submit" class="btn center-block btn-default">Upload</button> 
                    </div>

                    @include('layouts.form-errors')
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
