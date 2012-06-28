<?php
require_once('../library/helper_functions.php');
require_once('../library/form.php');
require_once('redirect_non_admins.php');
$message="";

$process_id=$_REQUEST['process_id'];
$process_name=process_name($process_id);

$message = "Add parameters for process: $process_name";

if(isset($_POST['submit']) || isset($_POST['finish'])){
    $parameter=$_POST['parameter'];
    $unit=$_POST['unit'];
    $value=$_POST['value'];
    $confidence=$_POST['confidence'];
    $data_origin=$_POST['data_origin'];
    if($parameter){
    validate_inputs($unit, $value);
    add_parameter($process_id, $parameter, $unit, $value, $confidence, $data_origin);
    }
    if(isset ($_POST['finish'])){
        header("Location: select_parameter.php?process_id=$process_id");
    }else{
        $message = "Continue adding parameters for process: $process_name or select 'finish'";
    }

}

$title = "Enter Parameters";
ob_clean();
ob_start();
	
$form = new Form();
$form->open();
$form->input(
            array(
                'label' => 'Parameter',
                'name' => 'parameter',
                'id' => 'parameter',
                'class' => 'element text medium'
            )
        );
$form->input(
            array(
                'label' => 'Unit',
                'name' => 'unit',
                'id' => 'unit',
                'class' => 'element text medium'
            )
        );
$form->input(
            array(
                'label' => 'Value',
                'name' => 'value',
                'id' => 'value',
                'class' => 'element text medium'
            )
        );
$form->radio_list(
            array(
                'label' => 'Parameter Type',
                'name' => 'parameter_type',
                'radios' => array(
                    array('id'=>'input_radio', 'class' => 'element radio', 'value' => 'input', 'label' => 'Input'),
                    array('id'=>'process_parameter_radio', 'class' => 'element radio', 'value' => 'process_parameter', 'label' => 'Process Parameter'),
                    array('id'=>'measured_result_radio', 'class' => 'element radio', 'value' => 'measured_result', 'label' => 'Predicted Result'),
                )
            )
        );
$form->input(
            array(
                'label' => 'Confidence Level',
                'name' => 'confidence',
                'id' => 'confidence',
                'class' => 'element text medium'
            )
        );

$form->textarea(
            array(
                'label' => 'Data Origin',
                'name' => 'data_origin',
                'id' => 'data_origin',
                'class' => 'element text medium origin_desc'
            )
        );

$form->submit(
            array('name' => 'submit',
                'value' => 'Submit')
        );
$form->submit(
            array('name' => 'finish',
                'value' => 'Finish')
        );


?>
<script language="javascript" src="../assets/toggle.js"> </script>
  <form id="form_387875" class="appnitro" method="post" action="">

      <li class="buttons">
          <?=$form->html();?>
      </li>
    </ul>
  </form>
		
<?php
$content = ob_get_clean();
load_template('template.html', array('TITLE' => $title, 'CONTENT' => $content, 'INSTRUCTIONS' => '','MESSAGES' => $message ));

?>
