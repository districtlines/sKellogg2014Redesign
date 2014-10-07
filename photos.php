<? $active = 'home'; ?>
<? $curr_page = 'media'; ?>
<? $pageTitle = 'Photos'; ?>
<? $album_id = $_GET['id']; ?>
<? include('./includes/header.php'); ?>

	<div class="media">
	
		<h2 class="heading header_left">PHOTO ALBUMS</h2>
		
		<a class="back_to_albums button_right" href="<?= ROOT ?>/media">Back to Albums</a>
		
		<div class="clear"></div>
		
		<?php
			$sql = "SELECT * FROM photos WHERE album_id = ".$album_id." ORDER BY sort ASC";
			$query = mysql_query($sql) or die(mysql_error());
			$check = mysql_num_rows($query);
			$count = 0;
			
			if($check) {
				while($row = mysql_fetch_assoc($query)) :
					$count ++;
					
		?>
		
			<div class="palbum_cover <?=$count % 3 == 0 ? 'last' : 'first';?> single_photo">
				<a href="<?=ROOT?>/uploads/photos/<?=$row['id'];?>/<?=str_replace('full_', '', $row['photo']);?>" title="<?=$row['name']?>" rel="group" >
					<img src="<?=ROOT?>/uploads/photos/<?=$row['id'];?>/<?=$row['thumbnail'];?>" />
				</a>
				<? /*<p><?=strlen($row['name']) >= 17 ? substr($row['name'], 0, 16)."..." : $row['name'];?></p> */?>
			</div>
		<?php
				if($count % 3 == 0) {
					echo '<div class="clear"></div>';
				}
				
				endwhile;
				
				if ($count % 3 != 0) {
					echo '<div class="clear"></div>';
				}
				echo "<a class=\"back_to_albums\" href=\"". ROOT . '/media' ."\">Back to Albums</a>";
				
			} else {
				echo "<h2>Sorry there is currently no photos. Check back soon!</h2>";
			}
		?>
		
	</div>

</div><!-- end leftCntr -->

<script type="text/javascript">
$(document).ready(function() {
	$(".palbum_cover a").fancybox();
});
</script>
				
<? include('./includes/sidebar.php'); ?>

<? include('./includes/footer.php'); ?>