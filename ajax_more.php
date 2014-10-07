<?php
$author_imgs = array(
	
		4 => 'book',
		5 => 'music',
		6 => 'camera',
		7 => 'beer',
		8 => 'food'
	
	);
include("includes/config.php");
if(isset($_POST['lastpost']))
{
	$lastpost = date('Y-m-d H:i:s', $_POST['lastpost']);
	
	$lastpost = mysql_real_escape_string($lastpost);
	
	//SET LIMIT HERE
	$ajaxLimit = 6;
	$sql = "select * from recent_activity where created < '$lastpost' AND sticky='0' AND type <> 'twitter' order by created desc limit 50";
	$query = mysql_query($sql);

	$check = mysql_num_rows($query);
	if($check)
	{
		$count = 1;
						
		while($row=mysql_fetch_array($query))
		{	
			
				$end = "";
				
				if($wallType == "photo")
				{
					$photoCount ++;
					if($photoCount >= 3)
					{
						$photoCount = 3;
					}
				}else{
					$photoCount = 1;
				}
				
				
				
				if($count <= $ajaxLimit) {
					
					$recent_id = $row['id'];
					$wallType = $row['type'];
					$wallID = $row['type_id'];
					$createdDate = $row['created'];
					$wallDate = date('M d, Y',strtotime($row['created']));
					
					if (is_array($wallList)) {
						$end = end($wallList);
					}
					
					$wallList[] = $wallType;
					
					if($wallType == "photo")
					{
						
						if($photoCount <= 2)
						{
						
							$sqlSticky = "SELECT * FROM photos WHERE id = '" . $wallID . "' AND sticky='0'";
							$querySticky = mysql_query($sqlSticky);
							$checkSticky = mysql_num_rows($querySticky);
							
							if($checkSticky)
							{
								while($rowSticky = mysql_fetch_assoc($querySticky))
								{
									$count ++;
									$sqlPhoto = "SELECT gallery_id FROM photos WHERE id='$wallID'";
									$queryPhoto = mysql_query($sqlPhoto);
									while($rowPhoto = mysql_fetch_assoc($queryPhoto))
									{
										$galleryID = $rowPhoto['gallery_id'];
									}
									$link_url = ROOT . "/photos/$galleryID";
								?>
								<div class="textBox">
									
									<div class="left">
										<!-- <img alt="" src="<?= ROOT?>/images/post_photos.png" /> -->
										<i></i>
									</div>
									<div class="right">
																			
										<a href="<?=$link_url?>" class="post">POSTED ON <?=$wallDate?> VIA PHOTOS </a>
										
										<div class="link">
										
											<a class="twittericon" href="http://twitter.com/share?url=<?=$link_url?>&text=<?=$row['title']?>"><span>Tweet</span></a>
											
											<a class="facebookicon" href="http://www.facebook.com/sharer.php?u=<?=$link_url?>"><span>Share</span></a>
											
										</div>
										
										</div>
									</div>	
										<div class="clear"></div>
									
									
									
									<?
									$wallTypeID = $row['type_id'];
									$sql_photo = "SELECT * FROM photos WHERE id='$wallTypeID'";
									$query_photo = mysql_query($sql_photo);
									$check_photo = mysql_num_rows($query_photo);
									$row_photo = mysql_fetch_assoc($query_photo);
									?>
									
									<a href="<?=$link_url?>">
										<img src="<?=ROOT?>/uploads/photos/<?=$row_photo['id']?>/<?=$row_photo['thumbnail']?>" alt="<?=$row['title']?>" />
									</a>
									
									
									
								
								</div>
								<?
								}
								
							}
							
						}else{
							
						}
						
					}
					if($wallType == "blog")
					{
						
						$sqlSticky = "SELECT * FROM blog WHERE id = '$wallID' AND show_date <= NOW() AND sticky = '0'";
						//echo $sqlSticky;
						$querySticky = mysql_query($sqlSticky);
						$checkSticky = mysql_num_rows($querySticky);
						
		
						
						if($checkSticky)
						{
							while($rowSticky = mysql_fetch_assoc($querySticky))
							{
								$count ++;
								$link_url = ROOT . "/blog/$wallID";
					?> 
		
						<div class="textBox blog">
			
							
							
							<div class="left">
										<!-- <img alt="" src="<?=ROOT?>/images/<?=$author_imgs[$rowSticky['author']]?>_icon.png" /> -->
										<i></i>
									</div>
									<div class="right">
								
								<a href="<?=$link_url?>"><?=$row['title']?></a>
									<p><?=strip_tags($rowSticky['summary'])?>...</p>
								<a href="<?=$link_url?>" class="post">POSTED ON <?=$wallDate?> VIA BLOG </a>
								
								
								
								<div class="link">
								
									<a class="twittericon" href="http://twitter.com/share?url=<?=$link_url?>&text=<?=$row['title']?>"><span>Tweet</span></a>
								
									<a class="facebookicon" href="http://www.facebook.com/sharer.php?u=<?=$link_url?>"><span>Share</span></a>
									
								</div>
								
														
							</div>
												
							<div class="clear"></div>
						
						</div>
					<?
							}
						}
					}
					if($wallType == "events")
					{
						
						$sqlSticky = "SELECT * FROM events WHERE id = $wallID AND show_date <= NOW() AND sticky = 0 AND showing = 1";
						$querySticky = mysql_query($sqlSticky);
						$checkSticky = mysql_num_rows($querySticky);
						
						if($checkSticky)
						{
							while($rowSticky = mysql_fetch_assoc($querySticky))
							{
					?>
						
						<?	
							$count ++;
							$sql_events = "SELECT * FROM events WHERE id=$wallID AND show_date <= NOW() AND sticky = 0 AND showing = 1";
							$query_events = mysql_query($sql_events);
							$check_events = mysql_num_rows($query_events);
							$row_events = mysql_fetch_assoc($query_events);
							$events_date = date('m/d',strtotime($row_events['date']));
							
							$link_url = ROOT . "events/#events-$wallID";
							$title = "Stephen Kellogg and The Sixers is Playing at ". $row_events['venue'] . " in " . $row_events['city'] . " on ". $events_date. ".";
						?>
						<div class="textBox">
			
							
							
							<div class="left">
								<!-- <img alt="" src="<?=ROOT?>/images/post_tours.png" /> -->
								<i></i>
							</div>
								
							<div class="right">
								
								<a href="<?=$link_url?>"><?=$title?></a>
								
								<p><?=strip_tags($row_events['details'],'<p><i><em><div><li><strong><b><u><a><br><br /><embed><iframe><object><code><pre><h3><h2><h1><h4><h5>')?></p>
																
								<a href="<?=$link_url?>" class="post">POSTED ON <?=$wallDate?> VIA events </a>
								
								
								<div class="link">
								
									<a class="twittericon" href="http://twitter.com/share?url=<?=$link_url?>&text=<?=$title?>"><span>Tweet</span></a>
								
									<a class="facebookicon" href="http://www.facebook.com/sharer.php?u=<?=$link_url?>"><span>Share</span></a>
									
								</div>
								
								<div class="clear"></div>
							
							</div>

							<div class="clear"></div>
							
						</div>
						
						
						
					<?
							}
						}
					}
					if($wallType == "news")
					{
						
						$sqlSticky = "SELECT * FROM news WHERE id = '" . $wallID . "' AND show_date <= NOW() AND sticky = '0'";
						$querySticky = mysql_query($sqlSticky);
						$checkSticky = mysql_num_rows($querySticky);
						
						if($checkSticky)
						{
							
							while($rowSticky = mysql_fetch_assoc($querySticky))
							{
							$count ++;
							$sql_news = "SELECT * FROM news WHERE id = '$wallID'";
							$query_news = mysql_query($sql_news);
							$check_news = mysql_num_rows($query_news);
							$row_news = mysql_fetch_assoc($query_news);
							
							$link_url = ROOT . "/news/$wallID";
							$title = "Stephen Kellogg and The Sixers site news was updated.";
						?>
		
						
							<div class="textBox news">
			
								
								
								<div class="left">
										<!-- <img alt="" src="<?=ROOT?>/images/post_news.png" /> -->
										<i></i>
									</div>
									<div class="right">
									
									<a href="<?=$link_url?>"><?=$row['title']?></a>
									
									<p>
										<? if($rowSticky['id'] <= 176) { ?>
										<?=strip_tags(str_replace('scrape', ROOT . '/scrape',$row_news['summary']),'<img><br><a><p><br /><br/><b><em><i><u><strong>')?>
										
										<? } else {
														
											echo strip_tags($row_news['summary'],'<img><br><a><p><br /><br/><b><em><i><u><strong>');
											
										} ?>
									</p>
												
									<a href="<?=$link_url?>" class="post">POSTED ON <?=$wallDate?> VIA NEWS </a>
									
									<div class="link">
									
										<a class="twittericon" href="http://twitter.com/share?url=<?=$link_url?>&text=<?=$title?>"><span>Tweet</span></a>
								
										<a class="facebookicon" href="http://www.facebook.com/sharer.php?u=<?=$link_url?>"><span>Share</span></a>
										
									</div>
									
									
								
								</div>
								
								
								<!--
<p><? /* echo strip_tags(trim(substr($row['content'], 0, 255)),'<p><i><em><div><li><strong><b><u><a><br><br /><embed><iframe><object><code><pre><h3><h2><h1><h4><h5>') */?>
								<?php
									/*
if(strlen($row['content']) > 255) {
										echo "...";
									}
*/
								?>
								</p>
-->
								
								
							<div class="clear"></div>
							</div>
							
							
						<?
							}
						}
					}
					if($wallType == "video")
					{	
						$sqlSticky = "SELECT * FROM video WHERE id = '" . $wallID . "' AND sticky = '0'";
						$querySticky = mysql_query($sqlSticky);
						$checkSticky = mysql_num_rows($querySticky);
						
						if($checkSticky)
						{
							while($rowSticky = mysql_fetch_assoc($querySticky))
							{
								$count ++;
								$sql_vids = "SELECT * FROM videos WHERE id = '$wallID'";
								$query_vids = mysql_query($sql_vids);
								$check_vids = mysql_num_rows($query_vids);
								$row_vids = mysql_fetch_assoc($query_vids);
								
								
								$video = stripslashes($row_vids['embed_code']);
								$output = preg_replace('/width="(\d+)"/', 'width="100%"', $video);
								$output = preg_replace('/height="(\d+)"/', 'height="350"', $output);
								
								$title = "Stephen Kellogg and The Sixers Posted a video!";
								$link_url = ROOT . "/media/#videos";
					?>
						<div class="textBox videos">
							
							<div class="left">
										<!-- <img alt="" src="<?=ROOT?>/images/post_videos.png" /> -->
										<i></i>
									</div>
									<div class="right">
										<?=$output?>					
								<a href="<?=$link_url?>" class="post">POSTED ON <?=$wallDate?> VIA VIDEOS </a>
								
								<div class="link">
								
									<a class="twittericon" href="http://twitter.com/share?url=<?=$link_url?>&text=<?=$title?>"><span>Tweet</span></a>
								
									<a class="facebookicon" href="http://www.facebook.com/sharer.php?u=<?=$link_url?>"><span>Share</span></a>
									
								</div>
								
								<div class="clear"></div>
								
							</div>

							
							<div class="clear"></div>
						
						</div>
					<?
							}
						}
					}
					if($wallType == "twitter")
					{
						$count ++;
						$link_url = 'http://twitter.com/SK6ers';
					?>
						<div class="textBox twitter">
							
							<div class="left">
										<!-- <img alt="" src="<?=ROOT?>/images/post_twitter.png" /> -->
										<i></i>
									</div>
									<div class="right">
									<p><?=$row['content']?></p>				
								<a href="<?=$link_url?>" class="post">POSTED ON <?=$wallDate?> VIA TWITTER </a>
								
								<div class="link">
									<?php
										$twitterContent = '@sk6ers - ' . $row['content'];
									?>
									<a class="twittericon" href="http://twitter.com/share?url=<?=$link_url?>&text=<?=$twitterContent?>"><span>Tweet</span></a>
								
									<a class="facebookicon" href="http://www.facebook.com/sharer.php?u=http://www.stephenkellogg.com"><span>Share</span></a>
									
								</div>
								
								<div class="clear"></div>
								
							</div>
							
							<div class="clear"></div>
							
						</div>
					<?
					}
				}
				
			}
	} else {
		$end = true;
	}
?>



<?php
if(!$end) {
?>

<div id="more-<?php echo strtotime($createdDate); ?>" class="morebox">
	<a href="#" class="older_posts" id="<?php echo $createdDate ?>" class="more"><span>View More Posts</span></a>
</div>
<?
}else{
	echo '<div class="clear"></div>';
	echo "<h2>No more posts...</a>";
}
?>

<?php
}
?> 