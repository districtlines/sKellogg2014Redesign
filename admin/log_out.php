<?
	include('includes/config.php');
	$_SESSION['logged_in_id'] = 0;
	
	redirect('./' . $_sections[0] . '.php');
?>