<script>
    $( "#type" ).change(function() {
  		var type = $( "#type").val();
		if (type == 'boolean'){
			booleanQuestionSelected();
		} else {
			nonBooleanQuestionSelected();
		}
	});

	/**
	 * When a boolean option selected, hide options 3 -> 10
	 * Also set the two options still visible to True and False
	 */
	function booleanQuestionSelected(){
		for(var i = 3; i<=10; i++){
			var answer = "#answer" + i;
			$(answer).closest('div').hide();
		}
		$("#answer1").prop("readonly", true);
		$("#answer1").val("True");
		$("#answer2").prop("readonly", true);
		$("#answer2").val("False");
	}

	/**
	 * Reverse booleanQuestionSelected() above 
	 */
	function nonBooleanQuestionSelected(){
		for(var i = 3; i<=10; i++){
			var answer = "#answer" + i;
			$(answer).closest('div').show();
		}

		if($("#answer1").val() == "True" || $("answer1").val() == "False"){
			$("#answer1").prop("readonly", false);
			$("#answer1").val("");
			$("#answer2").prop("readonly", false);
			$("#answer2").val("");
		}
	}
</script>
