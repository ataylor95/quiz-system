<script>
    function selectOption(button) {
        $('.btn-success').toggleClass('btn-success btn-default');
        $('#' + button.id).toggleClass('btn-default btn-success');
    }

    function submitMCQuestion(){
        var answer = $('.btn-success').attr('name');
        $.post({
            url: "{{route('results')}}",
            data: {
                "response": answer,
                "_token": "{{csrf_token()}}"
            }
        });
    }
</script>
