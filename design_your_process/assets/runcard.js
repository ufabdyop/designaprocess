$(document).ready(function() {
	$('#runcard_button').attr('href', 'javascript: add_to_runcard()');	
	$('html').append('<form id="add_to_runcard" action="runcard/add_to_card.php" method="post"></form>');
});

function add_to_runcard() {
	$('#add_to_runcard').find('input').remove();
	$('[name]=value').each(
		function() {
			var elem_id = this.id;
			var id_index = this.id.indexOf('_') + 1;
			var input_id = this.id.substring(id_index);
			if (this.value == "") {
				return;
			}
			$('#add_to_runcard').append('<input type="hidden" name="input_parameter[' + input_id + ']" value="' + this.value + '" />');
		}
	);
	$('#add_to_runcard').append('<input type="hidden" name="process_id" value="' + $('#process_id').val() + '" />');
	$('#add_to_runcard').submit();
}

//
