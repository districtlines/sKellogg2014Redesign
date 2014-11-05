<?php $instagram = $Site->Instagram; ?>
<?php $slides = $Site->Slides; ?>
<div class="main_contentinleft">
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
					<a class="fancybox-media thumbnail" href="<?php echo $v->images->standard_resolution->url; ?>"><img src="<?php echo $v->images->standard_resolution->url; ?>" class="img-responsive"></a>
				</div>
				<?php ++$count; } ?>
			</div>
		</div>
	</div>
	
	<div id="slider_container">
		<div id="slider">
		<?php 
			
			if( isset($slides) && $slides != false) {
				foreach($slides as $slide) {
		?>
			<div class="slide">
				<?php if(!empty($slide['link'])) : ?>
				<a href="<?=$slide['link'];?>" title="<?=$slide['name'];?>">
				<?php endif; ?>
					<img width="255" height="131" src="<?=ROOT?>/uploads/slideshow/<?=$slide['id']?>/<?=$slide['image']?>" />
				<?php if(!empty($slide['link'])) : ?>
				</a>
				<?php endif; ?>
			</div>
			
		<?php } } ?>
		</div> <!-- slider -->
		<div id="slider_pagination">
			<ul>
			</ul>
			<div class="clear"></div>
		</div> <!-- slider_pagination -->
	</div> <!-- slider_container -->
	
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
	
	(function($) {
		$('#slider').cycle({ 
	    	fx: 'fade', 
	    	speed: 2000,
	    	pause: true,
	    	pauseOnPagerHover: true,
	    	timeout: 4000,
	    	pager:  '#slider_pagination ul', 
    		pagerAnchorBuilder: function(idx, slide) { 
		        return '<li><a href="#">' + (idx+1) + '</a></li>'; 
		    } 
		});
	})(jQuery);
	
</script>