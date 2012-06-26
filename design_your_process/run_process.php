<?php
require_once('library/helper_functions.php');
require_once('library/authentication.php');
$all_categories = get_all_categories();
$all_processes = get_all_processes();
$all_materials = get_all_materials();
$all_tools = get_all_tools();
$auth = new Authentication();
$login_html = "<a href=\"admin/login.php\">Login</a>";
ob_clean();
ob_start();
?>
<script>
    function create_parameters_table() {
            $('form').append('<table><tr>');
        for ( var parameter in the_process ) {
            
            $('form').append('<th>' + parameter + '</th>');
        }
        $('form').append('</tr><tr>');
        for ( var parameter in the_process ) {
            
            $('form').append('<td>' + eval( 'the_process.' + parameter) + '</td>');
            
        }
        $('form').append('</tr></table>');
            
    }
</script>
<form>
			<ul >
			
					<li id="li_1" >
		<label class="description" for="element_1">Category </label>
		<div>
		<select class="element select medium" id="category"  name="category" onchange="narrow_processes(create_parameters_table);"> 
			<option value="" selected="selected"></option>
			<?=array_to_options_html($all_categories)?>
		</select>
		</div> 
		</li>		<li id="li_2" >
		<label class="description" for="element_2">Process </label>
		<div>
		<select class="element select medium" id="process" name="process" onchange="narrow_processes(create_parameters_table);"> 
			<option value="" selected="selected"></option>
			<?=array_to_options_html($all_processes)?>
		</select>
		</div> 
		</li>		<li id="li_3" >
		<label class="description" for="element_3">Tool </label>
		<div>
		<select class="element select medium" id="tool" name="tool" onchange="narrow_processes(create_parameters_table);"> 
			<option value="" selected="selected"></option>
			<?=array_to_options_html($all_tools)?>

		</select>
		</div> 
		</li>		<li id="li_4" >
		<label class="description" for="element_4">Material </label>
		<div>
		<select class="element select medium" id="material" name="material" onchange="narrow_processes(create_parameters_table);"> 
			<option value="" selected="selected"></option>
			<?=array_to_options_html($all_materials)?>

		</select>
		</div> 
		</li>
			
					<li class="buttons">
			    <input type="hidden" name="form_id" value="384636" />
			    
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
		</li>
			</ul>
    </form>
<?php
$content = ob_get_clean();
load_template('template.html', array('TITLE' => 'Select a Process',
                                            'CONTENT' => $content,  
                                            'INSTRUCTIONS' => 'Please select a process.', 
                                            'LOGIN' => $login_html));

?>