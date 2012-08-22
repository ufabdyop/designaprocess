<?php
require_once('../library/helper_functions.php');
require_once('redirect_non_admins.php');

$quick_search = "<script src=\"" . DSPWEBROOT . "/assets/quick_search.js\" type=\"text/javascript\"></script>\n";

ob_clean();
ob_start();

?>

<ul>
    <li><a href="new_process.php">Add a New Process</a></li>
    <li><a href="edit_process.php">Edit/Delete Process or Parameters</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>

<?php
$content = ob_get_clean();
load_template('template.html', array('TITLE' => 'Admin', 
                                    'HEAD' => $quick_search,
                                    'CONTENT' => $content, 
                                    'INSTRUCTIONS' => 'Please select an administrative tool:' ));

?>
<div id="footer">
</div>









