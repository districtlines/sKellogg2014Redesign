<?
	include('includes/config.php');
	$_table = section;
	$_listing_fields = generate_fields($_table);
	$album_id = $_GET['album'];
	
	//submit
	if ($_REQUEST['action'] == 'submit') {
		$_SESSION['form_error'] = array();
		
		validate('album_id');
		validate('name');
		validate('photo', false, array(
			'upload' => array()
		));
		validate('sticky',false);
		validate('showing',false);
		if ($_FILES['photo']['name']) {
			$fixedPath = ereg_replace('([^A-Za-z0-9_\. -]+)', '_', $_FILES['photo']['name']);
			$_SESSION['form_data']['photo'] = 'full_' . $fixedPath;
		}
		
		if (is_numeric($_REQUEST['id'])) {
			$id = '`id` = ' . $_REQUEST['id'];
		} else {
			$new_entry = true;
		}
		
		if(!$newEntry)
		{
			$sql = "DELETE FROM recent_activity WHERE type = 'photos' AND type_id = '" . $_REQUEST['id'] . "'";
			$query = mysql_query($sql) or die(mysql_error());
		}
		
		if (!count($_SESSION['form_error'])) {
			
			set_times($id);
			
			if ($_FILES['photo']['name']) {
				$_SESSION['form_data']['thumbnail'] = 'thumb_' . $fixedPath;
			}
			
			if (save_row($_table, $_SESSION['form_data'], $id)) {
			
			if ($id) {
				$id = $_REQUEST['id'];
			} else {
				$id = mysql_insert_id();
			}
			
			if(!$_SESSION['form_data']['thumbnail']) {
			
				$sql = "SELECT thumbnail FROM photos WHERE id = $id LIMIT 1";
				$query = mysql_query($sql);
				if(mysql_num_rows($query)) {
				
					$_SESSION['form_data']['thumbnail'] = mysql_result($query,0);
				
				}
			
			}
					
			$typeSql = 'SELECT id FROM photos WHERE name = \'' . $_SESSION['form_data']['name'] . '\'';
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
				$sql = "SELECT sticky FROM photos WHERE id='$typeID'";
				$query = mysql_query($sql);
				$check = mysql_num_rows($query);
				if($check)
				{
					while($row = mysql_fetch_assoc($query))
					{
						$sticky = $row['sticky'];

							$sqlSticky = "INSERT INTO stickies (title,created,modified,type,type_id) values ('" . $_SESSION['form_data']['name'] . "', NOW(), NOW(), 'photos','" . $typeID . "')";
					}
				}
				}
					
				
				if($_SESSION['form_data']['sticky'] == "1" && $newEntry)
				{
					$sqlSticky = "INSERT INTO stickies (title,created,modified,type,type_id) values ('" . $_SESSION['form_data']['name'] . "',NOW(), NOW(), 'photos','" . $typeID . "')";
					$querySticky = mysql_query($sqlSticky) or die(mysql_error());
				}
				
				if($_SESSION['form_data']['sticky'] != "1" && !$newEntry)
				{
						
						$sqlSticky = "DELETE FROM stickies WHERE type = 'photos' AND type_id = '" . $typeID . "'";
						$querySticky = mysql_query($sqlSticky) or die(mysql_error());
				
				}
				
				if($_SESSION['form_data']['sticky'] == "1" && !$newEntry)
				{
					$sql = "SELECT * FROM stickies WHERE type_id='$typeID' and type='photos'";
					$query = mysql_query($sql);
					$check = mysql_num_rows($query);
					if($check)
					{
						$sqlSticky = "UPDATE stickies SET title = '" . $_SESSION['form_data']['name'] . "', created = NOW(), modified = NOW() WHERE type = 'photos' AND type_id = '$typeID'";
						$querySticky = mysql_query($sqlSticky) or die(mysql_error());
					}else{
						$sqlSticky = "INSERT INTO stickies (title,created,modified,type,type_id) values ('" . $_SESSION['form_data']['name'] . "', NOW(), NOW(), 'photos','" . $typeID . "')";
					$querySticky = mysql_query($sqlSticky) or die(mysql_error());
					}
				}
				
				if($_SESSION['form_data']['showing'] == "1")
				{
					$recentSql = "INSERT INTO recent_activity (title,created,modified,type,type_id,sticky) VALUES ('" . $_SESSION['form_data']['name'] . "'," . "NOW(), NOW(),'photos','" . $typeID . "','" . $_SESSION['form_data']['sticky'] . "')";
					$recentQuery = mysql_query($recentSql) or die(mysql_error());
				}
			
				validate('photo', false, array(
					'upload' => array(
						'id' => $id
					),
					'thumbnails' => array(
						'thumb' => array(
							'width' => 160,
							'height' => 170,
							'scale_type' => 'scale-crop2'
						),
						'full' => array(
							'width' => 815,
							'height' => 680,
							'scale_type' => 'scale-crop2'
						)
					),
				));	
				$_SESSION['message'] = clean_section . ' Entry Saved!';
				$_SESSION['form_data'] = array();
			}
		}
		
		redirect('./' . section . '.php?album=' . $album_id);
	}
	//delete
	if ($_REQUEST['action'] == 'delete') {
		if (is_array($_REQUEST['delete'])) {
			foreach ($_REQUEST['delete'] as $delete_id => $delete) {
				if ($delete) {
					$sql = "SELECT photo, name,thumbnail FROM ". $_table ." WHERE id = '$delete_id'";
					$query = mysql_query($sql) or die(mysql_error());
					$row = mysql_fetch_assoc($query);
					$image = $row['photo'];
					$thumbnail = $row['thumbnail'];
					$name = $row['name'];	
					unlink('../' . $_table . '/' . $delete_id . '/' . $image);
					unlink('../' . $_table . '/' . $delete_id . '/' . $thumbnail);
					unlink('../' . $_table . '/' . $delete_id . '/' . substr($image, 5));
					rmdir('../' . $_table . '/' . $delete_id);
					mysql_query('DELETE FROM `' . $_table . '` WHERE `id` = \'' . $delete_id . '\' LIMIT 1') or die(mysql_error());
					mysql_query("DELETE FROM recent_activity WHERE title='$name' AND type='photos' LIMIT 1") or die(mysql_error());
					mysql_query("DELETE FROM stickies WHERE title='$name' AND type='photos' LIMIT 1") or die(mysql_error());
				}
			}
			
			$_SESSION['message'] = clean_section . ' Entries Deleted!';
		} else {
			$_SESSION['message'] = 'No ' . clean_section . ' Entries Selected!';
		}
		
		redirect('./' . section . '.php?album=' . $album_id);
	}
	
	
	
	include('includes/header.php');
