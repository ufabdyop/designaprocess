<?php
    require_once('../library/pdo.php');
    require_once('../library/table.php');
    require_once('../library/helper_functions.php');
    require_once('redirect_non_admins.php');

    /**
        * returns html table for displaying an array of parameters to an admin
        */
    function display_parameters($parameters) {

    }

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
            $process_name = $process_index[0][1] . ' | ' .$process_index[0][2] . ' | ' .$process_index[0][3] . ' | '.$process_index[0][4] ;
    }
    $links = array( 
                    array('title' => 'Edit', 'page' => 'edit_parameter.php', 'extra' => ' title="Edit Parameter" class="edit-button"'),
                    array('title' => 'Delete', 'page' => 'delete_parameter.php', 'extra' => ' class="delete-button" onclick="return confirm(\'Are you sure?\')" title="Delete Parameter"')
                    );
    $title = "$process_name \n";
    $content .= user_input_table($process_id, $links, false);
    $content .= process_parameter_table($process_id, $links, false);
    $content .= predicted_result_table($process_id, $links, false);
    $links = array( 
                    array('title' => 'Edit', 'page' => 'edit_equation.php', 'extra' => ' title="Edit Equation" class="edit-button"'),
                    array('title' => 'Delete', 'page' => 'delete_equation.php', 'extra' => ' title="Delete Equation" class="delete-button" onclick="return confirm(\'Are you sure?\')"')
                    );
    $sql = "SELECT id, name, equation, unit FROM equation WHERE process_id = '$process_id'";
    $fields = db_query($sql);
    if($fields){
    $content .= "<div class=\"is_equation\">\n";
    $content .= "<h3>Equations</h3>\n";
    $content .= rows_to_table($fields, $links, 'id','&process_id='.$process_id);
    $content .= "</div>\n";
    }
    
    //add new parameter button
    $content .= "<a class=\"button\" href=\"new_parameter.php?process_id=$process_id\">
                    <span class=\"ui-icon ui-icon-plus ui-icon-shadow\">&nbsp;</span>
                    <span class=\"button_description\">Add new parameter</span>
                </a>\n";
    
    //add new equation
    $content .= "<a class=\"button\" href=\"new_equation.php?process_id=$process_id\">
                    <span class=\"ui-icon ui-icon-plus ui-icon-shadow\">&nbsp;</span>
                    <span class=\"button_description\">Add new equation</span>
                </a>\n";
    
    //delete process
    $content .= "<a class=\"button\" href=\"delete_process.php?process_id=$process_id\" " . 
                    "onclick=\"return confirm('Are you sure you want to delete this process?');\">
                    <span class=\"ui-icon ui-icon-trash ui-icon-shadow\">&nbsp;</span>
                    <span class=\"button_description\">Delete Process</span>
                </a>\n";
                    
    //process history
    $content .= "<a class=\"button\" href=\"show_history.php?process_id=$process_id\">
                    <span class=\"ui-icon ui-icon-clock ui-icon-shadow\">&nbsp;</span>
                    <span class=\"button_description\">View history</span>
                </a>\n";

    //user view
    $content .= "<a class=\"button\" href=\"../show_parameters.php?process_id=$process_id\">
                    <span class=\"ui-icon ui-icon-person ui-icon-shadow\">&nbsp;</span>
                    <span class=\"button_description\">User View</span>
                </a>\n";
    
    load_template('../assets/template.html', array('TITLE' => $title, 'CONTENT' => $content));

    ?>
 <a href="../admin">Return to Admin Page</a>
 