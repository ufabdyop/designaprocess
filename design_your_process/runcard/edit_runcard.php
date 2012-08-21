<?php
include('runcard_common.php');
$runcard_id = $_REQUEST['id'];

$runcard = Runcard::get_by_id($runcard_id);
$process_forms = $runcard->get_process_forms();
echo form_hidden('runcard_id', $runcard_id);
echo "<table><tr><th>Remove</th><th>Process Order</th><th>Process Name</th></tr>";
echo "<tbody id=\"runcard_sortable_container\">";
$i = 0;
foreach($process_forms as $pf) {
    
    echo "<tr class=\"runcard_sortable\">
        <td><a class=\"delete-button\" onclick=\"return confirm('Are you sure?')\" title=\"Delete Step From Runcard\" href=\"delete_runcard_step.php?runcard_id=$runcard_id&process_id=$pf->id&order=" . $i++ . "\">Delete</a></td>
        <td class=\"order\">$i</td>
            <td>" . $pf->process->name() . "<input type=\"hidden\" name=\"process_id\" value=\"" . $pf->process->id . "\"</td></tr>\n";
}
echo "</tbody>";
echo "</table>";

?>


<?php

$content = ob_get_clean();
$title = "Edit Runcard: " . $runcard->name;
load_template('template.html', array('TITLE' => $title, 
                            'CONTENT' => $content, 
                            'INSTRUCTIONS' => 'Drag rows to re-order.',
                            'HEAD' => ' <script src="' . DSPWEBROOT . 'runcard/runcard_edit.js"  language="javascript"></script>',
                            ));

?>
