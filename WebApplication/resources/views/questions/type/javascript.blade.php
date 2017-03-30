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

    function selectMultiOption(button){
        if ($('#' + button.id).attr('btn-success')) {
            $('#' + button.id).toggleClass('btn-success btn-default');
        } else {
            $('#' + button.id).toggleClass('btn-default btn-success');
        }
    }

    function submitMSQuestion(){
        var answers = $('.btn-success');
        //Use a javascript function, map,  to extract the names of all the 
        //answers into an array
        var result = answers.map(function(answer) {
            return $(answers[answer]).attr('name');
        });
        
        //When you call map on a jquery collection, it retuns another jquery collection
        //This means the resultant array contains jquery collection object like prevObject
        //
        //This does not get accepted in the ajax request
        //It has to be changed to an array to be used in ajax
        result = result.toArray();

        $.post({
            url: "{{route('results')}}",
            data: {
                "response": result,
                "_token": "{{csrf_token()}}"
            }
        });
    }
</script>
