@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <form method="POST" action="{{route('questions.store')}}">
                    <input name="quiz_id" type="hidden" value="{{ app('request')->input('quiz') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name">Question Text:</label>
                        <input type="text" class="form-control" id="question_text" name="question_text" required>
                    </div>

                    <div class="form-group">
                        <label for="type">Type:</label>
                        <select id="type" name="type">
                            @for ($i=0; $i<count($typeKeys); $i++)
                                <option value="{{ $typeKeys[$i] }}">{{ $typeValues[$i] }}</option>
                            @endfor
                        </select>
                    </div>
                        @for ($j=1; $j<=$numberAnswers; $j++)
                            <div class="form-group">
                                <label for="name">Answer {{$j}}:</label>
                                <input type="text" class="form-control" id="answer{{$j}}" name="answer{{$j}}">
                            </div>
                        @endfor
                    
                    <div class='form-group'>
                        <button type="submit" class="btn center-block btn-default">Create</button> 
                    </div>

                    @include('layouts.form-errors')
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    @include('questions.javascript')
@endsection
