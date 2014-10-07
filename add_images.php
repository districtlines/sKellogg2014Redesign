<?
	ini_set("memory_limit","300M");
	ini_set("max_execution_time","0");
	ini_set("max_input_time","0");
	
	include('includes/config.php');
	
	$a = $_GET['album'];
		
	$dir = scandir("imgs/$a");
		
/* 	$albumName = str_replace(' ','',$a); */
	if($a == 'BehindTheScenes') {
		
		$db_name = "Behind The Scenes";
	
	} else {
	
		$db_name = $a;
	
	}

	foreach($dir as $file) {
		
		$filename = explode('/',$file);
		
		$filename = array_pop($filename);
		
		$sql = "SELECT id FROM photo_albums WHERE name = '$db_name'";
		
		$query = mysql_query($sql);
		
		$a_id = mysql_result($query,0);
		
		$sql2 = "INSERT INTO photos (album_id,name,photo,created,modified,thumbnail) VALUES ($a_id,'$filename','$file',NOW(),NOW(),'$file')";
		
		mysql_query($sql2);
		
		$new_id = mysql_insert_id();
				
		$working_dir = $_SERVER['DOCUMENT_ROOT'] . "/imgs/$a/$file";
		
		chmod($working_dir,777);
		
		if(!is_dir($_SERVER['DOCUMENT_ROOT'] . '/photos/'.$new_id)) {
		
			mkdir($_SERVER['DOCUMENT_ROOT'] . '/photos/'.$new_id);
		
		}
		
		$newdir = $_SERVER['DOCUMENT_ROOT'] . "/photos/$new_id/$file";
		
		chmod($newdir,777);
		
		rename($working_dir,$newdir);
		
		echo $file . " has been moved";
	}
	
?>