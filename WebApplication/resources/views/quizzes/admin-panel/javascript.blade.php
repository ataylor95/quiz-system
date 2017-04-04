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
            success: function(data){
                console.log(data);
            },
        });
    }); 
    $('#end-quiz').on('click', function() {
        $.ajax({
            url: "{{route('endQuiz')}}",
        });
    }); 
</script>
