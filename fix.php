<?
	
	include('includes/config.php');
	
	
	$sql = "SELECT * FROM news";
	
	$query = mysql_query($sql);
	
	while($row = mysql_fetch_assoc($query)) {
		
		$content = strip_tags($row['content']);
		
		$replace = array('</b>','</u>','</embed>','</a>', '</u>');
		
		$content = str_replace($replace,'',$content);
		
		echo $content;
		
		$sql2 = "UPDATE news SET content = '$content' WHERE id = {$row['id']}";
		
		mysql_query($sql2);
		
	}
	
/* 	echo "<h1>FIXED!</h1>"; */
	
?>