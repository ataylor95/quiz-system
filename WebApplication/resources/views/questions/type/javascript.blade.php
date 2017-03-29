<script>
    function selectOption(button) {
        $('.btn-success').toggleClass('btn-success btn-default');
        $('#' + button.id).toggleClass('btn-default btn-success');
    }

    function submitMCQuestion(){
        console.log($('.btn-success').attr('name'));
    }
</script>
