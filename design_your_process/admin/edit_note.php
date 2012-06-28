<?php
require_once('../library/helper_functions.php');
require_once('../library/form.php');
require_once('../library/domain_model/note.php');
require_once('redirect_non_admins.php');
$message="";

$process_id=$_REQUEST['process_id'];
$id=$_REQUEST['id'];
$process_name=process_name($process_id);

$message = "Add note for process: $process_name";

if(isset($_POST['submit']) || isset($_POST['finish'])){
    $note = new Note();
    if ($id) {
        $note->get_by_id($id);
    }
    $note->note = $_POST['note'];
    $note->author = "anonymous";
    if (isset($_POST['author'])) {
        $note->author = $_POST['author'];
    }
    $note->set_process_id($process_id);
    $note->save();
    header('Location: select_parameter.php?process_id=' . $process_id);
}

$title = "Add Note";
ob_clean();
ob_start();

$note = new Note();
$note->get_by_id($id);

$form = new Form();
$form->open();
$form->textarea(
            array(
            'name' => 'note',
            'id' => 'note',
                'label' => 'Note',
                'class' => 'origin_desc',
                'value' => $note->note
            )
        );
$form->add_html("<div>");
$form->submit(
            array(
            'label' => '',
            'name' => 'submit',
            'id' => 'submit',
            'value' => 'Submit',
            )
        );
$form->add_html("</div>");

$form->close();
echo $form->html();	

?>

<?php
$content = ob_get_clean();
load_template('template.html', array('TITLE' => $title, 'CONTENT' => $content, 'INSTRUCTIONS' => '','MESSAGES' => $message ));

?>
