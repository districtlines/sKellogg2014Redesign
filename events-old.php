<? $active = 'home'; ?>
<? $pageTitle = 'Events'; ?>
<? $curr_page = 'events'; ?>
<? include('./includes/header.php'); ?>

<?
	$page_name = 'events';
	
	if(!$_GET['page'] || $page < 1) {
		
		$page = 1;
	
	} else {
	
		$page = $_GET['page'];
	
	}
	
	

?>

	<div class="events">
		<h2 class="heading">Tour Dates</h2>
		<table class="events_tbl" cellpadding="0" cellspacing="0" width="100%">
			<thead>
				<tr>
					<td colspan="2">&nbsp;&nbsp;&nbsp;Date</td>
					<td>Venue</td>
					<td>Location</td>
					<td>Age</td>
					<td></td>
				</tr>
			</thead>

			<tbody>
			
			<?php
				
				
				$today = strtotime(date('m/d/Y'));
				$sql = "SELECT * FROM events WHERE date >= $today AND show_date <= NOW() ORDER BY date ASC";
				//echo $sql;
				$query = mysql_query($sql) or die(mysql_error());
				$check = mysql_num_rows($query);
				$count = 0;
				if($check) {
					while($row = mysql_fetch_assoc($query)) :
						$count ++;
			?>
				<tr class="<?=$count % 2 == 0 ? 'even' : 'odd';?>">
					<td>
						<?php
							if($row['details']) {
						?>
						<a name="events-<?=$row['id'];?>" class="desc_btn hidden" rel="<?=$row['id'];?>" href="#">+</a>
						<?php
							}
						?>
					</td>
					<td class="date add_padd"><?=date('n/j/Y', $row['date']);?></td>
					<td class="venue add_padd">
						<? if($row['venue_url']) { ?>
						
						<a href="<?=$row['venue_url']?>" target="_blank">
						
						<? } ?>
						
							<?=$row['venue'];?>
						
						<? if($row['venue_url']) { ?>
						
						</a>
						
						<? } ?>
					</td>
					<td class="address add_padd">
						<?=$row['city'];?>
					</td>
					<td class="age add_padd">
						<?=$row['age'];?>
					</td>
					<td class="misc add_padd">
						
						
						
						<?php if($row['tickets_url'] && !$row['soldout']) { ?>
						<a class="buy_tix normalize-btns" target="_blank" href="<?=$row['tickets_url'];?>">BUY</a>
						<?php } else if($row['tickets_url'] && $row['soldout']) { ?>
							
						<div class="soldout normalize-btns">Sold Out</div>
						<? } else { ?>
						<div class="notforsale">Not For Sale</div>
<!-- 						<div class="clear"></div> -->
							
						<? }?>
						<?php if($row['fb_event_url']) { ?>
						<a class="rsvp normalize-btns <?=$row['soldout'] ? 'sold-out' : '';?>" target="_blank" href="<?=$row['fb_event_url'];?>">RSVP</a>
						<?php } ?>
						<a class="twittericon" href="http://twitter.com/share?url=<?=ROOT?>/events/#events-<?=$row['id']?>&text=Stephen Kellogg site news was updated."</a>	
						<a class="facebookicon" href="http://www.facebook.com/sharer.php?u=<?=ROOT?>/events/#events-<?=$row['id']?>"</a>
						
						<div class="clear"></div>
					</td>
				</tr>
				<?php
					if($row['details']) {
				?>
				<tr id="description_<?=$row['id'];?>">
					<td class="description hidden" colspan="6">
						<p>Door Time: <?=date('g:i A', strtotime($row['door_time']));?> &mdash; Show time: <?=date('g:i A', strtotime($row['time']));?></p>
						<p><?=$row['details'];?></p>
					</td>
				</tr>
				<?php
					}
				?>
			
			<?php
					endwhile;
				} else {
					echo "<tr><td colspan=\"6\">Sorry there is currently no events. Check back soon!</td></tr>";
				}
			?>
			
			</tbody>
		</table>
		
		<a name="past-events"></a>
		<h2 class="heading" style="margin-top:20px;">Past Tour Dates</h2>
		<table class="events_tbl" cellpadding="0" cellspacing="0" width="100%" style="">
			<thead>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;Date</td>
					<td>Venue</td>
					<td>Location</td>
					<td></td>
				</tr>
			</thead>

			<tbody>
			
			<?php
				
				$max_pages = 13;
				
				$limit = 4;
				
				$start = ($page - 1) * $limit;
	
				$today = strtotime(date('m/d/Y'));
				
				$sql = "SELECT * FROM events WHERE date < $today AND show_date <= NOW() ORDER BY date DESC";
				
				$query = mysql_query($sql) or die(mysql_error());
				
				$check = mysql_num_rows($query);
				
				$total_pages = ceil( $check / $limit );
				
				if ($page > $total_pages && $total_pages) {
										
					$page = $total_pages;
				
				}
				
				$sql .= " LIMIT $start, $limit";
	
				$query = mysql_query($sql);
				
				$check = mysql_num_rows($query);
				
				$count = 0;
				if($check) {
					while($row = mysql_fetch_assoc($query)) :
						$count ++;
			?>
				<tr id="event-<?=$row['id']?>" class="<?=$count % 2 == 0 ? 'even' : 'odd';?>">
					
					<td class="date add_padd" style="padding-left:10px;"><?=date('n/j/Y', $row['date']);?></td>
					<td class="venue add_padd"><?=$row['venue'];?></td>
					<td class="address add_padd">
						<?=$row['city'];?>
					</td>
					<td class="misc add_padd">
						<? if($row['setlist']) {
						?>
						
							<a name="events-<?=$row['id']?>" class="desc_btn hidden setlist normalize-btns" style="" rel="<?=$row['id']?>" href="#">Show Setlist</a>
							
						<?
						}
						?>
					</td>
				</tr>
				<?php
					if($row['setlist']) {
				?>
				<tr id="description_<?=$row['id'];?>">
					<td class="description hidden" colspan="4">
						<p><?=$row['setlist'];?></p>
					</td>
				</tr>
				<?php
					}
				?>
			
			<?php
					endwhile;
				} else {
					echo "<tr><td colspan=\"6\">Sorry there is currently no events. Check back soon!</td></tr>";
				}
			?>
			
			</tbody>
		</table>
		
	</div>
	
	<?
	
	if ($total_pages > 1) {
		
			echo "<div id=\"pagination\" style=\"text-align: center;margin-top:20px\">";
		
			$prev_page = $page - 1;
			
			$next_page = $page + 1;
			
			$start_page = 1;
			
			
			if ($prev_page >= 1) {
				
				echo "<a class=\"pagination_prev pagination\" href=\"" . ROOT . "/$page_name/page$prev_page\">&laquo; Previous</a>";
				
			}
			
			
			
			if(($page + $max_pages) < $total_pages) {
			
				if((($page + 2) - $max_pages) < 1) {
					
					$start_page = 1;
					
					$front_dots = false;
				
				} else {
				
					$start_page = $page - 1;
					
					
					$front_dots = true;
				
				}
				
				$end_dots = true;
			
			}
			
			
			
			if((($page + 2) - $max_pages) < 1 && $total_pages > $max_pages) {
				
				$front_dots = false;
				$start_page = $page;
				$end_dots = true;
			
			}
			
/* 			if 371 >= */

			//echo "($page + $max_pages) >= $total_pages && $total_pages > $max_pages";
			
			if(($page + $max_pages) >= $total_pages && $total_pages > $max_pages) {
			
				$front_dots = true;
				
				$start_page = ($total_pages) - $max_pages;
				
				$end = true;
				
/* 				echo "($total_pages) - $max_pages<BR>";	 */
/* 				$start_page = $page; */
				
				$end_dots = false;
			
			}
			
			
			
			
			if($front_dots || $end_dots) {
			
				
				if($front_dots) {
					if(!$end) {
						$start_page++;		
					} else {
						
						$end_page = $total_pages;
					
					}
/* 					$start_page --; */
				}
				
				if($end_dots) {
					
					$end_page++;
					if($page != 1) {
					$start_page --;
					}
				
				}
			
			}
						
			/*
echo $start_page;
			echo "<BR>";
			echo $end_page;
*/		
			$end_page = $max_pages + $start_page;
			
			if($start_page >= 3) {
			
				$front_dots = true;
				
				if(!$end) {
					$end_page--;
				}
			}
			
			if($start_page == 2) {
				
				$start_page = 1;
				
				$end_page --;
				
			}
			
			if($end_page > $total_pages) {
				
				$end_page = $total_pages;
			
			}
			
/* 			echo $start_page; echo "<BR>"; echo $end_page;echo "<BR>";echo "FRONT : " . $front_dots;echo "<BR>";echo $end_dots; */
			
			for ($page_i = $start_page; $page_i <= $end_page; $page_i++) {

								
				if($page_i == $start_page && $front_dots) {
					
					echo "<a class=\"pagination_num pagination\" href=\"" . ROOT . "/$page_name/page1/#past-events\">1</a> ";
					echo "<span class=\"pagination\" style=\"background:none;display:block;float:left;\">...</span>";
					
					if($page_i == $page) {
						
						echo "<span class=\"pagination current\">". $page_i ."</span>";
					
					} else {
					
						echo "<a class=\"pagination_num pagination\" href=\"" . ROOT . "/$page_name/page$page_i/#past-events\">$page_i</a> ";
						
					}
				
				} else if($page_i == $start_page && !$front_dots){
					
					if($page_i == $page) {
					
						echo "<span class=\"pagination current\">". $page_i ."</span>";
					
					} else {
					
						echo "<a class=\"pagination_num pagination\" href=\"" . ROOT . "/$page_name/page$page_i/#past-events\">$page_i</a> ";
					
					}
				
				} else if($page_i == $end_page) {
										
					if($end_dots) {
						echo "<span class=\"pagination\" style=\"background:none;display:block;float:left;\">...</span>";
						echo "<a class=\"pagination_num pagination\" href=\"" . ROOT . "/$page_name/page$total_pages/#past-events\">$total_pages</a> ";
					
					} else if($end_page == $page) {
						
						echo "<span class=\"pagination current\">". $end_page ."</span>";
						
					} else {
					
						echo "<a class=\"pagination_num pagination\" href=\"" . ROOT . "/$page_name/page$page_i/#past-events\">$page_i</a> ";
						
					}
				
				} else if($page_i == $page){
					
					
					echo "<span class=\"pagination current\">". $page_i ."</span>";
				
				} else{
				
				
					echo "<a class=\"pagination_num pagination\" href=\"" . ROOT . "/$page_name/page$page_i/#past-events\">$page_i</a> ";
				
				}
				
			
			}
		
			if ($next_page <= $total_pages) {
			
				echo "<a class=\"pagination_next pagination\" href=\"" . ROOT . "/$page_name/page$next_page/#past-events\">Next &raquo;</a>";
			
			}
			
			echo "<div class=\"clear\"></div>\r\n</div>";
			
		}	
		
		

?>

</div><!-- end leftCntr -->

<script type="text/javascript">
$(document).ready(function() {
	$(".desc_btn").click(function() {
		var btnID = $(this).attr('rel');
		//console.log(btnID);
		if($(this).hasClass('hidden')) {
			
			if($(this).hasClass('setlist')) {
				
				$(this).attr('class','visible desc_btn setlist normalize-btns').text('Hide Setlist');
				
			} else {
				$(this).attr('class','visible desc_btn normalize-btns');
				$(this).html('-');
			
			}
			
			$("#description_"+btnID).find('td').attr('class', 'description visible');
			//console.log($("#description_"+btnID).find('td'));
		} else {
			
			if($(this).hasClass('setlist')) {
				
				$(this).attr('class','hidden desc_btn setlist normalize-btns').text('Show Setlist');
				
				
			} else {
				
				$(this).attr('class','hidden desc_btn');
				
				$(this).html('+');
			
			}
			
			$("#description_"+btnID).find('td').attr('class', 'description hidden');
		}
		
		return false;
	});
});
</script>


<? include('./includes/sidebar.php'); ?>

<? include('./includes/footer.php'); ?>