<script>
    $('#prev-quiz').on('click', function() {
        $.ajax({
            url: "{{route('prevQuiz')}}",
        });
    }); 
    $('#next-quiz').on('click', function() {
        $.ajax({
            url: "{{route('nextQuiz')}}",
        });
    }); 
    $('#show-results').on('click', function() {
        $.ajax({
            url: "{{route('showResults', ['session_key' => $key])}}",
        });
    }); 
    $('#end-quiz').on('click', function() {
        $.ajax({
            url: "{{route('endQuiz')}}",
        });
    }); 
</script>
