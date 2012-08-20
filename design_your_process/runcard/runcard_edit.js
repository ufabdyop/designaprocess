
function runcard_sorting() {
    $('#runcard_sortable_container').sortable({stop: runcard_sorted});
}
function saving() {
    $('#form_container').append('<div id="saving">Saving</div>');
}
function order_saved(data, textStatus, jqXHR) {
    $('#saving').html('Saved');
    $('#saving').fadeOut('slow', function() {
        $('#saving').remove();    
    });
}
function order_save_failed() {
    alert('Failure to Save');
    $('#saving').remove();
}
function save_order() {
    var the_data = [];
    var i = 0;
    $('input[name=process_id]').each(function() {
       the_data[i] = $(this).val() ;        
       i++;
    });
    
    i = 0;
    $('a.delete-button').each(function() {
        var old_href = $(this).attr('href');
        var new_href = old_href.replace(/order=\d*/, 'order=' + i);
        $(this).attr('href', new_href);
        i++;
    });
    
    $.ajax('runcard_reorder.php', {
        success: order_saved,
        error: order_save_failed,
        data: {process_id: the_data, 
                runcard_id: $('input[name=runcard_id]').val()}
    })
}
function runcard_sorted(event, ui) {
    var i = 1;
    
    $('.runcard_sortable .order').each(function() {
        $(this).html(i);
        i++;    
    });
    
    saving();
    save_order();
}

$(document).ready(function() {
   runcard_sorting(); 
});