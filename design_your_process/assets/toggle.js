    function disable_confidence_and_origin() {
        $('#data_origin').attr('disabled', 'true');
        $('#confidence').attr('disabled', 'true');
        $('#confidence').css('background-color', '#ccc');
    }
    function enable_confidence_and_origin() {
        $('#data_origin').removeAttr('disabled');
        $('#confidence').removeAttr('disabled');
        $('#confidence').css('background-color', '#fff');
    }
    function toggle_fields() {
	if ($('#measured_result_radio').attr('checked') ) {
		enable_confidence_and_origin();
	} else {
		disable_confidence_and_origin();
	}
    }
    $(document).ready(function() {
        $('#process_parameter_radio').click(toggle_fields) ;
        $('#input_radio').click(toggle_fields) ;
        $('#measured_result_radio').click(toggle_fields) ;
	toggle_fields();
    });

