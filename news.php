<?php
	$active = 'home';
	$pageTitle = 'Events';
	$curr_page = 'events';
	$layout = 'main';
	include('./includes/header.php');
	if(isset($_GET['page'])) {
		include('includes/news/list.php');
	} elseif(isset($_GET['article'])) {
		include('includes/news/single.php');
	} else {
		header('Location: /home');
		exit;
	}
	include('./includes/footer.php');
	exit;
?>