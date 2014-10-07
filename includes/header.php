<?php include_once('includes/config.php'); ?>
<!DOCTYPE html>
<html>
<head>
<title>Stephen Kellogg<?= isset($pageTitle) ? ' - '.$pageTitle : ' - Home';?></title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta name="keywords" content="Stephen Kellogg, music, sk6ers, sk, videos" />	
<meta name="description" content="Official website for Stephen Kellogg. Get all the up to date news, tour dates, and music here." />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" >

<!-- SET: FAVICON -->
<link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico">
<!-- END: FAVICON -->

<!-- SET: STYLESHEET -->
<link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/fonts/font-awesome-4.2.0/font-awesome-4.2.0/css/font-awesome.css">
<link href="/css/style.css" rel="stylesheet" type="text/css" media="all">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

<!-- END: STYLESHEET -->

<!-- SET: SCRIPTS -->
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.js"></script>
	<script type="text/javascript" src="/js/respond-1.1.0.min.js"></script>
	<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<!-- END: SCRIPTS -->
</head>

<body>
<!-- wrapper starts -->
<div class="wrapper">

		<!-- Header Starts -->
		<div class="header">
			<div class="container">
				<div class="row">
						<div class="headerin">
							<div id="logo">
								<a href="/"><img class="img-responsive" src="/images/logo.png" width="270" height="129" alt="Logo"></a>
							</div>

							<div class="navbar navbar-default col-lg-10 col-md-10 col-sm-10 col-xs-12">
							  <div class="navbar-header"> <a href="#" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"><strong>MENU</strong><span class="sr-only">Toggle navigation</span> <em class="strip"><span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span></em> <span class="clear"> </span></a> </div>
							  <div id="nav">
								<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
									<ul class="nav navbar-nav navbar-right">
										<li class="bordernone pad_last"><a href="/">HOME</a></li>
										<li><a href="/events">SHOWS</a></li>
										<li><a href="/music">MUSIC</a></li>
										<li class="list2"><a href="/band">CONTACT</a></li>
										<li class="list"><a href="<?php echo SIXERTOWN_URL; ?>" target="_blank">KELLOGGTOWN</a></li>
										<li class="last"><a href="<?php echo STORE_URL; ?>" target="_blank">STORE</a></li>
									</ul>
									<div class="clear"></div>
								</div>
							  </div>
							</div>

							<div class="share col-lg-2 col-md-2 col-sm-2 col-xs-12">
								<ul>
									<li><a href="<?php echo FACEBOOK_URL; ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
									<li><a href="<?php echo TWITTER_URL; ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
									<li><a href="#"><i class="fa fa-instagram"></i></a></li>
									<li><a href="<?php echo YOUTUBE_URL; ?>" target="_blank"><i class="fa fa-youtube"></i></a></li>
									<li><a href="#"><i class="fa fa-spotify"></i></a></li>
									<li><a href="#"><i class="fa fa-soundcloud"></i></a></li>
								</ul>
								<div class="clear"></div>
							</div>

						</div>
					</div>
			</div>
		<!-- Header ends -->
		</div>
		
		<?php include('includes/layouts/'.$layout.'/header.php'); ?>