<?php
require_once('../library/helper_functions.php');
require_once('redirect_non_admins.php');


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
load_template('template.html', array('TITLE' => 'Admin', 'CONTENT' => $content, 'INSTRUCTIONS' => 'Please select an administrative tool:' ));

?>
<div id="footer">
</div>









