<?php
include('runcard_common.php');
$runcard_id = $_REQUEST['id'];

$runcard = Runcard::get_by_id($runcard_id);
$process_forms = $runcard->get_process_forms();

echo "<table>
    <tr>
        <th>STEP</th>
        <th>CATEGORY</th>
        <TH>PROCESS</TH>
        <TH>TOOL</TH>
        <TH>MATERIAL</TH>
        <TH>PARAMETERS</TH>
        <TH>ACTUAL PARAMETERS</TH>
        <TH>VERIFICATION</TH>
    </TR>
        ";
$i = 0;
foreach($process_forms as $pf) {
    $i++;
    echo "<tr>\n";
    echo "<td>$i</td>\n";
    echo "<td>{$pf->process->category}</td>\n";
    echo "<td>{$pf->process->process}</td>\n";
    echo "<td>{$pf->process->tool}</td>\n";
    echo "<td>{$pf->process->material}</td>\n";
    echo "<td class=\"nested_table_container\">{$pf->to_printer_html()}</td>\n";
    echo "<td class=\"nested_table_container\">{$pf->to_printer_html(true)}</td>\n";
    echo "<td><span>&nbsp;</span></td>\n";
    echo "</tr>\n";
}
echo "</table>\n";
?>


<?php
$content = ob_get_clean();
$title = "View Runcard: " . $runcard->name;
load_template('printer_template.html', array('TITLE' => $title, 
                            'CONTENT' => $content, 
                            'INSTRUCTIONS' => '',
                            'HEAD' => ' <script src="' . DSPWEBROOT . 'runcard/runcard_equation_evaluation.js"  language="javascript"></script>',
                            'MESSAGES' => $message ));
?>
