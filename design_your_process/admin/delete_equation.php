<?php
require_once('../library/helper_functions.php');
$process_id = $_REQUEST['process_id'];
$equation_id = $_REQUEST['id'];

delete_equation($process_id, $equation_id);

header("Location: select_parameter.php?process_id=$process_id");
?>
