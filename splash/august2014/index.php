<!DOCTYPE html>

<?php 
	
	$raw_dates = array(
				/*
				'08/31 Park City, UT',
				'09/21 San Diego, CA',
				*/
				'10/16/2014 Dublin, IRE',
				'10/17/2014 Brighton, UK', 
				'10/18/2014 Bristol, UK',
				'10/19/2014 Glasgow, UK',
				'10/20/2014 Manchester UK',
				'10/21/2014 London, UK',
				'10/23/2014 Brussels, BE',
				'10/24/2014 Nijmegen, NL',
				'10/25/2014 Amsterdam, NL',
				'10/26/2014 Hamburg, GER',
				'10/27/2014 Cologne, GER',
				'10/29/2014 Oslo, NOR',
				'10/30/2014 Stockholm, SWE',
				/*
				'10/31/2014 Copenhagen, DEN',
				'11/01/2014 Berlin, GER',
				*/
				'11/28/2014 Ardmore, PA',
				'11/29/2014 Vienna, VA', 
				'11/30/2014 Charlotte, NC',
				'12/02/2014 Carrboro, NC', 
				'12/03/2014 Decatur, GA', 
				'12/04/2014 Nashville, TN', 
				'12/05/2014 Evanston, IL', 
				'12/06/2014 Spring Lake, MI', 
				'12/07/2014 Chicago, IL', 
				'12/08/2014 Des Moines, IA', 
				'12/09/2014 Des Moines, IA', 
				'12/10/2014 Valparaiso, IN', 
				/*'11//201429 Vienna, VA',*/  
				'12/11/2014 Pittsburgh, PA', 
				'12/12/2014 New York, NY', 
				'12/13/2014 New York, NY',
				'12/26/2014 Hamden, CT', 
				'12/27/2014 Londonderry, NH',
				'12/28/2014 Somerville, MA',
				);
	
	$sold_out = array(
				'10/17/2014 Brighton, UK', 
				'10/18/2014 Bristol, UK',
				'10/19/2014 Glasgow, UK',
				'10/20/2014 Manchester UK',
				'10/21/2014 London, UK',
				'10/24/2014 Nijmegen, NL',
				'10/26/2014 Hamburg, GER',
				'10/27/2014 Cologne, GER',
				'10/30/2014 Stockholm, SWE',
				'12/07/2014 Chicago, IL', 
				'12/05/2014 Evanston, IL',
				'12/08/2014 Des Moines, IA',  
				'12/09/2014 Des Moines, IA', 
				);
				
	$dates = array();
	$today = strtotime('-1 day');
	asort($raw_dates);
	
	foreach($raw_dates as $d){
		$tour_date = explode(' ',$d,2);
		$dates[] = $tour_date;
/*
		if($tour_date[0] > $today){
			$dates[] = $tour_date;
		}
*/
	}
	
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="/splash/august2014/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="/splash/august2014/css/main.css">
		<title>Stephen Kellogg</title>
	</head>
	
	<body>
		<div class="container">
			<div class="row">
				<section class="content">
					<header>
						<a href="/home"><img src="/splash/august2014/images/logo.png" alt="stephenkellogg_logo" /></a>
						<p>Every Night's A Little Different Tour</p>
					</header>
					<div class="col-lg-6 col-md-9 col-sm-12 list">
						<div class="col-md-6 col-sm-6 col-xs-12 dates">
						<?php 
							foreach($dates as $key=>$d){
								$event = $d[0].' '.$d[1];
								
								if($key < (count($dates)/2)){
									echo '<div>';
									if(in_array($event,$sold_out)){
										echo '<span>SOLD OUT</span>';
										echo '<p>'.$event.'</p>';
									}else{
										echo '<p>'.$event.'</p>';
									}
									echo '</div>';
								}
							}
						?>
						</div>
					
						<div class="col-md-6 col-sm-6 col-xs-12 dates">
						<?php 
							foreach($dates as $key=>$d){
								$event = $d[0].' '.$d[1];
								
								if($key > (count($dates)/2)){
									echo '<div>';								
									if(in_array($event,$sold_out)){
										echo '<span>SOLD OUT</span>';
										echo '<p>'.$event.'</p>';
									}else{
										echo '<p>'.$event.'</p>';
									}
									echo '</div>';
								}
							}			
						?>
						</div>	
								
					</div>
					<div class="col-lg-6 col-md-3 links-container ">
						<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 links"><a href="/events"><img src="/splash/august2014/images/tickets.png" alt="tickets_available" /><span style="margin-left:-20px;">tickets available</span></a></div>
						<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 links site"><a href="/home"><span class="glyphicon glyphicon-chevron-right" style="margin-left: 33px;margin-top: 75px;margin-bottom: 0px;"></span></br><span>enter site</span></a></div>
					</div>	
				</section>
			</div>
		</div>
	</body>

</html>
