<?php
	require_once('includes/Merch.php'); 
	$merch = new Merch;
?>
<div class="skmerchcontent col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="skmerchcontentin">
		<h2>SK MERCH //</h2>
		<div class="row">
			<?php foreach($merch->merchItems as $product) { ?>
			<div class="col-xs-6 product">
				<a href="<?php echo $merch->product($product); ?>" target="_blank"><img class="img-responsive" src="<?= $merch->image($product['PRODUCT_ID'],$product['IMAGE'],'browse_') ?>" alt="<?=$product['PRODUCT']?>" /></a>
			</div>
			<?php } ?>
		</div>
		<span class="clear"></span>
	</div>

	<div class="videocontent">
		<h2>WATCH STEPHEN'S TED TALK //</h2>
		<div class="video">
			<img src="/images/video.png" width="238"  class="img-responsive" height="134" alt="img">
			<a class="play" href="#"><img src="/images/playbt.png" width="50" height="50" alt="Play" title="Play"></a>
		</div>
	</div>

	<div class="campaign hide">
		<h2>CHECK OUT SK'S CAMPAIGN //</h2>
		<a href="#"><img class="img-responsive" src="/images/pledgemusic.png" width="199" height="29" alt="Pledge music" title="Pledge music"></a>
	</div>

</div>