<?php 
	session_start();
	include('splash/january2014/includes/tourDates.php');
?>
<!DOCTYPE html>
<!--[if lt IE 7]>	   <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>		   <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>		   <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Stephen Kellogg's - Blunderstone Rookery</title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width">

		<link rel="icon" type="image/x-icon" href="favicon.ico" />
		<link href='http://fonts.googleapis.com/css?family=Pathway+Gothic+One' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="/splash/january2014/css/normalize.css">
		<link rel="stylesheet" href="/splash/january2014/css/main.css">
		<link rel="stylesheet" href="/splash/january2014/fancybox/jquery.fancybox.css">
		<meta id="viewport" name="viewport" content="width=device-width; initial-scale=0.35; maximum-scale=1.0; user-scalable=0;" />
		<link rel="apple-touch-icon" href="/splash/january2014/images/apple-touch-icon.png" />
	</head>
	<body>
		<!--[if lt IE 7]>
			<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
		<![endif]-->

		<header>
			<img src="/splash/january2014/images/Jan-2014-logo.png" width="483" height="318" />
		</header>
		<div id="wrapper" style="text-align:center;">
			<div class="tour-dates" style="color:white;">
				<img src="/splash/january2014/images/on_tour.png" />
				<table width="100%" cellpadding="0" cellspacing="0" border="0" style="cursor:pointer">	
					
					<?php $count = 1; ?>
					
					<tr class="tours" onclick="window.open('http://www.stephenkellogg.com/events', '_blank');">
					<?php
						foreach($tourDates as $tour) {
							if ($count > $tourcolumns){
								$count = 1;
					?>
					</tr>
					<tr class="tours" onclick="window.open('http://www.stephenkellogg.com/events', '_blank');">
						<td>
							<span class="tourday"><?=$tour['date'];?></span> - <?=$tour['place'];?>
						</td>
					
					<?php
								$count++;	
							} else {		
					?>
						<td>
							<span class="tourday"><?=$tour['date'];?></span> - <?=$tour['place'];?>
							<?php if($tour['soldOut']) { echo '<span class="soldout">(SOLD OUT)</span>'; }?>
						</td>
					<?php
								$count++;
							}	
						 }
					?>
				</table>
			</div>
			<div class="navigation">
				<a href="/home">
					<img src="/splash/january2014/images/Jan-2014-sklogo.png" />
				</a>
				<a href="http://www.districtlines.com/stephen-kellogg" target="_blank">
					<img src="/splash/january2014/images/Jan-2014-merch.png" style="margin-left:10px;"/>
				</a>
			</div>
		</div>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="/splash/blunderstone_rookery/fancybox/jquery.fancybox.js"></script>
		
		<script type="text/javascript">
			$('.fancyvideo').fancybox({
				
			});
		</script>
	</body>
</html>
