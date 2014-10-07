<?
	session_start();
	$active = 'home';
	$curr_page = 'home';
	$today = mktime();
	
	$author_imgs = array(
	
		4 => 'book',
		5 => 'music',
		6 => 'camera',
		7 => 'beer',
		8 => 'food'
	
	);
?>

<? include('./includes/header.php'); ?>
<div class="home_page">
<?
	?>
					
					
					<?php
					//tests
					/*
$sql = "SELECT * FROM recent_activity";
					$query = mysql_query($sql);
					$num = mysql_num_rows($query);
					echo $num;
*/
					
					//STICKY
					
					$stickyCount = 0;
					
					$sql = "SELECT * FROM stickies ORDER BY sort ASC";
					$query = mysql_query($sql);
					$check = mysql_num_rows($query);
					
					if($check)
					{
						$stickyCount = $check;
					}
					
					if($check)
					{
						$i = 1;
						while($row = mysql_fetch_assoc($query))
						{
/* 							Print_r( $row) ; */
							$wallType = $row['type'];
							$wallID = $row['type_id'];
/* 							echo $wallType; */
							$wallDate = date('M d, Y',strtotime($row['modified']));
							$createdDate = $row['created'];
							if($i == 1) {
								$cd = $createdDate;
							}
							$i++;
							if($wallType == "photos")
							{
								$sqlSticky = "SELECT * FROM photos WHERE id = '" . $wallID . "' AND sticky = 1  AND showing = 1";
								$querySticky = mysql_query($sqlSticky);
								$checkSticky = mysql_num_rows($querySticky);
								
								if($checkSticky)
								{
									while($rowSticky = mysql_fetch_assoc($querySticky))
									{
										$count ++;
										$sqlPhoto = "SELECT album_id FROM photos WHERE id='$wallID'";
										$queryPhoto = mysql_query($sqlPhoto);
										while($rowPhoto = mysql_fetch_assoc($queryPhoto))
										{
											$galleryID = $rowPhoto['album_id'];
										}
										$link_url = ROOT . "/photos/$galleryID";
									?>
									<div class="textBox">
										
										<div class="left">
											<!--<img alt="" src="<?=ROOT ?>/images/post_photos.png" />--><i></i>
										</div><!--end .left-->
										
										<div class="right">

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
											
											
											<a href="<?=$link_url?>" class="post">POSTED ON <?=$wallDate?> VIA PHOTOS </a>
											
											
											<div class="link">
												
												<a class="twittericon" href="http://twitter.com/share?url=<?=$link_url?>&text=<?=urlencode('@Stephen_Kellogg ' . $row['title']);?>">Tweet</a>
												
												<a class="facebookicon" href="http://www.facebook.com/sharer.php?u=<?=$link_url?>">Share</a>
												
											</div>
											
											<div class="clear"></div>
										
										
										
										
										
										
										</div>
										<div class="clear"></div>
									</div>
									
									<?	
									}
								} else {
								
									$stickyCount--;
									
								}
							}
							if($wallType == "blog")
								{
								$sql_blog = "SELECT * FROM blog WHERE id = '$wallID' AND show_date <= NOW() AND sticky = 1 AND showing = 1";
								$query_blog = mysql_query($sql_blog);
								$check_blog = mysql_num_rows($query_blog);
								if($check_blog) {
								while($row_blog = mysql_fetch_assoc($query_blog)) {
									
									$link_url = ROOT . "/blog/$wallID";
								?> 
					
									<div class="textBox">
										
										<div class="left">
											<!-- <img src="<?=ROOT?>/images/<?=$author_imgs[$row_blog['author']]?>_icon.png" /> --><i></i>
										</div><!--end .left-->
										
										<div class="right">
									
											<a href="<?=$link_url?>"><?=$row['title']?></a>
							

											<p>
												<?=strip_tags(nl2br($row_blog['summary']))?>
											</p>
											
											<a href="<?=$link_url?>" class="post">POSTED ON <?=$wallDate?>  VIA BLOG </a>
											
											<div class="link">
												<a class="twittericon" href="http://twitter.com/share?url=<?=$link_url?>&text=<?=$row['title']?>">Tweet</a>
											
												<a class="facebookicon" href="http://www.facebook.com/sharer.php?u=<?=$link_url?>">Share</a>
												
											</div>
											
											<div class="clear"></div>
										
										</div>
										
										<div class="clear"></div>
									</div>
									
								<?
									}
								} else {
									
									$stickyCount--;
								
								}
								}
								if($wallType == "events")
								{
									
								?>
									
									<?
										$sql_tour = "SELECT * FROM events WHERE id='$wallID' AND show_date <= NOW() AND sticky = 1 AND showing = 1";
										$query_tour = mysql_query($sql_tour);
										$check_tour = mysql_num_rows($query_tour);
										
										$check_tour = false;
										if($check_tour) {
										$row_tour = mysql_fetch_assoc($query_tour);
										$tour_date = date('m/d',$row_tour['date']);
										
										$link_url = ROOT . "/events/#events-$wallID";
										$title = urlencode('@Stephen_Kellogg ' . "Event added at ". $row_tour['venue'] . " in " . $row_tour['city'] . " on ". $tour_date. ".");
									?>
									<div class="textBox">

										<div class="left">
											<!--<img alt="" src="<?=ROOT ?>/images/post_tours.png" />--><i></i>
										</div><!--end .left-->
										
										<div class="right">
						
										<a href="<?=$link_url?>"><?=$row_tour['wall_name']?></a>
										
										
											<p><?=$title?></p>
											
											<a href="<?=$link_url?>" class="post">POSTED ON <?=$wallDate?> VIA EVENTS </a>
																					
											
											
											<div class="link">
												
												<a class="twittericon" href="http://twitter.com/share?url=<?=$link_url?>&text=<?=$title?>">Tweet</a>
											
												<a class="facebookicon" href="http://www.facebook.com/sharer.php?u=<?=$link_url?>">Share</a>
												
											</div>
											
											<div class="clear"></div>
										
										
										
										
										
										</div>										
										<div class="clear"></div>
									</div>
									
									
									
									
								<?
									} else {
									
										$stickyCount--;
									
									}
									}
								if($wallType == "news")
								{
										
										$sql_news = "SELECT * FROM news WHERE id = '$wallID' AND show_date <= NOW() AND sticky = 1 AND showing = 1 AND date <= $today";
										$query_news = mysql_query($sql_news);
										$check_news = mysql_num_rows($query_news);
										if($check_news){
										$row_news = mysql_fetch_assoc($query_news);
										
										$link_url = ROOT . "/news/$wallID";
										$title = urlencode('@Stephen_Kellogg ' . $row['title']);
									?>
					
									
										<div class="textBox">
											
											<div class="left">
												<!--<img alt="" src="<?=ROOT ?>/images/post_news.png" />--><i></i>
											</div><!--end .left-->
										
											<div class="right">
						
											<a href="<?=$link_url?>"><?=$row['title']?></a>
											
											
												<p>
													<? if($row_news['id'] <= 176) { ?>
													<?=strip_tags(str_replace('scrape', ROOT . '/scrape',$row_news['summary']),'<img><br><a><p><br /><br/><b><em><i><u><strong>')?>
													<? } else {
														
														echo strip_tags($row_news['summary'],'<img><br><a><p><br /><br/><b><em><i><u><strong>');
														
													} ?>
												</p>
												
												<a href="<?=$link_url?>" class="post">POSTED ON <?=date('M j, Y',$row_news['date'])?> VIA NEWS </a>
												
												<div class="link">
												
													<a class="twittericon" href="http://twitter.com/share?url=<?=$link_url?>&text=<?=$title?>">Tweet</a>
											
													<a class="facebookicon" href="http://www.facebook.com/sharer.php?u=<?=$link_url?>">Share</a>
													
												</div>
												
												<div class="clear"></div>
											
											
											
											
																					
											</div>
											<div class="clear"></div>
										</div>
										
									
									<?
									} else {
										
										$stickyCount--;
									
									}
									}
									
								
								if($wallType == "videos")
								{
											
											$sql_vids = "SELECT * FROM videos WHERE id = '$wallID' AND sticky = 1 AND showing = 1";
											$query_vids = mysql_query($sql_vids);
											$check_vids = mysql_num_rows($query_vids);
											
											if($check_vids){
											$row_vids = mysql_fetch_assoc($query_vids);
											
											$video = stripslashes($row_vids['embed_code']);
											$output = preg_replace('/width="(\d+)"/', 'width="'.$vid_width.'"', $video);
											$output = preg_replace('/height="(\d+)"/', 'height="350"', $output);
											
											
											$title = urlencode('@Stephen_Kellogg ' . $row['title']);
											
											$link_url = ROOT . "/media/#videos";
								?>
									<div class="textBox">

											<div class="left">
												<!-- <img alt="" src="<?= ROOT?>/images/post_videos.png" /> -->
												<i></i>
											</div><!--end .left-->
											
											<div class="right">
																				
											<?=$output?>
											
											<a href="<?=$link_url?>" class="post">POSTED ON <?=$wallDate?> VIA VIDEOS </a>
											
											
											
											<div class="link">
												
												<a class="twittericon" href="http://twitter.com/share?url=<?=$link_url?>&text=<?=$title?>">Tweet</a>
											
												<a class="facebookicon" href="http://www.facebook.com/sharer.php?u=<?=$link_url?>">Share</a>
												
											</div>
											
											<div class="clear"></div>
																
										
										
										</div>
										<div class="clear"></div>
									</div>
									
								<?
										
						} else {
						
							$stickyCount--;
						
						}
					}
					}
					}
					
					//SET LIMIT HERE
					$limit = 20;
					$limit = ($limit - $stickyCount);
					$sql="select * from recent_activity WHERE sticky=0 AND type <> 'twitter' ORDER BY created DESC LIMIT 50";
					$query = mysql_query($sql);
					$check = mysql_num_rows($query);
					if($check)
					{
					
						$count = 1;
						
						while($row=mysql_fetch_array($query))
						{	
							
								$end = "";
								
								if($wallType == "photos")
								{
									$photoCount ++;
									if($photoCount >= 3)
									{
										$photoCount = 3;
									}
								}else{
									$photoCount = 1;
								}
								
								if($count <= $limit) {
									
									$recent_id = $row['id'];
									$wallType = $row['type'];
									$wallID = $row['type_id'];
									$createdDate = $row['created'];
									$wallDate = date('M d, Y',strtotime($row['created']));
									$cd = $createdDate;
									if (is_array($wallList)) {
										$end = end($wallList);
									}
									
									$wallList[] = $wallType;
									
									if($wallType == "photos")
									{
										if($photoCount <= 2)
										{
										
											$sqlSticky = "SELECT * FROM photos WHERE id = '" . $wallID . "' AND sticky = 0 AND showing = 1";
											$querySticky = mysql_query($sqlSticky);
											$checkSticky = mysql_num_rows($querySticky);
											
											if($checkSticky)
											{
												while($rowSticky = mysql_fetch_assoc($querySticky))
												{
													$count ++;
													$sqlPhoto = "SELECT album_id FROM photos WHERE id='$wallID'";
													$queryPhoto = mysql_query($sqlPhoto);
													while($rowPhoto = mysql_fetch_assoc($queryPhoto))
													{
														$galleryID = $rowPhoto['album_id'];
													}
													$link_url = ROOT . "/photos/$galleryID";
												?>
												<div class="textBox">
													
													<div class="left">
														<!--<img alt="" src="<?=ROOT ?>/images/post_photos.png" />--><i></i>
													</div><!--end .left-->
													
													<div class="right">
														
														<?
													$wallTypeID = $row['type_id'];
													$sql_photo = "SELECT * FROM photos WHERE id='$wallTypeID' AND sticky = 0 AND showing = 1";
													$query_photo = mysql_query($sql_photo);
													$check_photo = mysql_num_rows($query_photo);
													$row_photo = mysql_fetch_assoc($query_photo);
													?>
													
													<a href="<?=$link_url?>">
														<img src="<?=ROOT?>/uploads/photos/<?=$row_photo['id']?>/<?=$row_photo['thumbnail']?>" alt="<?=$row['title']?>" />
													</a>
														
														
														<a href="<?=$link_url?>" class="post">POSTED ON <?=$wallDate?> VIA PHOTOS </a>
														
														
														
														
														<div class="link">
														
															<a class="twittericon" href="http://twitter.com/share?url=<?=$link_url?>&text=<?=urlencode('@Stephen_Kellogg ' . $row['title']);?>">Tweet</a>
															
															<a class="facebookicon" href="http://www.facebook.com/sharer.php?u=<?=$link_url?>">Share</a>
															
														</div>
														
														<div class="clear"></div>
													
													
													
													
													</div>
													<div class="clear"></div>
												</div>
												
												<?
												}
												
											}
											
										}else{
											
										}
										
									}
									if($wallType == "blog")
									{
										
										$sqlSticky = "SELECT * FROM blog WHERE id = '$wallID AND show_date <= NOW()' AND sticky = 0 AND showing = 1";
										$querySticky = mysql_query($sqlSticky);
										$checkSticky = mysql_num_rows($querySticky);
										
	
										
										if($checkSticky)
										{
											while($rowSticky = mysql_fetch_assoc($querySticky))
											{	
												$author = $rowSticky['author'];
												$author_sql = "SELECT name FROM blog_authors WHERE id = $author LIMIT 1";
												$author_query = mysql_query($author_sql);
												$author = ucwords(mysql_result($author_query,0));
												$count ++;
												$link_url = ROOT . "/blog/$wallID";
									?> 
						
										<div class="textBox">
											
											<div class="left">
												<!-- <img alt="" src="<?=ROOT?>/images/<?=$author_imgs[$rowSticky['author']]?>_icon.png" /> -->
												<i></i>
											</div><!--end .left-->
											
											<div class="right">
							
											<a href="<?=$link_url?>"><?=$row['title']?></a>
																		
												<p>
													<?=strip_tags($rowSticky['summary'])?>
												</p>
												
												<a href="<?=$link_url?>" class="post">POSTED ON <?=$wallDate?>  VIA BLOG </a>
												
												<div class="link">
												
													<a class="twittericon" href="http://twitter.com/share?url=<?=$link_url?>&text=<?=$row['title']?>">Tweet</a>
												
													<a class="facebookicon" href="http://www.facebook.com/sharer.php?u=<?=$link_url?>">Share</a>
													
												</div>
												
												<div class="clear"></div>
											
											
											
											
											</div>
											<div class="clear"></div>
										</div>
										
									<?
											}
										}
									}
									if($wallType == "events")
									{
										
										$sqlSticky = "SELECT * FROM events WHERE id = '$wallID' AND show_date <= NOW() AND sticky = 0 AND showing = 1";
										$querySticky = mysql_query($sqlSticky);
										$checkSticky = mysql_num_rows($querySticky);
										
										if($checkSticky)
										{
											while($rowSticky = mysql_fetch_assoc($querySticky))
											{
									?>
										
										<?	
											$count ++;
											$sql_tour = "SELECT * FROM events WHERE id='$wallID' AND sticky = 0 AND showing = 1";
											
											$query_tour = mysql_query($sql_tour);
											$check_tour = mysql_num_rows($query_tour);
											$row_tour = mysql_fetch_assoc($query_tour);
											$tour_date = date('m/d',$row_tour['date']);
											
											$link_url = ROOT . '/events';
											$title = urlencode('@Stephen_Kellogg ' . "Event added at ". $row_tour['venue'] . " in " . $row_tour['city'] . " on ". $tour_date. ".");
										?>
										<div class="textBox">

											<div class="left">
												<!--<img alt="" src="<?=ROOT ?>/images/post_tours.png" />--><i></i>
											</div><!--end .left-->
											
											<div class="right">
							
											<a href="<?=$link_url?>/#events-<?=$wallID?>"><?=$title?></a>									
												
												
												<a href="<?=$link_url?>/#events-<?=$wallID?>" class="post">POSTED ON <?=$wallDate?> VIA EVENTS </a>
												
												<div class="link">
												
													<a class="twittericon" href="http://twitter.com/share?url=<?=$link_url?>&text=<?=$title?>">Tweet</a>
												
													<a class="facebookicon" href="http://www.facebook.com/sharer.php?u=<?=$link_url?>">Share</a>
													
												</div>
												</div>
												<div class="clear"></div>
											
											
										
										</div>
										<div class="clear"></div>
										
										
										
									<?
											}
										}
									}
									if($wallType == "news")
									{
										$sqlSticky = "SELECT * FROM news WHERE id = '" . $wallID . "' AND show_date <= NOW() AND sticky = 0 AND showing = 1 AND date <= $today";
										$querySticky = mysql_query($sqlSticky);
										$checkSticky = mysql_num_rows($querySticky);
										
										if($checkSticky)
										{
											
											while($rowSticky = mysql_fetch_assoc($querySticky))
											{
											$count ++;

											$sql_news = "SELECT * FROM news WHERE id = '$wallID' AND date <= $today";
											$query_news = mysql_query($sql_news);
											$check_news = mysql_num_rows($query_news);
											$row_news = mysql_fetch_assoc($query_news);
											
											$link_url = ROOT . "/news/$wallID";
											$title = urlencode('@Stephen_Kellogg ' . $row['title']);
										?>
						
										
											<div class="textBox">

												<div class="left">
													<!--<img alt="" src="<?=ROOT ?>/images/post_news.png" />--><i></i>
												</div><!--end .left-->
												
												<div class="right">
							
												<a href="<?=$link_url?>"><?=$row['title']?></a>
																								
													<p>
														<? if($rowSticky['id'] <= 176) { ?>
														<?=strip_tags(str_replace('scrape', ROOT . '/scrape',$row_news['summary']),'<img><br><a><p><br /><br/><b><em><i><u><strong>')?>
													<? } else {
														
														echo strip_tags($row_news['summary'],'<img><br><a><p><br /><br/><b><em><i><u><strong>');
														
													} ?>
													</p>
													
													<a href="<?=$link_url?>" class="post">POSTED ON <?=date('M j, Y',$row_news['date'])?> VIA NEWS </a>
													
													<div class="link">
													
														<a class="twittericon" href="http://twitter.com/share?url=<?=$link_url?>&text=<?=$title?>">Tweet</a>
												
														<a class="facebookicon" href="http://www.facebook.com/sharer.php?u=<?=$link_url?>">Share</a>
														
													</div>
													
													<div class="clear"></div>
												
												
												
												</div>
												<div class="clear"></div>
											</div>
											
										
										<?
											}
										}
									}
									if($wallType == "videos")
									{	
										$sqlSticky = "SELECT * FROM videos WHERE id = '" . $wallID . "' AND sticky = 0 AND showing = 1";
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
												
												$vid_width = '500';
/* 											$vid_width = '100%'; */
												
												$video = stripslashes($row_vids['embed_code']);
												$output = preg_replace('/width="(\d+)"/', 'width="'.$vid_width.'"', $video);
												$output = preg_replace('/height="(\d+)"/', 'height="350"', $output);
												
												$title = urlencode('@Stephen_Kellogg ' . $row['title']);
												$link_url = ROOT . "/media/#videos";
									?>
										<div class="textBox">
												
												<div class="left">
													<!--<img alt="" src="<?=ROOT ?>/images/post_videos.png" />--><i></i>
												</div><!--end .left-->
												
												<div class="right">
																						
													<?=$output?>
												
													<a href="<?=$link_url?>" class="post">POSTED ON <?=$wallDate?> VIA VIDEOS </a>
												
													<div class="link">
												
														<a class="twittericon" href="http://twitter.com/share?url=<?=$link_url?>&text=<?=$title?>">Tweet</a>
												
														<a class="facebookicon" href="http://www.facebook.com/sharer.php?u=<?=$link_url?>">Share</a>
													
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
										$link_url = "http://twitter.com/SK6ers";
									?>
										<div class="textBox">
									
											<div class="left">
												<img alt="" src="<?=ROOT?>/images/icon-twitter.png" />
											</div>
									
											<div class="right">

												<p><?=$row['content']?></p>		
													
												<a href="<?=$link_url?>" class="post">POSTED ON <?=$wallDate?> VIA TWITTER </a>
												
												<div class="link">
												
													<a class="twittericon" href="http://twitter.com/share?url=http://twitter.com/sk6ers&text=<?=$row['content']?>">Tweet</a>
												
													<a class="facebookicon" href="http://www.facebook.com/sharer.php?u=http://twitter.com/sk6ers">Share</a>
													
												</div>
												
												<div class="clear"></div>
												
											</div>
											<div class="clear"></div>
										</div>
										
									<?
									}
								}
								
							}
							
					}else{
					
						echo "<h2>News Feed is currently Unavailable. Please check back later.</a>";
					
					}
