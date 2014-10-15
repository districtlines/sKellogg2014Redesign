<?php 
	$curr_page = 'music';
	$pageTitle = 'Music'; 
	$active = 'home'; 
	$layout = 'large-left-col';
	include('./includes/header.php');
?>
<style type="text/css">
	body {
		background: url("/images/recordcollection.jpg") no-repeat fixed center center;
		background-size: cover; 

	}
</style>

	<h2 class="page-title">MUSIC //</h2>
	<div class="music-page">
		
		<?php
						
			$all_music = $SQL->fetchAssoc("SELECT * FROM music WHERE show_date <= NOW() ORDER BY release_date DESC");
			$check = count($all_music);
			if($check) {
				foreach($all_music as $music):				
		?>
		
		<div id="album-<?=$music['id']?>" class="album">
			<div class="album_art">
				<img width="300px" src="<?= ROOT ?>/uploads/music/<?=$music['id'];?>/thumb_<?=$music['album_cover'];?>" />
				
			</div>
			
			<div class="tracks">
				<h2 class="album_name"><?=$music['name'];?></h2>
				<span class="release_date">Release Date: <?= date('m/d/Y', strtotime($music['release_date']));?></span>
				
				<div class="clear"></div>
				<?php
					if($music['download_link']) {
				?>
				
				<a class="btn btn-sm btn-default" href="<?=$music['download_link'];?>" target="_blank">Download</a>
				
				<?php
					}
				?>
				
				<?php
					if($music['itunes_link']) {
				?>
				
				<a class="btn btn-sm btn-default" href="<?=$music['itunes_link'];?>" target="_blank">iTunes</a>
				
				<?php
					}
					$Tracks = $SQL->fetchAssoc("SELECT * FROM tracks WHERE album_id = ".$music['id']." ORDER BY sort DESC");
					//$queryTracks = mysql_query($sqlTracks) or die(mysql_error());
					$checkTracks = count($Tracks);
					
					$track_array = array();
					
					if($checkTracks) {
						
						echo "<a class=\"btn btn-sm btn-default listen-btn \" data-listen_id=\"listen-". $music['id'] ."\">Listen</a>";
						
						echo "<div class=\"clear\"></div>";
					
						foreach($Tracks as $tracks) {
							$track_array[] = array(
								'name' => stripslashes($tracks['name']),
								'embed_code' => $tracks['embed_code']
							);
						} //endwhile $tracks = mysql....
					?>

						<div id="tracks-<?= $music['id'] ?>">
							<ul>
						<?php		
							foreach ($track_array AS $track) {
						?>
								<li><?= $track['name'] ?></li>
						
						<?php
							}
						?>
							</ul>
						</div>
						<div id="listen-<?= $music['id'] ?>" style="display: none;">
							<ul>
							<?php
							foreach ($track_array AS $track) {
							?>
								<li><?= $track['embed_code'] ?></li>
							<?php
							}
							?>
							</ul>
						</div>
				<?php
					} else { //endif $checkTracks
					
						echo "<div class=\"clear\"></div>";
					
					}
				?>
				
			</div>
			
			<div class="clear"></div>
			
		</div>
		
		<?php
				endforeach;
			} else {
				echo "<h2>Sorry there is currently no albums. Check back soon!</h2>";
			}
		?>
	</div>

</div><!-- end leftCntr -->

<script type="text/javascript">

	$(document).ready(function(){
	
		$('.listen-btn').click(function(){
			
			var id = $(this).data('listen_id').substr(7);
						
			if ($('#listen-'+id).css('display') == 'none') {
		
				$('#tracks-'+id).hide();
			
				$('#listen-'+id).show();
			
			} else {
			
				$('#tracks-'+id).show();
				
				$('#listen-'+id).hide();
			
			}
		
			return false;
		
		});
	
	});

</script>


<? include('./includes/footer.php'); ?>