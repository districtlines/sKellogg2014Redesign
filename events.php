<?php
	$active = 'home';
	$pageTitle = 'Events';
	$curr_page = 'events';
	$layout = 'large-left-col';
	include('./includes/header.php');
	
	$today = strtotime(date('m/d/Y'));
	$currentShowsRes = $SQL->fetchAssoc("SELECT * FROM events WHERE date >= '{$today}' AND show_date <= NOW() ORDER BY date ASC");
	
	if(!isset($_GET['page'])) {
		$page = 1;
	} else {
		$page = $_GET['page'];
	}
	
	$max_pages = 13;
	$limit = 6;
	$start = ($page - 1) * $limit;
	$sql = "SELECT * FROM events WHERE date < '{$today}' AND show_date <= NOW() ORDER BY date DESC";
	$query = $SQL->fetchAssoc($sql);
	$check = count($query);
	$total_pages = ceil( $check / $limit );
	
	if ($page > $total_pages && $total_pages) {
		$page = $total_pages;
	}
		
	$sql .= " LIMIT $start, $limit";
	
	$pastShowsRes = $SQL->fetchAssoc($sql);
?>
<style>
	body {
		background: url("/images/shows-bg2.png") no-repeat fixed center top / contain #1D1D1D;
	}
</style>
<h2 class="page-title">TOUR DATES //</h2>

<div class="event-page">
	<div class="table-responsive">
	<table id="current-shows" class="table table-striped">
		<thead>
			<tr>
				<th>Date</th>
				<th>Venue</th>
				<th>Location</th>
				<th></th>
				<th><i class="fa fa-info-circle"></i></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($currentShowsRes as $event) : ?>
			<tr class="table-tooltip" data-toggle="tooltip" data-placement="top" title="Click to view more info!" data-details="<?php echo $event['id']; ?>">
				<td><?php echo date('m/d/Y',$event['date']);?></td>
				<td>
					<?php
						if( isset($event['venue_url']) && !empty($event['venue_url']) && $event['venue_url'] != 'null' ) { ?>
					<a href="<?php echo $event['venue_url']; ?>" target="_blank">
					<?php echo $event['venue']; ?>
					</a>
					<?php } else { ?>
					<?php echo $event['venue']; ?>
					<?php } ?>
				</td>
				<td><?php echo $event['city']; ?></td>
				<td>
					<?php if($event['tickets_url'] != 'null' && !$event['soldout'] && !$event['last_tickets']) { ?>
					<a class="btn btn-default btn-xs" target="_blank" href="<?=$event['tickets_url'];?>">BUY</a>
					<?php } else if($event['tickets_url'] && !$event['soldout'] && $event['last_tickets']) { ?>
					<a class="btn btn-warning btn-xs" target="_blank" href="<?=$event['tickets_url'];?>">Last Tickets</a>
					<?php } else if($event['tickets_url'] && $event['soldout']) { ?>
					<span class="soldout normalize-btns btn btn-danger btn-xs">Sold Out</span>
					<? } else { ?>
					<!-- <div class="notforsale">Not For Sale</div> -->
					<? }?>
					<?php if($event['fb_event_url']) { ?>
					<a class="btn btn-default btn-xs <?=$event['soldout'] ? 'sold-out' : '';?>" target="_blank" href="<?=$event['fb_event_url'];?>">RSVP</a>
					<?php } ?>

					<a class="btn btn-default btn-xs" target="_blank" href="<?php echo SIXERTOWN_URL; ?>">VIP</a>

					<a class="twittericon" href="http://twitter.com/share?url=<?=ROOT?>/events/#events-<?=$event['id']?>&text=Stephen Kellogg site news was updated."><i class="fa fa-twitter"></i></a>
					<a class="facebookicon" href="http://www.facebook.com/sharer.php?u=<?=ROOT?>/events/#events-<?=$event['id']?>"><i class="fa fa-facebook"></i></a>
				</td>
				<td class="caret-col"><i class="fa fa-caret-down"></i></td>
			</tr>
			<tr class="hide" id="extraDetails-<?php echo $event['id']; ?>">
				<td colspan="5">
					<?php if($event['age'] == 'null'){ echo " "; }else{ echo "Age: ".$event['age']."<br>"; } ?>
					<?php echo "Show: ".date('g:ia', strtotime($event['time']))."<br>"; ?>
					<?php echo "Door: ".date('g:ia', strtotime($event['door_time']))."<br><br>"; ?>

					<?php echo nl2br($event['details']); ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	</div>
	<hr />
	<h2 class="page-title">PAST DATES //</h2>
	<div class="table-responsive">
	<table id="past-events" class="table table-striped">
		<thead>
			<tr>
				<th>Date</th>
				<th>Venue</th>
				<th>Location</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($pastShowsRes as $event) : ?>
			<tr class="table-tooltip" data-toggle="tooltip" data-placement="top" title="Click to view the setlist!" data-details="<?php echo $event['id']; ?>">
				<td><?php echo date('m/d/Y',$event['date']);?></td>
				<td>
					<a href="<?php echo $row['venue_url'] ? $row['venue_url'] : '#'; ?>" target="_blank">
					<?php echo $event['venue']; ?>
					</a>
				</td>
				<td><?php echo $event['city']; ?></td>
				<td><a class="btn btn-block btn-default">Show setlist</a></td>
			</tr>
			<tr class="hide" id="setList-<?php echo $event['id']; ?>">
				<td colspan="8">
					<?php echo $event['setlist']; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>	
</div>

<?php
    
    // determine page (based on <_GET>)
    $page = isset($_GET['page']) ? ((int) $_GET['page']) : 1;

    // instantiate; set current page; set number of records
    $pagination = (new Pagination());
    $pagination->setCurrent($page);
    $pagination->setRPP($limit);
    $pagination->setTotal($check);
    $pagination->setTarget('/events/page');
    $pagination->setHashBang('#past-events');

    // grab rendered/parsed pagination markup
    $markup = $pagination->parse();
    echo $markup;
?>

</div>

<script type="text/javascript">
	(function($) {
		$('.table-tooltip').tooltip();
		$('body').on('click', '#current-shows tbody tr', function() {
			var $t = $(this);
			var $id = $t.attr('data-details');
			$t.find('.caret-col > i').toggleClass('fa-caret-up fa-caret-down');
			$('#extraDetails-'+$id).toggleClass('hide');
		});
		$('body').on('click', '#past-events tbody tr', function() {
			var $t = $(this);
			var $id = $t.attr('data-details');
			$('#setList-'+$id).toggleClass('hide');
		});
	})(jQuery);
</script>
<?php include('./includes/footer.php'); ?>