<?php
include('runcard_common.php');
$runcard_id = $_REQUEST['id'];

$runcard = Runcard::get_by_id($runcard_id);
$process_forms = $runcard->get_process_forms();

foreach($process_forms as $pf) {
    echo "<h2>" . $pf->process->name() . "</h2>\n";
    echo $pf->to_html();
}

?>


<?php

$content = ob_get_clean();
$title = "View Runcard: " . $runcard->name;
load_template('template.html', array('TITLE' => $title, 
                            'CONTENT' => $content, 
                            'INSTRUCTIONS' => '',
                            'HEAD' => ' <script src="' . DSPWEBROOT . 'runcard/runcard_equation_evaluation.js"  language="javascript"></script>',
                            'MESSAGES' => $message ));

?>
