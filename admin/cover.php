<?php
	include('includes/config.php');
	$ref = $_SERVER['HTTP_REFERER'];
	
	if(isset($_GET['coverID']) && isset($_GET['galleryID']))
	{
		$coverId = $_GET['coverID'];
		$galleryId = $_GET['galleryID'];
		$sql = "UPDATE photo_albums SET thumbnail = '$coverId' WHERE id='$galleryId'";
		$query = mysql_query($sql);
		if($query)
		{
			$_SESSION['success'] = "Gallery Cover Successfully Set!";
		}else{
			$_SESSION['success'] = "Gallery cover not set. Please try again.";
		}
		
	}
	
	header("Location: $ref");

?>