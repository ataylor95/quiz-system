@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"> 
                    {{ $quiz['name'] }}
                    <a class="heading-right" href="{{route('quizzes.edit', ['id' => $quiz['id']])}}">Edit</a>
                </div>

                <div class="panel-body">
                    <p>{{ $quiz['desc'] }}</p>
                </div>
                @foreach ($combined as $item)
                    @if ($item->file_name)
                        <div class="row">
                            <div class='col-xs-3 text-center'>
                                <p>{{$item->file_name}}</p>
                            </div>
                            <div class='col-xs-2'></div>
                            <div class='col-xs-1'>
                                <p>{{$item->position}}</p>
                            </div>
                            <div class='col-xs-2'></div>
                            <div class='col-xs-3'>
                                <a href="{{$imageBase . $item->file_name}}.png">View</a>
                            </div>
                        </div>
                    @elseif ($item->question_text)
                        <div class='row'>
                            <div class='col-xs-3'>
                                <p>{{$item->question_text}}</p>
                            </div>
                            <div class='col-xs-2'>
                                <p>{{config('questions')['types'][$item->type]}}</p>
                            </div>
                            <div class='col-xs-1'>
                                <p>{{$item->position}}</p>
                            </div>
                            <div class='col-xs-1'>
                                <form method="post" action="{{route('changePosition', ['id' => $item->id, 'direction' => 'up'])}}">
                                    {{ csrf_field() }}
                                    <div class='form-group'>
                                        <button type="submit" class="glyphicon glyphicon-chevron-down"></button> 
                                    </div>
                                </form>
                            </div>
                            <div class='col-xs-1'>
                                <form method="post" action="{{route('changePosition', ['id' => $item->id, 'direction' => 'down'])}}">
                                    {{ csrf_field() }}
                                    <div class='form-group'>
                                        <button type="submit" class="glyphicon glyphicon-chevron-up"></button> 
                                    </div>
                                </form>
                            </div>
                            <div class='col-xs-1'>
                                <a href="{{route('questions.show', ['id' => $item['id']])}}">View</a>
                            </div>
                            <div class='col-xs-1'>
                                <a href="{{route('questions.edit', ['id' => $item['id'], 'quiz' => $quiz['id']])}}">Edit</a>
                            </div>
                            <div class='col-xs-1'>
                                <form method="POST" action="{{route('questions.destroy', ['id' => $item['id']])}}">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <div class='form-group'>
                                        <button type="submit" class="btn btn-default btn-danger">Delete</button> 
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                    @endif
                @endforeach
                <div class="panel-footer text-right">
                    <a href="{{route('questions.create', ['quiz' => $quiz['id']])}}" class="btn">Add Question</a>
                </div>
                <div class="panel-footer text-right">
                    <a href="{{route('runSlides', ['quiz' => $quiz->id])}}" class="btn">Add Slides</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
