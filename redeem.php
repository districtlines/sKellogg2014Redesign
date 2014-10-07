<?php
	session_start();
	include_once('./includes/config.php');
	include_once('./includes/redeem/functions.php');
	
	if($_GET['done']) { $_SESSION = array();header('Location:/redeem');exit;}
	if($_SESSION['authenticated']) {
									
		if(isset($_POST['process'])) {
			include('includes/redeem/process.php');
		} else {
			include('includes/header.php');
			echo '<div id="redeem-section">';
			include('includes/redeem/authenticated.php');
			echo '</div>';
			include('includes/sidebar.php');
			include('includes/footer.php');
		}
	} else {
		if( isset( $_POST['redeem_code'] ) ) {
			include('includes/redeem/check_code.php');
		} else {
			include('includes/header.php');
			echo '<div id="redeem-section">';
			include('includes/redeem/welcome.php');
			echo '</div>';
			include('includes/sidebar.php');
			include('includes/footer.php');
		}
	}
?>