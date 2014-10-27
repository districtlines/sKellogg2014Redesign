<?
	include('includes/config.php');
	$_table = section;
	$_listing_fields = generate_fields($_table);
	
	
	
	//submit
	if ($_REQUEST['action'] == 'submit') {
		$_SESSION['form_error'] = array();
		
		validate('name', false);
		validate('time', true,array(
			'time' => array()
		));
		validate('door_time', true,array(
			'time' => array()
		));
		validate('venue');
		validate('city');
		validate('tickets_url', false);
		validate('details', false);
		validate('setlist', false);
		validate('age', false);
		validate('fb_event_url', false);
		validate('sticky', false);
		validate('showing', false);
		validate('soldout', false);
		validate('last_tickets', false);
		validate('venue_url',false);
		validate('show_time',false,array(
			
			'time' => array()
			
		));
		validate('show_d',false);
		
		if($_SESSION['form_data']['show_d'] && $_SESSION['form_data']['show_time']) {
			
			$_SESSION['form_data']['show_date'] = $_SESSION['form_data']['show_d'] . ' ' . $_SESSION['form_data']['show_time'];
			
			unset($_SESSION['form_data']['show_time']);
			unset($_SESSION['form_data']['show_d']);
			
		}
		
		
		
		if($_SESSION['form_data']['show_date'] == '1969-12-31 18:00:00' || $_SESSION['form_data']['show_date'] == '1911-11-30 12:00:00' || $_SESSION['form_data']['show_date'] == '0000-00-00 00:00:00') {
			
			$_SESSION['form_data']['show_date'] = date('Y-m-d H:i:s');
		
		}
		
		if ($_POST['date']) {
			$_SESSION['form_data']['date'] = strtotime($_POST['date']);
		}
		
		// IF ID IS PASSED		
		if (is_numeric($_REQUEST['id'])) {
			
			$id = '`id` = ' . $_REQUEST['id'];
			$newEntry = false;
			
		} else {
		
			$newEntry = true;
			
		}
		
		// NOT A NEW ENTRY? DELETE OLD ONE FROM "RECENT_ACTIVITY" TABLE
		if(!$newEntry)
		{
			$sql = "DELETE FROM recent_activity WHERE type = 'events' AND type_id = '" . $_REQUEST['id'] . "'";
			$query = mysql_query($sql) or die(mysql_error());
		}
		
		if (!count($_SESSION['form_error'])) {
		
			set_times($id);
			
			if (save_row($_table, $_SESSION['form_data'], $id)) {
				
				if ($id) {
					$id = $_REQUEST['id'];
				} else {
					$id = mysql_insert_id();
				}
					
					$sql = "UPDATE events SET show_date = '{$_SESSION['form_data']['show_date']}' WHERE id = $id";
					
					mysql_query($sql);
					//ADD INTO RECENT ACTIVITIES	
					$typeSql = 'SELECT id FROM events WHERE name = \'' . $_SESSION['form_data']['name'] . '\'';
					$typeQuery = mysql_query($typeSql);
					$typeCheck = mysql_num_rows($typeQuery);
					if($typeCheck)
					{
						while($typeRow = mysql_fetch_assoc($typeQuery))
						{
							$typeID = $typeRow['id'];
						}
					}
					
					if($_SESSION['form_data']['sticky'] == '1' && !$newEntry)
				{
				$sql = "SELECT sticky FROM events WHERE id='$typeID'";
				$query = mysql_query($sql);
				$check = mysql_num_rows($query);
				if($check)
				{
					while($row = mysql_fetch_assoc($query))
					{
						$sticky = $row['sticky'];

							$sqlSticky = "INSERT INTO stickies (title,created,modified,type,type_id) values ('" . $_SESSION['form_data']['name'] . "', NOW(), NOW(), 'events','" . $typeID . "')";
					}
				}
				}
					
				
				if($_SESSION['form_data']['sticky'] == "1" && $newEntry)
				{
					$sqlSticky = "INSERT INTO stickies (title,created,modified,type,type_id) values ('" . $_SESSION['form_data']['name'] . "',NOW(), NOW(), 'events','" . $typeID . "')";
					$querySticky = mysql_query($sqlSticky) or die(mysql_error());
				}
				
				if($_SESSION['form_data']['sticky'] != "1" && !$newEntry)
				{
						
						$sqlSticky = "DELETE FROM stickies WHERE type = 'events' AND type_id = '" . $typeID . "'";
						$querySticky = mysql_query($sqlSticky) or die(mysql_error());
				
				}

				if($_SESSION['form_data']['sticky'] == "1" && !$newEntry)
				{
					$sql = "SELECT * FROM stickies WHERE type_id='$typeID' and type='events'";
					$query = mysql_query($sql);
					$check = mysql_num_rows($query);
					if($check)
					{
						$sqlSticky = "UPDATE stickies SET title = '" . $_SESSION['form_data']['name'] . "', created = NOW(), modified = NOW() WHERE type = 'events' AND type_id = '$typeID'";
						$querySticky = mysql_query($sqlSticky) or die(mysql_error());
					}else{
						$sqlSticky = "INSERT INTO stickies (title,created,modified,type,type_id) values ('" . $_SESSION['form_data']['name'] . "', NOW(), NOW(), 'events','" . $typeID . "')";
					$querySticky = mysql_query($sqlSticky) or die(mysql_error());
					}
				}
					
					if($_SESSION['form_data']['showing'] == "1")
					{
					$recentSql = "INSERT INTO recent_activity (title,created,modified,type,type_id,sticky) VALUES ('" . $_SESSION['form_data']['name'] . "', NOW(), NOW(),'events','" . $typeID . "','" . $_SESSION['form_data']['sticky'] . "')";
					$recentQuery = mysql_query($recentSql) or die(mysql_error());
					}
					//END
				
				$_SESSION['message'] = clean_section . ' Entry Saved!';
				$_SESSION['form_data'] = array();
			}
		}
		
		redirect('./' . section . '.php');
	}
	
	
	//delete
	if ($_REQUEST['action'] == 'delete') {
		if (is_array($_REQUEST['delete'])) {
			foreach ($_REQUEST['delete'] as $delete_id => $delete) {
				if ($delete) {
					
					$sql = "SELECT name FROM ". $_table ." WHERE id = '$delete_id'";
					$query = mysql_query($sql) or die(mysql_error());
					$row = mysql_fetch_assoc($query);
					$name = $row['name'];
					mysql_query('DELETE FROM `' . $_table . '` WHERE `id` = \'' . $delete_id . '\' LIMIT 1') or die(mysql_error());
					mysql_query("DELETE FROM recent_activity WHERE title='$name' AND type='events' LIMIT 1") or die(mysql_error());
					mysql_query("DELETE FROM stickies WHERE title='$name' AND type='events' LIMIT 1") or die(mysql_error());
					
				}
			}
			
			$_SESSION['message'] = clean_section . ' Entries Deleted!';
		} else {
			$_SESSION['message'] = 'No ' . clean_section . ' Entries Selected!';
		}
		
		redirect('./' . section . '.php');
	}
	
	
	
	include('includes/header.php');
