<?php
	$active = 'home';
	$pageTitle = 'Events';
	$curr_page = 'events';
	$layout = 'large-left-col';
	include('./includes/header.php');
?>
	<h2 class="page-title">BIO //</h2>
	<div class="article">
		
		<?
			
			$sql = "SELECT * FROM content_pages WHERE title = 'band'";
			
			$query = mysql_query($sql);
			
			if(mysql_num_rows($query)) {
				
				$row = mysql_fetch_assoc($query);
				
				echo "<div class=\"article_copy\">";
				echo strip_tags($row['body'],'<font><em><strong><br><br /><BR><hr><hr /><b><i><u><h1><h2><h3><h4><h5><h6><img><span><font><a><iframe><object><embed>');
				echo "</div>";
			}
			
			$sql = "SELECT * FROM content_pages WHERE title = 'discography/music'";
			
			$query = mysql_query($sql);
			
			if(mysql_num_rows($query)) {
				$row = mysql_fetch_assoc($query);
			}
			
		?>
		<h2 class="page-title">CONTACT //</h2>
		<div id="contact_contacts">
			<?
					$sql = "SELECT * FROM content_pages WHERE title='contact'";
					$query = mysql_query($sql);
					$check = mysql_num_rows($query);
					if($check)
					{	
						while($row=mysql_fetch_assoc($query))
						{
							echo "<div class=\"user_input\">".$row['body']."</div>";
						}
					}else{
						echo "<p>No Content has been added.</p><hr />";
					}	
				?>
		</div>
		
		<h2 class="page-title">CAUSES //</h2>
		<?
			
			$sql = "SELECT * FROM causes";
			
			$query = mysql_query($sql);
			
			if(mysql_num_rows($query)) {
				
				echo "<ul>";
				
				while($row = mysql_fetch_assoc($query)) {
				
					?>
						<br>
						<strong><?=$row['title']?></strong>
						
						<p class="article_copy"><?=$row['content']?></p>
						
						<p><a href="<?=$row['link']?>"><?=$row['link_text']?></a></p>
					
					<?
					
				}
				
				echo "</ul>";
			
			}
			
		?>
	</div><!-- article -->
	
	</div>

<? include('includes/footer.php'); ?>