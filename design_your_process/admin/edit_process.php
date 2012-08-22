<?php
require_once('../library/helper_functions.php');
require_once('redirect_non_admins.php');
$all_categories = get_all_categories();
$all_processes = get_all_processes();
$all_materials = get_all_materials();
$all_tools = get_all_tools();
$quick_search = "<script src=\"" . DSPWEBROOT . "/assets/quick_search.js\" type=\"text/javascript\"></script>\n";
                                

ob_clean();
ob_start();
?>
<script>
function capture_process_id() {
    $('form').append('<input name="process_id" type="hidden" value="' + the_process.id + '"></input>');
    var action = $('form').attr('action');
    action += '?process_id=' + the_process.id;
    $('form').attr('action', action);
}    
</script>
            <form action="select_parameter.php" method="post">	
			<ul >
					<li id="li_quick_search" >
		<label class="description" for="quick_search">Quick Search</label>
		<div>
		<input class="element input medium" id="quick_search" name="quick_search" type="text"/>
		</div> 
		</li>		
			
					<li id="li_1" >
		<label class="description" for="element_1">Category </label>
		<div>
		<select class="element select medium" id="category"  name="category" onchange="narrow_processes(capture_process_id);"> 
			<option value="" selected="selected"></option>
			<?=array_to_options_html($all_categories)?>
		</select>
		</div> 
		</li>		<li id="li_2" >
		<label class="description" for="element_2">Process </label>
		<div>
		<select class="element select medium" id="process" name="process" onchange="narrow_processes(capture_process_id);"> 
			<option value="" selected="selected"></option>
			<?=array_to_options_html($all_processes)?>
		</select>
		</div> 
		</li>		<li id="li_3" >
		<label class="description" for="element_3">Tool </label>
		<div>
		<select class="element select medium" id="tool" name="tool" onchange="narrow_processes(capture_process_id);"> 
			<option value="" selected="selected"></option>
			<?=array_to_options_html($all_tools)?>

		</select>
		</div> 
		</li>		<li id="li_4" >
		<label class="description" for="element_4">Material </label>
		<div>
		<select class="element select medium" id="material" name="material" onchange="narrow_processes(capture_process_id);"> 
			<option value="" selected="selected"></option>
			<?=array_to_options_html($all_materials)?>

		</select>
		</div> 
		</li>
			
					<li class="buttons">
			   <!-- <input type="hidden" name="form_id" value="384636" /> -->
			 
				<input id="saveForm" class="button_text" type="submit" name="Go" value="Go"/>
				
		</li>
			</ul>
            </form>
		
<?php
$content = ob_get_clean();
load_template('template.html', array('TITLE' => 'Select a Process to Edit',
    'HEAD' => $quick_search,
    'CONTENT' => $content, 
    'INSTRUCTIONS' => 'Please select a process.' ));

	if(isset($_POST['Go'])){
            $category=$_POST['category'];
            $process=$_POST['process'];
            $tool=$_POST['tool'];
            $material=$_POST['material'];
            $sql=("SELECT id from processes WHERE category='$category' and process='$process' and tool='$tool' and material='$material'");
            $process_id=db_query($sql);
        }
?>