?>

	<a href="photo_albums.php?edit=<?=$album_id ?>">Back to Album</a>
	
	
	<?
		if(isset($_SESSION['success']))
		{	
			echo '<p>';
			echo $_SESSION['success'];
			echo '</p>';
			$_SESSION['success'] = "";
		}
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
	
	<form action="./<?= section ?>.php?album=<?=$album_id?>" method="post" id="add_form" enctype="multipart/form-data">
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
			
			<?php

			$sql = "SELECT name FROM photo_albums WHERE id = '". $album_id ."'";
			$query = mysql_query($sql) or die(mysql_error());
			$check = mysql_num_rows($query);
			if ($check) {
				$gallery_name = mysql_result($query, 0);	
			}
			echo "Add a photo to <strong>" . stripslashes($gallery_name) . "</strong>";
			?>
			<input type="hidden" name="album_id" value="<?= $album_id ?>" />
			</td>
		</tr>
      
		<tr>
			<th>Name</th>
			<td class="<?= isset($_SESSION['form_error']['name']) ? ' error' : '' ?>"><? input_text('name') ?></td>
		</tr>
		
		<tr>
			<th>Photo <small>815x680 (wxh)</small></th>
			<td class="<?= isset($_SESSION['form_error']['photo']) ? ' error' : '' ?>"><? input_file('photo') ?></td>
		</tr>
					     	
		<tr>
			<th>Sort</th>
			<td><? input_text('sort') ?></td>
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
	
	<form action="./<?= section ?>.php?album=<?=$album_id?>" method="post">
		<h4 style="border: 1px #000 solid;padding: 5px;background: #0f0; width: 600px;">This list can be dragged and dropped into order.</h4>
	
		<?
		input_hidden('action','delete');
		input_hidden('album_id', $album_id);
		
		$sql = "SELECT id, name FROM gallery";
		$query = mysql_query($sql) or die(mysql_error());
		$check = mysql_num_rows($query);
		if ($check) {
			$gallery = array();
			while ($gallery = mysql_fetch_assoc($query)) {
				$gallery[$gallery['id']] = stripslashes($gallery['name']);
			}	
		}
		?>
	
		<table id="listing_table" cellspacing="0">
			<tr>
				<th>&nbsp;</th>
				<th>ID</th>
				
				<?
				if (is_array($_listing_fields)) foreach ($_listing_fields as $field) {
					if ($field == "album_id") {

					}
				?>
				<th><?= ucwords(str_replace('_',' ',$field)) ?></th>
				<? } ?>
				<th></th>
				<th></th>
			</tr>
			
			<?
				if (get_rows("SELECT * FROM $_table WHERE album_id='$album_id'  ORDER BY `sort` ASC"))  while ($row = get_rows()) {
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
				<td width="<?= floor(90 / count($_listing_fields)) ?>%"><a href="./<?= section ?>.php?edit=<?= $row['id'] ?>&album=<?= $album_id ?>"><?= shorten(strip_tags($row[$field]), floor(60 / count($_listing_fields))) ?></a></td>
				<? } ?>
				<td><a href="cover.php?galleryID=<?=$album_id?>&coverID=<?=$row['id']?>" title="Set as cover image">Set as Cover</a></td>
				<td>
					<a class="fancybox" href="/uploads/photos/<?php echo $row['id']; ?>/<?php echo str_replace('full_', '', $row['photo']); ?>">Preview</a>
				</td>
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
			
			$(".fancybox").fancybox();
			
		});
	</script>
	
<?
	include('includes/footer.php');
?>