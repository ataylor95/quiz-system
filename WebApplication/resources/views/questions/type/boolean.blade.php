<div id="question-form" class="text-center">
    <h1>{{$question->question_text}}</h1>
        <input type="hidden" name="question-number" id="question-number" value="{{$position}}"</input>
        @for ($i=1; $i<=2; $i++)
            @php $numAnswer = "answer" . $i; @endphp
            @if (strlen($question->$numAnswer) > 0)
            <div class="form-group">
                <button class="btn btn-default" id="{{'answer' . $i}}" name="{{'answer' . $i}}" onclick="selectOption(this)">
                    {{$question->$numAnswer}}
                </button>
            </div>
            @endif
        @endfor

        <div class='form-group'>
            <button type="submit" class="btn center-block btn-info" onclick='submitMCQuestion()'>Submit</button> 
        </div>

</div>
