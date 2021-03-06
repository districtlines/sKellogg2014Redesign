<?php 
	$merch = $Site->Merch;
	$youtube = $Site->youtube();
?>

<div class="skmerchcontent">
	<div class="widget-bg">
		<div class="skmerchcontentin widget">
			<h2>SK MERCH //</h2>
			<div class="row">
				<?php foreach($merch->merchItems as $product) { ?>
				<div class="col-xs-6 product">
					<a href="<?php echo $merch->product($product); ?>" target="_blank" class="thumbnail"><img class="img-responsive" src="<?= $merch->image($product['PRODUCT_ID'],$product['IMAGE'],'browse_') ?>" alt="<?=$product['PRODUCT']?>" /></a>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	
	<div class="widget-bg">		
		<div class="videocontent widget">
			<h2>WATCH STEPHEN'S TED TALK //</h2>
			<div class="video">
				<a class="youtube" href="<?=$youtube['url']?>">
					<img src="<?=$youtube['photo']?>" class="img-responsive" alt="img">
					<span class="play"><img src="/images/playbt.png" width="50" height="50" alt="Play" title="Play"></span>
				</a>
			</div>
		</div>
	</div>
	<?php if(PLEDGE_URL !== null && PLEDGE_URL != '' && PLEDGE_URL != 'null') { ?>
	<div class="widget-bg">		
		<div class="campaign widget">
			<h2>CHECK OUT SK'S CAMPAIGN //</h2>
			<a href="<?php echo PLEDGE_URL; ?>"><img class="img-responsive" src="/images/pledgemusic.png" width="199" height="29" alt="Pledge music" title="Pledge music"></a>
		</div>
	</div>
	<?php } ?>
</div>


<script type="text/javascript">
$(document).ready(function() {
	$('.youtube').fancybox({
		maxWidth	: 800,
		maxHeight	: 600,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		openEffect  : 'none',
		closeEffect : 'none',
		helpers : {
			media : {}
		}
	});
	
	});
</script>