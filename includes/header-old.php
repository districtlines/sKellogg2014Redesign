<?php
	@ini_set('display_errors','Off');
	@ini_set('error_reporting',0);
?>

<? include_once('includes/config.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

	<title>Stephen Kellogg<?=$pageTitle ? ' - '.$pageTitle : '';?></title>

	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

	<meta name="keywords" content="Stephen Kellogg, music, sk6ers, sk, videos" />	
	<meta name="description" content="Official website for Stephen Kellogg. Get all the up to date news, tour dates, and music here." />
	<meta name="robots" content="noindex, nofollow" /><!-- change into index, follow -->
	
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
	<script type="text/javascript" src="<?=ROOT?>/js/fancybox/jquery.fancybox-1.3.4.js"></script>
	<script type="text/javascript" src="<?=ROOT?>/js/jquery.cycle.min.js"></script>

				
	<link rel="stylesheet" href="<?=ROOT?>/css/global.css" type="text/css" />
	<link rel="stylesheet" href="<?=ROOT?>/js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" />
	
	<!--[if lte IE 6]>
		<script type="text/javascript" src="js/pngfix.js"></script>
		<script type="text/javascript" src="js/ie6.js"></script>

		<link rel="stylesheet" href="css/ie6.css" type="text/css" />
	<![endif]-->

</head>
<body>

<!--  / WRAPPER \ -->
<div id="wrapper">
	
	<!--  / HEADER CONTAINER \ -->
	<div id="headerCntr">
	
		<div class="middle-container">
		
			<!--  / MENU CONTAINER \ -->
			<div id="menuCntr">
					
				<ul>
					<li class="home<? echo $curr_page == 'home' ? ' selected' : '';?>"><a href="<?=ROOT?>/home">HOME</a></li>
	                <li class="news<? echo $curr_page == 'news' ? ' selected' : '';?>"><a href="<?=ROOT?>/news">NEWS</a></li>
	                <li class="events<? echo $curr_page == 'events' ? ' selected' : '';?>"><a href="<?=ROOT?>/events">SHOWS</a></li>
	                <li class="music<? echo $curr_page == 'music' ? ' selected' : '';?>"><a href="<?=ROOT?>/music">MUSIC</a></li>
	                <li class="media<? echo $curr_page == 'media' ? ' selected' : '';?>"><a href="<?=ROOT?>/media">VISUAL</a></li>
	                <li class="bio<? echo $curr_page == 'band' ? ' selected' : '';?>"><a href="<?=ROOT?>/band">BIO</a></li>
	                <li class="blog"><a href="http://stephenkelloggmusic.tumblr.com/" target="_blank">MUSINGS</a></li>
	                <li class="sixertown"><a href="<?= SIXERTOWN_URL ?>" target="_blank">VIP PACKAGES</a></li>
	                <li class="store"><a href="<?= STORE_URL ?>" target="_blank">STORE</a></li>
				</ul>
	                                                                    
			</div>
			<!--  \ MENU CONTAINER / -->
	        
	        <div class="linkBox">
	        
	        	<ul>
	            	<li><a href="<?= TWITTER_URL ?>" class="twitter" target="_blank">twitter</a></li>
	                <li><a href="<?= FACEBOOK_URL ?>" class="facebook" target="_blank">facebook</a></li>
	                <?php /*<li><a href="<?= FACEBOOK_URL ?>" class="instagram" target="_blank">instagram</a></li>*/ ?>
	                <li><a href="<?= YOUTUBE_URL ?>" class="youtube" target="_blank">youtube</a></li>
	                <?php /*<li><a href="<?= ROOT ?>/contact" class="mail" target="">mail</a></li>*/ ?>	                
	            </ul>
	        	
	        	<script src="https://apis.google.com/js/plusone.js"></script><div class="g-ytsubscribe" data-channel="sk6ersofficial" data-layout="default"></div>
	        	<iframe src="https://embed.spotify.com/follow/1/?uri=spotify:artist:794GTZkztMS29PO7cTOnmY&size=basic&theme=light&show-count=0" width="200" height="25" scrolling="no" frameborder="0" style="border:none; overflow:hidden;" allowtransparency="true" class="spotify-subscribe"></iframe>
	        	
	        </div>
	        
		</div>

	</div>
	<!--  \ HEADER CONTAINER / -->
	
	<div class="middle-container" style="margin: 30px auto;">
		<img src="<?= ROOT ?>/images/sk-header.png" />
	</div>
	
	<!--  / MAIN CONTAINER \ -->
	<div id="mainCntr">
		
		<!--  / CONTENT CONTAINER \ -->
				<div id="contentCntr">
		        	<div class="bottom">
		            	<div class="mid">
		
		                    <!--  / LEFT CONTAINER \ -->
		                    <div id="leftCntr">
