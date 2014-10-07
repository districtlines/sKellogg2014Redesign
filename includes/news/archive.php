<?

$author_imgs = array(
	
			4 => 'book',
			5 => 'music',
			6 => 'camera',
			7 => 'beer',
			8 => 'food'
		
		);
$news = array();
$today = strtotime(date('m/d/Y'));
	
	if(!$_GET['page'] || $page < 1) {
		
		$page = 1;
	
	} else {
	
		$page = $_GET['page'];
	
	}
	
	
	$sql = "SELECT * FROM blog_authors";
	
	$query = mysql_query($sql);
	
	$skip_ids = array();
	
	if(mysql_num_rows($query)) {
	
		while($row = mysql_fetch_assoc($query)) {
			
			$sql2 = "SELECT id FROM blog WHERE author = {$row['id']} AND show_date <= NOW() ORDER BY date DESC LIMIT 1";
					
			$query2 = mysql_query($sql2);
			
			if(mysql_num_rows($query2)) {
				
				$skip_ids[] = mysql_result($query2,0);
			
			}
		}
	}

	$skip_ids = 'id <> ' . implode(' AND id <> ', $skip_ids);
	
	$sql = "SELECT * FROM blog WHERE $skip_ids AND show_date <= NOW() ORDER BY date DESC";
	
	$limit = 16;
		
	// START ACTUAL LOOP
	
	$start = ($page - 1) * $limit;
	
	$sql .= " LIMIT $start, $limit";
		
	$query = mysql_query($sql);
	
	if(mysql_num_rows($query)) {
	
		while($row = mysql_fetch_assoc($query)) {
		
			$sql2 = "SELECT name FROM blog_authors WHERE id = {$row['author']}";
						
			$query2 = mysql_query($sql2);
			
			$author_name = mysql_result($query2,0);
			
			$news[] = array(
				'id'		=> $row['id'],
				'title'		=> $row['title'],
				'summary'	=> $row['summary'],
				'created'	=> $row['created'],
				'date'		=> $row['date'],
				'author'	=> $row['author'],
				'author_name' => $author_name
			);
		}
	}				

	// END LOGIC STUFF
	
	
	
	// OUTPUT 
	
	if(sizeof($news) > 0) {
	foreach($news as $n) {
	?>
		
		<div class="textBox">
			
			<div class="left">
				
				<img src="<?=ROOT?>/images/<?=$author_imgs[$n['author']]?>_icon.png" />
				
			</div> <!-- left -->
			
			<div class="right">
				
				<a href="<?=ROOT?>/<?=$page_name?>/<?=$n['id']?>" title="<?=$n['title']?>"><?=strip_tags($n['author_name'], '<img>')?></a>
					
					<a class="blog_title" href="<?=ROOT?>/<?=$page_name?>/<?=$n['id']?>" title="<?=$n['title']?>"><?=strip_tags($n['title'],'<img>')?></a>
			
				
				<p><?
					
					if($n['id'] <= 176) {
					
					echo strip_tags(str_replace('scrape', ROOT . '/scrape',$n['summary']),'<img><br><p><b><i><a><br/><br /><strong><em><object>');
					} else {
					
						echo strip_tags($n['summary'],'<img><br><p><b><i><a><br/><br /><strong><em><object>');
					
					}
				?></p>
				
				<? if($n['date']) { ?>
				
				<a class="post" href="<?=ROOT?>/<?=$page_name?>/<?=$n['id']?>">POSTED ON <?=date('F jS, Y', $n['date'])?> via <?=$page_name;?></a>
				
				<? } else { ?>
				
				<a class="post" href="<?=ROOT?>/<?=$page_name?>/<?=$n['id']?>">POSTED ON <?=date('F jS, Y', strtotime($n['date']))?> via <?=$page_name;?></a>
				
				<? } ?>
			</div> <!-- right -->
		<div class="clear"></div>
		</div> <!-- textBox -->
		
		
		
	<?
	
	
	
	}
	?>
		
		<div class="view_all view_archived"><a href="<?=ROOT?>/<?=$page_name?>">Previous Posts</a></div>
		
		<div class="clear"></div>
		
	<?
	} else {
	
		echo "<p>No archives exist</p>";
	
	}
	?>
		
<?php
		
		if ($total_pages > 1) {
		
			echo "<div id=\"pagination\" style=\"text-align: center;\">";
		
			$prev_page = $page - 1;
			
			$next_page = $page + 1;
			
			
			if ($prev_page >= 1) {
				
				echo "<a class=\"pagination_prev pagination\" href=\"" . ROOT . "/$page_name/archive/page$prev_page\">&laquo; Previous</a>";
				
			}
			
			for ($page_i = 1; $page_i < $total_pages + 1; $page_i++) {
			
				if ($page_i == $page) {
				
					echo "<span class=\"pagination current\">". $page_i ."</span>";
				
				} else {
			
					echo "<a class=\"pagination_num pagination\" href=\"" . ROOT . "/$page_name/archive/page$page_i\">$page_i</a> ";
			
				}
			
			}
		
			if ($next_page <= $total_pages) {
			
				echo "<a class=\"pagination_next pagination\" href=\"" . ROOT . "/$page_name/archive/page$next_page\">Next &raquo;</a>";
			
			}
			
			echo "<div class=\"clear\"></div>\r\n</div>";
			
		}	
		
		?>	