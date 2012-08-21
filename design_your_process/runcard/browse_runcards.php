<?php
include('runcard_common.php');

$my_runcards = Runcard::get_by_username($user->username);
$runcards = Runcard::get_all_public();

?>

<div id="my_runcards">
    <h3>My Runcards</h3>
    <?php
    $table_rows = array();
    foreach($my_runcards as $rc) {
        $table_rows[] = array('id' => $rc->id, 'Name' => $rc->name);
    }
    $links = array( 
                array('title' => 'View', 'page' => 'view_runcard.php', 'extra' => ' title="View Runcard" class="search-button"'),
                array('title' => 'Edit', 'page' => 'edit_runcard.php', 'extra' => ' title="Edit Runcard" class="edit-button"'),
                array('title' => 'Delete', 'page' => 'delete_runcard.php', 'extra' => ' class="delete-button" onclick="return confirm(\'Are you sure?\')" title="Delete Runcard"')
                );
    echo rows_to_table($table_rows, $links, 'id');

    ?>
</div>
<div id="public_runcards">
    <h3>Public Runcards</h3>
    
</div>

<?php
$instructions = 'To create a new runcard, first click the Home link to return to process selection.  Then select a process and click &quot;Add to Runcard&quot;.';

$content = ob_get_clean();
$title = "Browse Runcards";
load_template('template.html', array('TITLE' => $title, 'CONTENT' => $content, 'INSTRUCTIONS' => $instructions,'MESSAGES' => $message ));

?>
