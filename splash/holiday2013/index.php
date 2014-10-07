<?php session_start(); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Stephen Kellogg's - Blunderstone Rookery</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="icon" type="image/x-icon" href="favicon.ico" />

        <link rel="stylesheet" href="/splash/holiday2013/css/normalize.css">
        <link rel="stylesheet" href="/splash/holiday2013/css/main.css">
        <link rel="stylesheet" href="/splash/holiday2013/fancybox/jquery.fancybox.css">
        <meta id="viewport" name="viewport" content="width=device-width; initial-scale=0.35; maximum-scale=1.0; user-scalable=0;" />
		<link rel="apple-touch-icon" href="/splash/holiday2013/images/apple-touch-icon.png" />
    </head>
    <body>
    	<style>
    		.right td {
	    		height: 55px;
    		}
    	</style>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->
        <br />
        <br />
        <header>
        	<img src="/splash/holiday2013/images/nov2013-logo.png" width="716" height="145" />
        </header>
        <div id="wrapper" style="text-align:center;">
        	<div class="left">	
        		<div class="player">
        			<a href="http://www.youtube.com/embed/yJNTy_tjghQ" class="fancyvideo blk" data-fancybox-type="iframe">
	        			<img src="/splash/holiday2013/images/nov2013-player-thumb.jpg" />
        			</a>
        		</div>
        		<a href="http://www.districtlines.com/stephen-kellogg" target="_blank">
        			<img src="/splash/holiday2013/images/nov2013-visit-merch.png" />
        		</a>
        	</div>
        	
        	<div class="right">
        		<div class="tour-dates">
        			<img src="/splash/holiday2013/images/nov2013-tour-heading.png" />
        			<table onclick="window.open('http://www.stephenkellogg.com/events', '_blank');" width="100%" cellpadding="0" cellspacing="0" border="0">
        				<?php
        					$tourDates = array(
        						array(
        							'date' => '12/04 - 12/17',
        							'place' => 'supporting Josh Ritter<br />in Ireland',
        							'soldOut' => true
        						),
        						array(
        							'date' => '12/18',
        							'place' => 'supporting Josh Ritter<br/>in Wexford, Ireland',
        							'soldOut' => true
        						),
        						array(
        							'date' => '12/19 - 12/22',
        							'place' => 'supporting Milow<br />in Belgium',
        							'soldOut' => true
        						),
        						array(
        							'date' => '12/26',
        							'place' => 'Portland, ME',
        							'soldOut' => false
        						),
        						array(
        							'date' => '12/27',
        							'place' => 'Londonderry, NH',
        							'soldOut' => true
        						),
        						array(
        							'date' => '12/28',
        							'place' => 'White River Junction, VT',
        							'soldOut' => false
        						),
        						array(
        							'date' => '12/29',
        							'place' => 'Hartford, CT',
        							'soldOut' => false
        						),
        						array(
        							'date' => '02/07',
        							'place' => 'Cayamo Cruise',
        							'soldOut' => true
        						),
        						array(
        							'date' => '02/19',
        							'place' => 'Richmond, VA',
        							'soldOut' => false
        						),
        						array(
        							'date' => '02/20',
        							'place' => 'Virginia Beach, VA',
        							'soldOut' => false
        						),
        						array(
        							'date' => '02/22',
        							'place' => 'Rock Boat Cruise',
        							'soldOut' => false
        						),
        						array(
        							'date' => '2/28',
        							'place' => 'Charlotte, NC',
        							'soldOut' => false
        						),
        						array(
        							'date' => '03/01',
        							'place' => 'Carrboro, NC',
        							'soldOut' => false
        						),
        						array(
        							'date' => '03/02',
        							'place' => 'Wilmington, DE',
        							'soldOut' => false
        						),
        						array(
        							'date' => '03/05',
        							'place' => 'Annapolis, MD',
        							'soldOut' => false
        						),
        						array(
        							'date' => '03/07',
        							'place' => 'Burlington, VT',
        							'soldOut' => false
        						),
/*
        						array(
        							'date' => '',
        							'place' => '',
        							'soldOut' => false
        						),
*/
        					);
        				?>
        				
        				<tr>
        					<td>
        						<table cellpadding="0" cellspacing="0" border="0" width="100%">
        					
        				<?php
        					$tourCount = count($tourDates);
        					$itemsPerCol = round($tourCount / 2);
        					$eachCount = 0;
        					foreach($tourDates as $tour) {
	        					$eachCount++;
        				?>
	        						<tr>
	        							<td>
	        								<span><?=$tour['date'];?></span>
	        								<?php if($tour['soldOut']) { ?>
	        								<div class="datename">
												<span class="soldout"></span>
												<?=$tour['place'];?>
	        								</div>
	        								<?php } else { ?>
	        									<?=$tour['place'];?>
	        								<?php } ?>
	        							</td>
	        						</tr>
	        				<?php if($eachCount == $itemsPerCol && $eachCount != $tourCount) { ?>
        						</table>
        					</td>

							<td>
        						<table cellpadding="0" cellspacing="0" border="0" width="100%">
        						
	        				<?php } elseif($eachCount == $tourCount) { ?>
        						</table>
							</td>
        				</tr>
	        				<?php } ?>
	        			<?php } ?>
        			</table>
        		</div>
        		<br />
        		<a style="background-color:rgba(0,0,0,0.2);padding:20px;" href="/home">
        			<img src="/splash/holiday2013/images/nov2013-enter.png" />
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
