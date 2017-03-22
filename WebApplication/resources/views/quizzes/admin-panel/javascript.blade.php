<script>
    $('#prev-quiz').on('click', function() {
        $.ajax({
            url: '/quiz/prev',
        });
    }); 
    $('#next-quiz').on('click', function() {
        $.ajax({
            url: '/quiz/next'
        });
    }); 
    $('#end-quiz').on('click', function() {
        $.ajax({
            url: '/quiz/end'
        });
    }); 
</script>
