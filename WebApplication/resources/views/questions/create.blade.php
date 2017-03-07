@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <form method="POST" action="/questions">
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
                                <input type="text" class="form-control" id="answer{{$j}}" name="answer{{$j}}" required>
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