?>
	
	<h3><span><?= $_REQUEST['edit'] ? 'Edit' : 'Add' ?> Entry</span></h3>
	
	<? if (is_array($_SESSION['form_error']) && count($_SESSION['form_error'])) { ?>
	<div class="error">
		<strong>Please fix the errors below and re-submit the form:</strong><br /><br />
		<? foreach ($_SESSION['form_error'] as $field => $error) { ?>
		&nbsp;+ <?= $error ?><br />
		<? } ?>
	</div>
	<? } ?>
	
	<form action="./<?= section ?>.php" method="post" id="add_form" enctype="multipart/form-data">
	<? input_hidden('action','submit') ?>
	
	<?
		if (is_numeric($_REQUEST['edit']) && get_rows('SELECT * FROM `' . $_table . '` WHERE `id` = \'' . $_REQUEST['edit'] . '\'')) {
			$_SESSION['form_data'] = get_rows();
			input_hidden('id',$_REQUEST['edit']);
		}
	?>	
	
	<table id="form_table" cellspacing="0">
		<? if ($_REQUEST['edit']) { ?>
		<tr>
			<th>ID</th>
			<td><? input_text('id',$_REQUEST['edit'],' disabled="disabled"') ?></td>
		</tr>
		<? } ?>
      
		<tr>
			<th>Name</th>
			<td class="<?= isset($_SESSION['form_error']['name']) ? ' error' : '' ?>"><? input_text('name') ?></td>
		</tr>
	
		<tr>
			<th>Date</th>
			<td class="<?= isset($_SESSION['form_error']['date']) ? ' error' : '' ?>"><? input_text('date','','class="date-pick"') ?></td>
		</tr>
		
		<tr>
			<th>Show Time</th>
			<td class="<?= isset($_SESSION['form_error']['time']) ? ' error' : '' ?>"><? select_time('time',$_SESSION['form_data']['time']) ?></td>
		</tr>
		
		<tr>
			<th>Door Time</th>
			<td class="<?= isset($_SESSION['form_error']['door_time']) ? ' error' : '' ?>"><? select_time('door_time',$_SESSION['form_data']['door_time']) ?></td>
		</tr>
		
		<tr>
			<th>Venue</th>
			<td class="<?= isset($_SESSION['form_error']['venue']) ? ' error' : '' ?>"><? input_text('venue') ?></td>
		</tr>
		
		<tr>
			<th>Venue URL</th>
			<td class="<?= isset($_SESSION['form_error']['venue_url']) ? ' error' : '' ?>"><? input_text('venue_url') ?></td>
		</tr>
		
		<tr>
			<th>City</th>
			<td class="<?= isset($_SESSION['form_error']['city']) ? ' error' : '' ?>"><? input_text('city') ?></td>
		</tr>
		
		<tr>
			<th>Age</th>
			<td class="<?= isset($_SESSION['form_error']['age']) ? ' error' : '' ?>"><? input_text('age') ?></td>
		</tr>
		
		<tr>
			<th>Ticket URL</th>
			<td class="<?= isset($_SESSION['form_error']['tickets_url']) ? ' error' : '' ?>"><? input_text('tickets_url') ?></td>
		</tr>
		
        <tr>  
        	<th>Details</th>  
        	<td class="<?= isset($_SESSION['form_error']['details']) ? ' error' : '' ?>"><? textarea('details', '', 'id="ignore_tf"') ?></td>
        </tr>
        
        <tr>  
        	<th>Setlist <small style="font-size:10px;"><br>( Visible once the event has happened )</small></th>  
        	<td class="<?= isset($_SESSION['form_error']['setlist']) ? ' error' : '' ?>"><? textarea('setlist', '', 'id="ignore_tf2"') ?></td>
        </tr>
        
        <tr>  
        	<th>Facebook Event URL</th>  
        	<td class="<?= isset($_SESSION['form_error']['fb_event_url']) ? ' error' : '' ?>"><? input_text('fb_event_url') ?></td>
        </tr>
        
        <tr>
			<th>Sold Out</th>
			
			<td class="<?= isset($_SESSION['form_error']['soldout']) ? ' error' : '' ?> checkbox"><? input_checkbox('soldout', '1') ?></td>
		</tr>
		
		<tr>
			<th>Last Tickets</th>
			
			<td class="<?= isset($_SESSION['form_error']['last_tickets']) ? ' error' : '' ?> checkbox"><? input_checkbox('last_tickets', '1') ?></td>
		</tr>
		<tr>
			<th>Sticky</th>
			
			<td class="<?= isset($_SESSION['form_error']['sticky']) ? ' error' : '' ?> checkbox"><? input_checkbox('sticky', '1') ?></td>
			
		</tr>
		
		<tr>
			<th>Show on Wall</th>
			
			<td class="<?= isset($_SESSION['form_error']['showing']) ? ' error' : '' ?> checkbox"> <input type="checkbox" checked="yes"
 name="showing" value="1" checked="yes" />
			</td>
			
		</tr>
		
		<tr>
			<tr>&nbsp;</tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<tr>&nbsp;</tr>
			<td>&nbsp;</td>
		</tr>
				
		<tr>
			<th>Date to Show</th>
			<?
			
				$split = explode(' ',$_SESSION['form_data']['show_date']);
				$showdate = array_shift($split);
				$_SESSION['form_data']['show_d'] = $showdate;
				$showtime = array_pop($split);
				
			?>
			<td class="<?= isset($_SESSION['form_error']['show_d']) ? ' error' : '' ?>"><? input_text('show_d','','class="date-pick"') ?></td>
		</tr>
		
		<tr>
			<th>Time to Show</th>
			<td class="<?= isset($_SESSION['form_error']['show_time']) ? ' error' : '' ?>"><? select_time('show_time',$showtime) ?></td>
		</tr>
		
		<script>
		Date.firstDayOfWeek = 0;
		Date.format = 'yyyy-mm-dd';
		$(function(){
		
			$('.date-pick').datePicker();
		});
		
		
		</script>

		<? if ($_REQUEST['edit']) { ?>
		<tr>
			<th>Created</th>
			<td><? input_text('created',null,' disabled="disabled"') ?></td>
		</tr>
		
		<tr>
			<th>Modified</th>
			<td><? input_text('modified',null,' disabled="disabled"') ?></td>
		</tr>
		<? } ?>

		<tr>
			<td colspan="2" class="submit"><? input_submit('submit','Submit') ?></td>
		</tr>
		
		
		
	</table>
	</form>
	
	
	
	<h3><span>All Entries</span></h3>
	
	<form action="./<?= section ?>.php" method="post">
		<h4>
			Sort By:
			
			<select name="sort" id="listing_sort" class="<?= section ?>_sort">
				<option value="id">ID</option>
				
				<? 
					$key = array_search("description", $_listing_fields);
					unset($_listing_fields[$key]);
					
					$key = array_search("setlist", $_listing_fields);
					unset($_listing_fields[$key]);
					
					$key = array_search("tickets_url", $_listing_fields);
					unset($_listing_fields[$key]);
					
					$key = array_search("venue_url", $_listing_fields);
					unset($_listing_fields[$key]);
					
					$key = array_search("name", $_listing_fields);
					unset($_listing_fields[$key]);
					
					$key = array_search("fb_event_url", $_listing_fields);
					unset($_listing_fields[$key]);
					
					$key = array_search("age", $_listing_fields);
					unset($_listing_fields[$key]);
					
					$key = array_search("door_time", $_listing_fields);
					unset($_listing_fields[$key]);
					
					$key = array_search("details", $_listing_fields);
					unset($_listing_fields[$key]);
					
					if (is_array($_listing_fields)) foreach ($_listing_fields as $field) {
				?>
				<option value="<?= $field ?>"<?= $field == $_REQUEST['sort'] ? ' selected="selected"' : '' ?>><?= ucwords(str_replace('_',' ',$field)) ?></option>
				<? } ?>
			</select>
		</h4>
	
		<? input_hidden('action','delete') ?>
	
		<table id="listing_table" cellspacing="0">
			<tr>
				<th>&nbsp;</th>
				<th>ID</th>
				
				<? if (is_array($_listing_fields)) foreach ($_listing_fields as $field) { ?>
				<th><?= ucwords(str_replace('_',' ',$field)) ?></th>
				<? } ?>
			</tr>
			
			<?
				if (get_rows('SELECT * FROM `' . $_table . '` ORDER BY `' . $sort . '` DESC'))  while ($row = get_rows()) {
					$row_class = ($row_class == 'row_odd') ? 'row_even' : 'row_odd';
			?>
			<tr class="<?= $row_class ?>">
				<td width="5%" class="checkbox"><?= input_checkbox('delete[' . $row['id'] . ']',1) ?></td>
				<td width="5%"><label for="checkbox_delete[<?= $row['id'] ?>]_1"><?= $row['id'] ?></label></td>
				
				<? if (is_array($_listing_fields)) foreach ($_listing_fields as $field) { ?>
				<td width="<?= floor(90 / count($_listing_fields)) ?>%"><a href="./<?= section ?>.php?edit=<?= $row['id'] ?>"><?= shorten(strip_tags($row[$field]), floor(100 / count($_listing_fields))) ?></a></td>
				<? } ?>
			</tr>
			
			<?
				}
			?>
		</table>
		
		<div class="submit">
			<? input_submit('submit','Delete Selected Entries') ?>
		</div>
	</form>
<?
	include('includes/footer.php');
?>