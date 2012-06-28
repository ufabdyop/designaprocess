<?php
    require_once('library/pdo.php');
    require_once('library/table.php');
    require_once('library/helper_functions.php');
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
    $links = array();
    $title = "$process_name\n";
    $content = user_input_table($process_id, $links, true);
    $content .= '<div id="equations_output"></div>';
    $content .= process_parameter_table($process_id, $links, true);
    $content .= predicted_result_table($process_id, $links, true);
    $content .= process_notes_table($process_id, false);
    $content .= '<script src="assets/run_processes.js"></script>';
    $content .= '<input type="hidden" id="process_id" value="' . $process_id . '"></input>';
    
    $admin_button = '
            <a id="admin_button" class="button" href="admin/select_parameter.php?process_id=' . $process_id . '">
                <span class="ui-icon ui-icon-wrench ui-icon-shadow">&nbsp;</span>
                <span class="button_description">Admin</span>
            </a>
    ';

    
    load_template('template.html', array('TITLE' => $title, 'CONTENT' => $content, 'DASHBOARD' => $admin_button));
?>

