
function runcard_evaluations() {
    all_equations = $('.equation');
    all_equations.each(function() {
        var my_equation = $(this).attr('rc-equation-name');
        var my_process = $(this).attr('rc-process-id');
        var my_process_obj = all_processes.get_process_by_id(my_process);
        var my_inputs = $(this).find('input');
        var my_inputs_hash = {};
        my_inputs.each(function() { my_inputs_hash[$(this).attr('name')] = $(this).attr('value') ; } );
        var result = run_calculation(my_process_obj, my_equation, my_inputs_hash );
        $(this).parent().next().html(result.toPrecision(3));
    });
}

$(document).ready(function() {
   runcard_evaluations(); 
});