<?php
include('runcard_common.php');

$title = "Add Process to Runcard";

//display existing runcards
$run_cards = Runcard::get_by_username($user->username);
if ($run_cards) {
    echo "<div class=\"existing_runcards\">
            <h3>Your Runcards</h3>\n";
    $table_rows = array();
    foreach($run_cards as $card) {
        $table_rows[] = array( 'Add to an Existing Runcard' => styled_button( '#', $card->name, "plus", array('select_card'), array('rc-val' => $card->id) ));
    }
    echo rows_to_table($table_rows);
    echo "</div>\n";
}


$hidden_fields = array('process_id' => $_REQUEST['process_id']);
foreach($_POST['input_parameter'] as $id => $value) {
    $hidden_fields['input_parameter[' . $id . ']'] = $value;
}

echo form_open("add_to_card.php",array('method' => 'post', 'id' => 'add_to_card'), $hidden_fields);

echo "<div class=\"new_runcard\">\n<h3>Create a New Runcard</h3>\n";
echo "<table>\n";
echo "<tr><td>" . form_label('Name') . "</td><td>" . form_input('name') . "</td></tr>\n";
echo "<tr><td>" . form_label('Access') . "</td><td>" . form_radio(array('name' => 'access', 'id' => 'public'), 'public') . form_label('Public', 'public') . form_radio(array('name' => 'access', 'id' =>'private'), 'private') . form_label('Private', 'private')  . "</td></tr>\n";
echo "</table>\n</div><br/>\n";
//display form for new runcards
echo styled_button( '#', "Add to New Card", "plus", array('select_card'));

echo form_close();
echo '<script src="../assets/runcard.js"></script>';


$content = ob_get_clean();
load_template('template.html', array('TITLE' => $title, 'CONTENT' => $content, 'INSTRUCTIONS' => '','MESSAGES' => $message ));

?>
