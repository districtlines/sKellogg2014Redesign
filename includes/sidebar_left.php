<?php
require_once('includes/Instagram.php');
$instagram = new Instagram;
?>
<div class="main_contentinleft col-lg-3 col-md-3 col-sm-3 col-xs-12">
	<div class="twittercontent">
		<a class="twitter-timeline" width="263" href="https://twitter.com/Stephen_Kellogg" data-widget-id="319296738567520256">Tweets by @Stephen_Kellogg</a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	</div>
	<div class="instagramcontent">
		<h2 class="page-title">INSTAGRAM //</h2>
		<div class="row">
			<?php $count=0; foreach ($instagram->feed->data as $k => $v) { if($count==4) {break;}?>
			<div class="col-xs-6">
				<img src="<?php echo $v->images->standard_resolution->url; ?>" class="img-responsive">
			</div>
			<?php ++$count; } ?>
		</div>
	</div>
	<span class="clear"></span>
</div>