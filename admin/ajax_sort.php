<?
	include('includes/config.php');
	
	if ($_REQUEST['table']) {
		$table = mysql_real_escape_string($_REQUEST['table']);
	} else {
		die("INVALID TABLE!");
	}

	
	if (is_array($_REQUEST['ids'])) {
		$ids = $_REQUEST['ids'];
	} else {
		die("INVALID LISTING!");
	}
	
	
	$count = 0;
	
	foreach ($ids as $id) {
		if ($id && is_numeric($id)) {
			$sql = "UPDATE `$table` SET `sort` = '$count' WHERE `id` = '$id' LIMIT 1";
			print "$sql<br />";
			mysql_query($sql) or die(mysql_error());
			$count++;
		}
	}
?>