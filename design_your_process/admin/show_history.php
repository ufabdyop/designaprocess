<?php
    require_once('../library/pdo.php');
    require_once('../library/table.php');
    require_once('../library/helper_functions.php');
    require_once('redirect_non_admins.php');
    $process_id = $_REQUEST['process_id'];
    if (!$process_id) {
        $process_id = $_POST['process_id'];
    }
    if (!$process_id) {
            echo "Please specify a process\n";
            die ;
    }
    $process_index = db_query("select * from processes where id = '$process_id'");
    if ($process_index) {
            $process_name = $process_index[0][1] . ', ' .$process_index[0][2] . ', ' .$process_index[0][3] . ', '.$process_index[0][4] ;
    }
    $title = "History for Process: $process_name\n";
    $input_types['User Inputs'] = "is_input_field";
    $input_types['Process Parameters'] = "is_process_parameter";
    $input_types['Measured Results'] = "is_measured_result";
    $input_types['Equations'] = "is_equation";
    foreach($input_types as $name => $input_type) {
        $sql = "SELECT date, type, old_name, new_name, old_unit, new_unit, old_value, new_value 
                FROM history 
                WHERE process_id = '$process_id' AND $input_type = '1'
                ORDER BY date";
        $fields = db_query($sql);
        if($fields){
            $content .= "<div class=\"$input_type\">\n";
            $content .= "<h3>$name</h3>\n";
            $content .= rows_to_table($fields);
            $content .= "</div>\n";
        }
    }
            /*$content .= "<a class=\"button\" href=\"select_parameter.php?process_id=$process_id\">
                        <span class=\"ui-icon ui-icon-script ui-icon-shadow\">&nbsp;</span>
                        <span class=\"button_description\">Process Info</span>
                     </a>\n";
             * 
             */
    load_template('template.html', array('TITLE' => $title, 'CONTENT' => $content));
?>
<a href="../admin">Return to Admin Page</a>