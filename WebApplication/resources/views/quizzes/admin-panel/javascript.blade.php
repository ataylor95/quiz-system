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
    $('#end-quiz').on('click', function() {
        $.ajax({
            url: "{{route('endQuiz')}}",
        });
    }); 
</script>
