<?	
	include('includes/config.php');
	$_table = section;
	$_listing_fields = generate_fields($_table);
	
	
	//submit
	if ($_REQUEST['action'] == 'submit') {
		$_SESSION['form_error'] = array();
		
		validate('name');
		validate('code');
		validate('active',false);
		
		//Get Video Thumbnail
		$thumbsIDs = array();
		$embed = stripslashes($_SESSION['form_data']['embed_code']);
		
		//Get Id of video
		preg_match('/embed\/([A-Za-z0-9]+)/', $embed, $thumbsIDs);
		
		$embed_id = $thumbsIDs[1];
		
		$_SESSION['form_data']['embed_id'] = $embed_id;
		
		if (is_numeric($_REQUEST['id'])) {
			$id = '`id` = ' . $_REQUEST['id'];
		}else{
			$newEntry = true;
		}
		
		if(!$newEntry)
		{
			$sql = "DELETE FROM recent_activity WHERE type = 'videos' AND type_id = '" . $_REQUEST['id'] . "'";
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
				
				$typeSql = 'SELECT id FROM videos WHERE name = \'' . $_SESSION['form_data']['name'] . '\'';
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
					$sql = "SELECT sticky FROM videos WHERE id='$typeID'";
					$query = mysql_query($sql);
					$check = mysql_num_rows($query);
					if($check)
					{
						while($row = mysql_fetch_assoc($query))
						{
							$sticky = $row['sticky'];
	
								$sqlSticky = "INSERT INTO stickies (title,content,created,modified,type,type_id) values ('" . $_SESSION['form_data']['name'] . "','" . $_SESSION['form_data']['embed_code'] . "', NOW(), NOW(), 'videos','" . $typeID . "')";
						}
					}
					}
						
					
					if($_SESSION['form_data']['sticky'] == "1" && $newEntry)
					{
						$sqlSticky = "INSERT INTO stickies (title,content,created,modified,type,type_id) values ('" . $_SESSION['form_data']['name'] . "','" . $_SESSION['form_data']['embed_code'] . "', NOW(), NOW(), 'videos','" . $typeID . "')";
						$querySticky = mysql_query($sqlSticky) or die(mysql_error());
					}
					
					if($_SESSION['form_data']['sticky'] != "1" && !$newEntry)
					{
							
							$sqlSticky = "DELETE FROM stickies WHERE type = 'videos' AND type_id = '" . $typeID . "'";
							$querySticky = mysql_query($sqlSticky) or die(mysql_error());
					
					}
					
					if($_SESSION['form_data']['sticky'] == "1" && !$newEntry)
					{
						$sql = "SELECT * FROM stickies WHERE type_id='$typeID' and type='videos'";
						$query = mysql_query($sql);
						$check = mysql_num_rows($query);
						if($check)
						{
							$sqlSticky = "UPDATE stickies SET title = '" . $_SESSION['form_data']['name'] . "',content = '" . $_SESSION['content'] . "', created = NOW(), modified = NOW() WHERE type = 'videos' AND type_id = '$typeID'";
							$querySticky = mysql_query($sqlSticky) or die(mysql_error());
						}else{
							$sqlSticky = "INSERT INTO stickies (title,content,created,modified,type,type_id) values ('" . $_SESSION['form_data']['name'] . "','" . $_SESSION['form_data']['embed_code'] . "', NOW(), NOW(), 'videos','" . $typeID . "')";
						$querySticky = mysql_query($sqlSticky) or die(mysql_error());
						}
					}
					if($_SESSION['form_data']['showing'] == "1")
					{
						$recentSql = "INSERT INTO recent_activity (title,content,created,modified,type,type_id,sticky) VALUES ('" . $_SESSION['form_data']['name'] . "','" . $_SESSION['form_data']['embed_code'] . "', NOW(), NOW(),'videos','" . $typeID . "','" . $_SESSION['form_data']['sticky'] . "')";
						$recentQuery = mysql_query($recentSql) or die(mysql_error());
					}
					
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
					/*$query = mysql_query($sql) or die(mysql_error());
					$image = @mysql_result($query, 0);
					unlink('../' . $_table . '/' . $delete_id . '/' . $image);
					unlink('../' . $_table . '/' . $delete_id . '/thumb_' . $image);
					rmdir('../' . $_table . '/' . $delete_id);*/
					mysql_query('DELETE FROM `' . $_table . '` WHERE `id` = \'' . $delete_id . '\' LIMIT 1') or die(mysql_error());
					mysql_query("DELETE FROM recent_activity WHERE title='$name' AND type='video' LIMIT 1") or die(mysql_error());
					mysql_query("DELETE FROM stickies WHERE title='$name' AND type='videos' LIMIT 1") or die(mysql_error());
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
			$id = $_REQUEST['edit'];
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
        	<th>Soundcloud Code</th>
        	<td class="<?= isset($_SESSION['form_error']['code']) ? ' error' : '' ?>"><? textarea('code') ?></td>
        </tr>
        
         <tr>
        	<th>Active</th>
        	<td class="<?= isset($_SESSION['form_error']['code']) ? ' error' : '' ?>">
        	
        		<select name="active">
        			
        			<? 
        				if() {
        					
        					
        				} else {
        				
        				
        				}
        			?>
        			<option value="0"<? echo $_SESSION['form_data']['active'] == 1 ? ' selected="selected"' : '' ;?>>No</option>
        			<option value="1">Yes</option>
        		
        		</select>
        	
        	</td>
        </tr>
     
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
		<h4 style="border: 1px #000 solid;padding: 5px;background: #0f0; width: 600px;">This list can be dragged and dropped into order.</h4>
	
		<?
		input_hidden('action','delete');
		
		$sql = "SELECT id, name FROM videos";
		$query = mysql_query($sql) or die(mysql_error());
		$check = mysql_num_rows($query);
		if ($check) {
			$photo_albums = array();
			while ($album = mysql_fetch_assoc($query)) {
				$photo_albums[$album['id']] = stripslashes($album['name']);
			}	
		}
		?>
	
		<table id="listing_table" cellspacing="0">
			<tr>
				<th>&nbsp;</th>
				<th>ID</th>
				<?
				   
				
				   if (is_array($_listing_fields)) foreach ($_listing_fields as $field) { ?>
				<th><?= ucwords(str_replace('_',' ',$field)) ?></th>
				<? } ?>
				<th></th>
			</tr>
			
			<?
				if (get_rows('SELECT * FROM `' . $_table . '` ORDER BY `sort` ASC'))  while ($row = get_rows()) {
					$row_class = ($row_class == 'row_odd') ? 'row_even' : 'row_odd';
			?>
			<tr class="<?= $row_class ?>" id="listing_table_row_<?= $row['id'] ?>">
				<td width="5%" class="checkbox"><?= input_checkbox('delete[' . $row['id'] . ']',1) ?></td>
				<td width="5%"><label for="checkbox_delete[<?= $row['id'] ?>]_1"><?= $row['id'] ?></label></td>
				
				<? if (is_array($_listing_fields)) foreach ($_listing_fields as $field) {
					if ($field == "active") {
						$row[$field] = $row[$field] ? "Yes" : "No";
					}
				?>
				<td width="<?= floor(90 / count($_listing_fields)) ?>%"><a href="./<?= section ?>.php?edit=<?= $row['id'] ?>"><?= shorten(strip_tags(htmlspecialchars($row[$field])), floor(60 / count($_listing_fields))) ?></a></td>
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
	
	<script type="text/javascript">
		$(document).ready(function(){	
		
			$("#listing_table").tableDnD({
		    onDragClass: "myDragClass",
		    onDrop: function(table, row) {
		      var rows = table.tBodies[0].rows;
		      var ids = new Array;
		
		      for (var i=0; i<rows.length; i++) {
		      	ids[i] = rows[i].id.replace('listing_table_row_','');
		      }
		      
		      $.get('ajax_sort.php', {'table': '<?= $_table ?>', 'ids[]': ids});
				}
			});
		
		});
	</script>
	
<?
	include('includes/footer.php');
?>