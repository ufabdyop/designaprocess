<?php
require_once ('../library/helper_functions.php');
require_once('redirect_non_admins.php');
$message="";
$equation_id = $_REQUEST['id'];
$process_id = $_REQUEST['process_id'];
$script = "<script language=\"javascript\">
    var available_variables = new Array();
    var process = all_processes.get_process_by_id('$process_id');
    var inputs = process.parameters;
    for (var i in inputs) {
        available_variables.push('id' + inputs[i]['id'] + '_' + i);
    }
</script>
";
$process_name = process_name($process_id);

$sql = "SELECT name, equation, unit FROM equation WHERE id = '$equation_id'";
$results = db_query($sql);
$curname = $results[0]['name'];
$curunit = $results[0]['unit'];
$curequation = $results[0]['equation'];

$calculator_html = load_template('../assets/calculator_template.html' , array('POST_TO' => '', 'PROCESS_ID' => $process_id, 'NAME' => $curname, 'UNIT' => $curunit, 'EQUATION' => $curequation, 'IS_DISABLED' => ''), false);
$title = "Edit Equation";
$message = "Edit the Equation for Process: $process_name";

if(isset($_POST['submit'])){
    $equation = $_POST['calculator_output'];
    $new_name = $_POST['equation_name'];
    $new_unit = $_POST['output_unit'];
    $sql = "UPDATE equation SET name = '$new_name', equation = '$equation', unit = '$new_unit' WHERE id = '$equation_id'";
    db_query($sql);   
    //insert edit record to history table in database
    $date = date('Y-m-d, h:i:s');
    $sql = "INSERT INTO history (process_id, date, type, old_name, new_name, old_unit, new_unit, old_value, new_value,
            is_input_field, is_process_parameter, is_measured_result, is_equation) VALUES 
            ('$process_id', '$date', 'edit', '$curname', '$new_name', '$curunit', '$new_unit', '$curequation', '$equation', '0', '0', '0', '1')";
    db_query($sql);
    
    header("Location: select_parameter.php?process_id=$process_id");
}
load_template('../assets/template.html', array('TITLE' => $title, 'CONTENT' => $calculator_html . "\n" . $script, 'MESSAGES' => $message));
?>



