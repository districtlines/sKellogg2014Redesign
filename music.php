<?php 
	$curr_page = 'music';
	$pageTitle = 'Music'; 
	$active = 'home'; 
	$layout = 'large-left-col';
	include('./includes/header.php');
?>
	<h2 class="page-title">MUSIC //</h2>
	<div class="music-page">
		
		<?php
						
			$sql = "SELECT * FROM music WHERE show_date <= NOW() ORDER BY release_date DESC";
			$query = mysql_query($sql) or die(mysql_error());
			$check = mysql_num_rows($query);
			
			if($check) {
				while($row = mysql_fetch_assoc($query)) :
				
		?>
		
		<div id="album-<?=$row['id']?>" class="album">
			<div class="album_art">
				<img width="300px" src="<?= ROOT ?>/uploads/music/<?=$row['id'];?>/thumb_<?=$row['album_cover'];?>" />
				
			</div>
			
			<div class="tracks">
				<h2 class="album_name"><?=$row['name'];?></h2>
				<span class="release_date">Release Date: <?= date('m/d/Y', strtotime($row['release_date']));?></span>
				
				<div class="clear"></div>
				<?php
					if($row['download_link']) {
				?>
				
				<a class="btn btn-sm btn-default" href="<?=$row['download_link'];?>" target="_blank">Download</a>
				
				<?php
					}
				?>
				
				<?php
					if($row['itunes_link']) {
				?>
				
				<a class="btn btn-sm btn-default" href="<?=$row['itunes_link'];?>" target="_blank">iTunes</a>
				
				<?php
					}
					$sqlTracks = "SELECT * FROM tracks WHERE album_id = ".$row['id']." ORDER BY sort DESC";
					$queryTracks = mysql_query($sqlTracks) or die(mysql_error());
					$checkTracks = mysql_numrows($queryTracks);
					
					$track_array = array();
					
					if($checkTracks) {
						
						echo "<a class=\"btn btn-sm btn-default\" href=\"listen-". $row['id'] ."\" class=\"listen-link\">Listen</a>";
						
						echo "<div class=\"clear\"></div>";
					
						while($tracks = mysql_fetch_assoc($queryTracks)) {
							$track_array[] = array(
								'name' => stripslashes($tracks['name']),
								'embed_code' => $tracks['embed_code']
							);
						} //endwhile $tracks = mysql....
					?>

						<div id="tracks-<?= $row['id'] ?>">
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
						<div id="listen-<?= $row['id'] ?>" style="display: none;">
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
				endwhile;
			} else {
				echo "<h2>Sorry there is currently no albums. Check back soon!</h2>";
			}
		?>
	</div>

</div><!-- end leftCntr -->

<script type="text/javascript">

	$(document).ready(function(){
	
		$('.listen_btn').click(function(){
		
			var id = $(this).attr('href').substr(7);
			
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