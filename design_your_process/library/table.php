<?php
require_once('helper_functions.php');
        /**
         *
         * @param type $rows This is a result set from calling db_query (in pdo.php)
         * @param type $links This is an array of the form:  
         *             $links = array( 
                        array('title' => 'Edit', 'page' => 'edit_parameter.php', 'extra' => null),
			array('title' => 'Delete', 'page' => 'delete_parameter.php', 'extra' => ' onclick="return confirm(\'Are you sure?\')"')
			);
         * @param type $link_var if set, link_var is set to the name of a column in the result set and is appended to the generated links href as a get parameter
         * @param type $additional_get, this is appended to the get string on links
         * @param type $additional_options, a way of passing more options, if passing editable_fields, this will generate text inputs instead of just plain text
         * @return string 
         */
function rows_to_table($rows, $links = array() , $link_var = '', $additional_get ='', $additional_options = array()) {

        $editable_fields = array();
        if (isset($additional_options['editable_fields'])) {
            $editable_fields = $additional_options['editable_fields'];
        }

        $hidden_fields = array();
        if (isset($additional_options['hidden_fields'])) {
            $hidden_fields = $additional_options['hidden_fields'];
        }


        $content = '<table>' . "\n";
        if ($rows) {
                //print header
                $content .= "\t<thead>\n";
                $content .= "\t<tr>\n";
                if ($links) {
                        foreach($links as $link ) {
                            $content .= "\t\t<th></th>\n";
                        }
                }
                foreach($rows[0] as $fieldname => $value) {
                        //check fieldname is not a number
                        if (preg_match('/[^\d]/', $fieldname)) {
                            if (in_array($fieldname, $hidden_fields)) {

                            } else {
                                $content .= "\t\t<th>$fieldname</th>\n";
                            }
                        }
                }
                $content .= "\t</tr>\n";
                $content .= "\t</thead>\n";
                $content .= "\t<tbody>\n";
                foreach($rows as $row) {
                        $content .= "\t<tr>\n";
                        if ($links) {
                                foreach($links as $link) {
                                    if($row[$link_var]) {
                                        $title = $link['title'];
                                        $page = $link['page'];
                                        $extra = $link['extra'];
                                        $content .= "\t\t<td><a $extra href=\"$page?$link_var=" . $row[$link_var] . $additional_get . "\">$title</a></td>\n";
                                    }
                                }
                        }
                        foreach($row as $fieldname => $value) {
                            if (in_array($fieldname, $hidden_fields)) {
                                continue;
                            }
                                //check fieldname is not a number
                                if (preg_match('/[^\d]/', $fieldname)) {
                                    if (in_array($fieldname, $editable_fields)) {
                                            $id_html = "";
                                            if (isset($row['id'])) {
                                                $id_html = " id =\"" . $fieldname . '_' . $row['id'] . '"';
                                            }
                                        $content .= "\t\t<td><input type=\"text\" name=\"$fieldname" . "[]\" $id_html></input></td>\n";
                                    } else {
                                        $content .= "\t\t<td>$value</td>\n";
                                    }
                                }
                        }
                        $content .= "\t</tr>\n";
                }
                $content .= "\t</tbody>\n";
        }
        $content .= '</table>' . "\n";
        return $content;
}
        
function user_input_table($process_id, $links, $hide_ids = true){
    $sql = "SELECT process_form.parameter_id as id, name, process_form.value, unit 
            FROM process_form LEFT JOIN parameter on process_form.parameter_id = parameter.id
            WHERE process_form.process_id = '$process_id' AND is_input_field = '1' ORDER BY name";
    $fields = db_query($sql);
    
    $options = array();
    if ($hide_ids) {
        $options = array('hidden_fields' => array('id', 'parameter_id'),
                         'editable_fields' => array('value'));
    }
    if ($fields) {
            $content .= "<div class=\"is_input_field\">\n";
            $content .= "<h3>User Inputs</h3>\n";
            $content .= rows_to_table($fields, $links, 'parameter_id','&process_id='.$process_id, $options);
            $content .= "</div>\n";
            return $content;
    }
}
function process_parameter_table($process_id, $links, $hide_ids = true){
    $sql = "SELECT process_form.parameter_id, name, process_form.value, unit
            FROM process_form LEFT JOIN parameter on process_form.parameter_id = parameter.id
            WHERE process_form.process_id = '$process_id' AND is_process_parameter = '1' ORDER BY name";
    $fields = db_query($sql);
    $options = array();
    if ($hide_ids) {
        $options = array('hidden_fields' => array('parameter_id'));
    }
    if ($fields) {
            $content .= "<div class=\"is_process_parameter\">\n";
            $content .= "<h3>Process Parameters</h3>\n";
            $content .= rows_to_table($fields, $links, 'parameter_id','&process_id='.$process_id, $options);
            $content .= "</div>\n";
            return $content;
    }
}
function predicted_result_table ($process_id, $links, $hide_ids = true){
    $sql = "SELECT process_form.parameter_id, name, process_form.value, unit, process_form.confidence, process_form.data_origin
            FROM process_form LEFT JOIN parameter on process_form.parameter_id = parameter.id
            WHERE process_form.process_id = '$process_id' AND is_measured_result = '1' ORDER BY name";
    $fields = db_query($sql);
    $options = array();
    if ($hide_ids) {
        $options = array('hidden_fields' => array('parameter_id'));
    }
    if ($fields) {
            $content .= "<div class=\"is_measured_result\">\n";
            $content .= "<h3>Predicted Results</h3>\n";
            $content .= rows_to_table($fields, $links, 'parameter_id','&process_id='.$process_id, $options);
            $content .= "</div>\n";
            return $content;
    }
}
function equation_table ($process_id, $links){
    $sql = "SELECT id, name, equation, unit FROM equation WHERE process_id = '$process_id'";
    $fields = db_query($sql);
    if($fields){
            $content .= "<div class=\"is_equation\">\n";
            $content .= "<h3>Equations</h3>\n";
            $content .= rows_to_table($fields, $links, 'id','&process_id='.$process_id);
            $content .= "</div>\n";
            return $content;
    }
}
?>
