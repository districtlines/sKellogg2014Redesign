<!DOCTYPE html>

<?php 
	
	$raw_dates = array(
				/*
				'08/31 Park City, UT',
				'09/21 San Diego, CA',
				*/
				'10/16 Dublin, IRE',
				'10/17 Brighton, UK', 
				'10/18 Bristol, UK',
				'10/19 Glasgow, UK',
				'10/20 Manchester UK',
				'10/21 London, UK',
				'10/23 Brussels, BE',
				'10/24 Nijmegen, NL',
				'10/25 Amsterdam, NL',
				'10/26 Hamburg, GER',
				'10/27 Cologne, GER',
				'10/29 Oslo, NOR',
				'10/30 Stockholm, SWE',
				/*
				'10/31 Copenhagen, DEN',
				'11/01 Berlin, GER',
				*/
				'11/28 Ardmore, PA',
				'11/29 Vienna, VA', 
				'11/30 Charlotte, NC',
				'12/02 Carrboro, NC', 
				'12/03 Decatur, GA', 
				'12/04 Nashville, TN', 
				'12/05 Evanston, IL', 
				'12/06 Spring Lake, MI', 
				'12/07 Chicago, IL', 
				'12/08 Des Moines, IA', 
				'12/09 Des Moines, IA', 
				'12/10 valparaiso, IN', 
				/*'11/29 Vienna, VA',*/  
				'12/11 Pittsburgh, PA', 
				'12/12 New York, NY', 
				'12/13 New York, NY',
				'12/26 Hamden, CT', 
				'12/27 Londonderry, NH',
				'12/28 Somerville, MA',
	
				);
				
	$dates = array();
	$today = date("m/d");
	asort($raw_dates);
	
	foreach($raw_dates as $d){
		$tour_date = explode(' ',$d,2);
		if($tour_date[0] > $today){
			$dates[] = $tour_date;
		}
	}
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="/splash/august2014/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="/splash/august2014/css/main.css">
		<title>Stephen Kellog</title>
	</head>
	
	<body>
		<div class="container">
		<div class="row">
	<section class="content">
		<header>
			<a href="/home"><img src="/splash/august2014/images/logo.png" alt="stephenkellogg_logo" /></a>
			<p>Every Night's A Little Different Tour</p>
		</header>
			<div class="col-md-6 list">
				<div class="col-md-6 col-sm-6 col-xs-6 dates">
					<?php 
						foreach($dates as $key=>$d){
							if($key < (count($dates)/2)){
								echo '<p>'.$d[0].' '.$d[1].'</p>';
							}
						}						
					?>
				</div>	
				<div class="col-md-6 col-sm-6 col-xs-6 dates">
					
					<?php 
						foreach($dates as $key=>$d){
							if($key > (count($dates)/2)){
								echo '<p>'.$d[0].' '.$d[1].'</p>';
							}
						}						
					?>	
				</div>	
			</div>
			<div class="col-md-3 col-xs-12 links"><a href="/events"><img src="/splash/august2014/images/tickets.png" alt="tickets_available" /><span style="margin-left:-20px;">tickets available</span></a></div>
			<div class="col-md-3 col-xs-12 links site"><a href="/home"><span class="glyphicon glyphicon-chevron-right" style="margin-left: 33px;margin-top: 75px;margin-bottom: 0px;"></span></br><span>enter site</span></a></div>
		</section>
	</div>
	</div>
	</body>

</html>
