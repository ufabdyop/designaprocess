/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function evaluate(event) {
    var process_id = $('#process_id').val();
    var process = all_processes.get_process_by_id(process_id);
    var params = {};
    //grab inputs
    $('input').each(function(inp) {
        var param_id;
        if ($(this).attr('id').match(/value/)) {
            param_id = $(this).attr('id').substring(6);
            params[param_id] = $(this).val();
        }
    });
    
    //calculate equations
    var equations_output = "";
    for (var eq in process.equations) {
        var equation = process.equations[eq];
        var result ;
        try {
            result = run_calculation(process, equation.name, params);
            result = result.toPrecision(3);
        } catch (error) {
            result = error;
        }
        var equation_human_name = equation_to_human_name(equation.equation);
        var equation_with_replacements = evaluate_equation(process, equation.name, params);
        equations_output += '<p>' + equation.name + ' = (' + equation_human_name  + ') = (' + equation_with_replacements + ') = ' + result + '</p>';
    }
    $('#equations_output').html(equations_output);
}
    
    jQuery(document).ready(
        function() {
            $('input').bind('keyup', evaluate);
            $('input').bind('paste', evaluate);
            $('input').bind('input', evaluate);
        });


