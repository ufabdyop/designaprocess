function quick_search_selection_made(event, ui) {
    console.log('selection made');
    var processes = all_processes.get_processes_by_search_term(ui.item.value.replace(new RegExp('\\|', 'g'), '\\|'));
    var process = processes[0];
    
    $('#process').val(process.process);
    $('#category').val(process.category);
    $('#tool').val(process.tool);
    $('#material').val(process.material);
    
    narrow_processes(capture_process_id);
    $('form').submit();

}
function quick_search() {
    var filter = $('#quick_search').val();
    var result_set = all_processes.get_processes_by_search_term(filter);
    var result_set_strings = Array();
    $(result_set).each(function() {
        result_set_strings.push(this.long_name);
    });
    
    $('#quick_search').autocomplete({ 
        source: result_set_strings,
        select: quick_search_selection_made
    });
}

$(document).ready(function() {
   $('#quick_search').keyup(quick_search) ;
   $('#quick_search').click(function() {this.select();});
});