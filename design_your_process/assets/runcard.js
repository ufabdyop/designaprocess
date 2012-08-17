function select_card() {
        console.log('select_card called');
	$('#select_card').find('input').remove();
	$('[name]=value').each(
		function() {
			var elem_id = this.id;
			var id_index = this.id.indexOf('_') + 1;
			var input_id = this.id.substring(id_index);
			if (this.value == "") {
				return;
			}
			$('#select_card').append('<input type="hidden" name="input_parameter[' + input_id + ']" value="' + this.value + '" />');
		}
	);
	$('#select_card').append('<input type="hidden" name="process_id" value="' + $('#process_id').val() + '" />');
	$('#select_card').submit();
}

function add_to_card() {
    var card_id = $(this).attr('rc-val');
    if (card_id) {
        $('#add_to_card').append('<input type="hidden" name="runcard_id" value="' + card_id +'"/>');
    }
    $('#add_to_card').submit();
}

$(document).ready(function() {
	$('#runcard_button').click(select_card);	
	$('#runcard_button').find('span').click(select_card);	
	$('html').append('<form id="select_card" action="runcard/select_card.php" method="post"></form>');
        $('.select_card').click(add_to_card);
});


//
