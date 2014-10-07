<? $active = 'home'; ?>
<? $pageTitle = 'Media'; ?>
<? $curr_page = 'media'; ?>
<? include('./includes/header.php'); ?>

	<div class="media">
	
		<h2 class="heading">PHOTO ALBUMS</h2>
		
		<?php
			$sql = "SELECT * FROM photo_albums ORDER BY sort ASC";
			$query = mysql_query($sql) or die(mysql_error());
			$check = mysql_num_rows($query);
			$count = 0;
			
			if($check) {
				while($row = mysql_fetch_assoc($query)) :
					$sqlImg = "SELECT * FROM photos WHERE album_id = ".$row['id']." AND id = ".$row['thumbnail']." LIMIT 1";
					$queryImg = mysql_query($sqlImg) or die(mysql_error());
					$album_cover = mysql_fetch_assoc($queryImg);
					$count ++;
		?>
		
			<div class="palbum_cover <?=$count % 3 == 0 ? 'last' : 'first';?> single_cover">
				<a href="<?=ROOT?>/photos/<?=$row['id'];?>">
					<img width="193px" src="<?=ROOT?>/uploads/photos/<?=$album_cover['id'];?>/<?=$album_cover['photo'];?>" />
				</a>
				<p><?=strlen($row['name']) >= 26 ? substr(trim($row['name']), 0, 23)."..." : $row['name'];?></p>
			</div>
		<?php
				if($count % 3 == 0) {
					echo '<div class="clear"></div>';
				}
				
				endwhile;
			} else {
				echo "<h2>Sorry there is currently no albums. Check back soon!</h2>";
			}
		?>

		<div class="clear"></div>		
		
		<h2 class="heading">VIDEO GALLERY</h2>
		
		<?php
			$sql = "SELECT * FROM videos ORDER BY sort DESC";
			$query = mysql_query($sql) or die(mysql_error());
			$check = mysql_num_rows($query);
			$count = 0;
			
			if($check) {
				while($row = mysql_fetch_assoc($query)) :
					$count ++;
		?>
		
			<div class="palbum_cover <?=$count % 3 == 0 ? 'last' : 'first';?>">
				<?php 
					$video = stripslashes($row['embed_code']);
					$output = preg_replace('/width="(\d+)"/', 'width="280px"', $video);
					$output = preg_replace('/height="(\d+)"/', 'height="275px"', $output);
					$src = preg_match('/src="http:\/\/www\.youtube\.com\/embed\/[a-zA-Z0-9._-]+"/', $video, $matches);
					$matches[0] = preg_replace('/src="[a-zA-Z0-9._-]+"/', '', $matches[0]);
					$matches[0] = str_replace("src=\"http://www.youtube.com/embed/", "", $matches[0]);
					$matches[0] = str_replace("\"", "", $matches[0]);
				?>
				<a class="fancy_video" href="http://www.youtube.com/watch?v=<?=$matches[0];?>&feature=player_embedded">
					<img src="http://img.youtube.com/vi/<?=$matches[0];?>/0.jpg" />
				</a>
				<p><?=strlen($row['name']) >= 26 ? substr(trim($row['name']), 0, 23)."..." : $row['name'];?></p>
			</div>
		<?php
				if($count % 3 == 0) {
					echo '<div class="clear"></div>';
				}
				
				endwhile;
			} else {
				echo "<h2>Sorry there is currently no albums. Check back soon!</h2>";
			}
		?>
		
	</div>

</div><!-- end leftCntr -->

<script type="text/javascript">
$(document).ready(function() {
	$(".fancy_video").click(function() {
		$.fancybox({
				'padding'		: 0,
				'autoScale'		: false,
				'transitionIn'	: 'none',
				'transitionOut'	: 'none',
				'title'			: this.title,
				'width'		: 680,
				'height'		: 495,
				'href'			: this.href.replace(new RegExp("watch\\?v=", "i"), 'v/'),
				'type'			: 'swf',
				'swf'			: {
				   	 'wmode'		: 'transparent',
					'allowfullscreen'	: 'true'
				}
			});
	
		return false;
	});
	
	$(".media").delegate('.palbum_cover', 'click', function() {
		var $link = $(this).find('a').attr('href');
		window.location = $link;
	});
});
</script>
				
<? include('./includes/sidebar.php'); ?>

<? include('./includes/footer.php'); ?>