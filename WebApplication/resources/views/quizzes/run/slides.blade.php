@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <form method="POST" action="{{route('storeSlides', ['id' => $quiz->id])}}">
                    {{ csrf_field() }}
                    <div class="form-group text-center">
                        <label class="text-center" for="file">Slides in pdf format:</label>
                        <input id="slides-upload" type="file" name="file" accept="application/pdf">
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
