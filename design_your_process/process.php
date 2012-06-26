<?php
	require_once('library/pdo.php');
	require_once('library/table.php');
	$process_id = $_GET['process_id'];
	if (!$process_id) {
		echo "Please specify a process\n";
		die ;
	}
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>Process ID# <?=$process_id?></title>
</head>
	<body>

		<?
		$process_index = db_query("select * from processes where id = '$process_id'");
		if ($process_index) {
			$process_name = $process_index[0][1] . ', ' .$process_index[0][2] . ', ' .$process_index[0][3] . ', '.$process_index[0][4] ;
		}
		echo "<h1>Process: $process_name</h1>\n";
		$sql = ("select name, type, unit, process_form.value from process_form left join parameter on process_form.parameter_id = parameter.id where process_form.process_id = '$process_id'");
		$fields = db_query($sql);
		echo rows_to_table($fields);
		?>
	</body>
</html>





