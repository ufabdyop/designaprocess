<?php
require_once ('../library/helper_functions.php');
require_once('redirect_non_admins.php');
$message="";
$process_id = $_REQUEST['process_id'];
$name = $_POST['equation_name'];
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


$calculator_html = load_template('../assets/calculator_template.html' , array('POST_TO' => '', 'PROCESS_ID' => $process_id, 'IS_DISABLED' => ' disabled'), false);
$title = "Enter Equation";
$message = "Add an Equation for Process: $process_name";

if(isset($_POST['submit'])){
    $equation = $_POST['calculator_output'];
    $unit = $_POST['output_unit'];
    add_equation($process_id ,$name , $unit, $equation);  
    header("Location: select_parameter.php?process_id=$process_id");
}
load_template('../assets/template.html', array('TITLE' => $title, 'CONTENT' => $calculator_html, 'MESSAGES' => $message  . "\n" . $script));
?>
