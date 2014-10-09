<?php $instagram = $Site->Instagram; ?>
<div class="main_contentinleft col-lg-3 col-lg-pull-9 col-md-4 col-sm-4">
	<div class="widget-bg">
		<div class="twittercontent widget">
			<a class="twitter-timeline" width="263" href="https://twitter.com/Stephen_Kellogg" data-widget-id="319296738567520256">Tweets by @Stephen_Kellogg</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</div>
	</div>	
	
	<div class="widget-bg">
		<div class="instagramcontent widget">
			<h2 class="page-title">INSTAGRAM //</h2>
			<div class="row">
				<?php $count=0; foreach ($instagram->feed->data as $k => $v) { if($count==4) {break;}?>
				<div class="col-xs-6">
					<a class="fancybox-media" href="<?php echo $v->images->standard_resolution->url; ?>"><img src="<?php echo $v->images->standard_resolution->url; ?>" class="img-responsive"></a>
				</div>
				<?php ++$count; } ?>
			</div>
		</div>
	</div>	
	<span class="clear"></span>
</div>


<script type="text/javascript">
	$('.fancybox-media').fancybox({
		openEffect  : 'none',
		closeEffect : 'none',
		helpers : {
			media : {}
		}
	});

</script>