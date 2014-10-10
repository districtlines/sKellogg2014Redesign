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
			
			$all_band = $SQL->fetchAssoc("SELECT * FROM content_pages WHERE title = 'band'");
						
			if(count($all_band)) {
				
				foreach($all_band as $band){
				
					echo "<div class=\"article_copy\">";
					echo strip_tags($band['body'],'<font><em><strong><br><br /><BR><hr><hr /><b><i><u><h1><h2><h3><h4><h5><h6><img><span><font><a><iframe><object><embed>');
					echo "</div>";
				}
			}
			
			$all_music = $SQL->fetchAssoc("SELECT * FROM content_pages WHERE title = 'discography/music'");
			
			
		?>
		<h2 class="page-title">CONTACT //</h2>
		<div id="contact_contacts">

			<?
				$contact_types= $SQL->fetchAssoc("SELECT * FROM `contact_types` ORDER BY `sort` ASC");
										
				if(count($contact_types)){
					
					foreach($contact_types as $type){
						
						
						$contacts = $SQL->fetchAssoc("SELECT * FROM `contacts` WHERE `type` = ".$type['id']." ORDER BY `sort` ASC");
						
						if(count($contacts)){
							echo "<div class='col-lg-4 col-xs-6 col-md-4 col-sm-4'> ";
							echo "<h5>".$type['type']."</h5>";		
								
							foreach($contacts as $contact){		
															
								echo '<div style="margin: 10px 0 20px;">';	
								if($contact['name'] != 'null'){ 
									
									if($contact['url'] != 'null' ){ 
										echo '<a href="http://'.$contact['url'].'">'.$contact['name'].'</a>'; 										
									}else{
										echo '<p>'.$contact['name'].'</p>'; 
									};
								}
								if($contact['company'] != 'null'){ echo '<p>'.$contact['company'].'</p>'; }
								if($contact['phone'] != 'null'){ echo '<p>'.$contact['phone'].'</p>'; }
								if($contact['phone_2'] != 'null'){ echo '<p>'.$contact['phone_2'].'</p>'; }
								if($contact['email'] != 'null' ){ echo '<a href="mailto:'.$contact['email'].'">Email</a>'; }
								
								echo '</div>';							

							}
						}
						echo '</div>';
					}
						
				}else{
					echo "<p>No Contacts</p><hr />";
				}	
			?>		
						
				
		<div class="clear"></div>
				
		
		<h2 class="page-title">CAUSES //</h2>
		<?
			
			$all_causes = $SQL->fetchAssoc("SELECT * FROM causes");
			
			
			if(count($all_causes)) {
				
				echo "<ul>";
				
				foreach($all_causes as $cause) {
				
					?>
						<br>
						<strong><?=$cause['title']?></strong>
						
						<p class="article_copy"><?=$cause['content']?></p>
						
						<p><a href="<?=$cause['link']?>"><?=$cause['link_text']?></a></p>
					
					<?
					
				}
				
				echo "</ul>";
			
			}
			
		?>
	</div><!-- article -->
	</div>
	</div>

<? include('includes/footer.php'); ?>