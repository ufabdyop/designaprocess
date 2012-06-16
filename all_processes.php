<?php
	require_once('library/pdo.php');
	require_once('library/table.php');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>All Processes</title>
</head>
	<body>
		<h1>All Processes</h1>
		<?
		$rows = db_query("select id,category,process,tool,material from processes");
		//add the link to the result set
		for ( $i = 0; $i < count($rows) ; $i++ ) {
			$rows[$i]['Link'] = '<a href="process.php?process_id=' . $rows[$i]['id']. '">Link</a>';
		}
		echo rows_to_table($rows);
		?>
	</body>
</html>