?>				

					</div>
					<div class="clear"></div>
						<div id="more-<?php echo strtotime($cd); ?>" class="morebox">
							<a href="#" class="older_posts more"><span>View More Posts</span></a>
						</div>
				</div>

				
				<? include('./includes/sidebar.php'); ?>
				
			
			

	
	<?php
        if(1) {
    ?>
        <a href="#blunderpopup" class="fancy-preorder"></a>
        <div style="display: none;">
        	<div id="blunderpopup">
				<div class="wrapper">
					<div class="left-col">
						<img src="/splash/blunderstone_rookery/images/pop-images/blunderstone-cover.png" />
						<br />
						<img src="/splash/blunderstone_rookery/images/pop-images/preorder-now.png" />
						<br />
						<a href="http://smarturl.it/BlunderstoneiTunes" target="_blank">
							<img src="/splash/blunderstone_rookery/images/pop-images/itunes.png" />
						</a>
						<br />
						<img src="/splash/blunderstone_rookery/images/pop-images/or-text.png" />
						<br />
						<a href="http://bit.ly/BlunderstoneAMZN" target="_blank">
							<img src="/splash/blunderstone_rookery/images/pop-images/amazon.png" />
						</a>
						<br />
						<br />
						<br />
						<a href="http://www.stephenkellogg.com/redeem" target="_blank">
							<img src="/splash/blunderstone_rookery/images/pop-images/redeem.png" />
						</a>
					</div>
					
					<div class="right-col">
						<a href="http://www.districtlines.com/stephenkellogg" target="_blank">
							<img src="/splash/blunderstone_rookery/images/pop-images/heading1.png" />
						</a>
						<br />
						<a href="http://www.districtlines.com/stephenkellogg" target="_blank">
							<img src="/splash/blunderstone_rookery/images/pop-images/preorders.png" />
						</a>
						<br />
						<br />
						<a class="enter-site" href="http://www.stephenkellogg.com/home">
							<img src="/splash/blunderstone_rookery/images/pop-images/enter.png" />
						</a>
						<div class="stephen"></div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
        </div>
        <?php } ?>
	
	<script type="text/javascript">
	$(document).ready(function() {
	
		<?php
			if(0) {
				$_SESSION['preorder_notice'] = true;
		?>
		$('.fancy-preorder').fancybox({
			padding : 0,
			margin : 0,
		}).trigger('click');
		<?php } ?>
		
		$('.enter-site').live('click', function() {
			$.fancybox.close();
			return false;
		});
		
		$('.morebox').live("click",function() 
		{
			var ID = $(this).attr("id");
			ID = ID.replace('more-', '');
			if(ID)
			{
				$('.morebox a span').html('Loading Posts....');
				$.ajax({
					type: "POST",
					url: "ajax_more.php",
					data: "lastpost="+ID, 
					cache: false,
					success: function(html){
						$(".home_page").append(html);
						$("#more-"+ID).remove();
					}
				});
			}
			else
			{
				$(".morebox").html('<h2>No more older posts!</h2>');
				
			}
	
			return false;
		});
	});
	</script>
<? include('./includes/footer.php'); ?>