<?
	include('includes/config.php');
	$_table = section;
	$_listing_fields = generate_fields($_table);
	
	
	//submit
	if ($_REQUEST['action'] == 'submit') {
		$_SESSION['form_error'] = array();
		
		validate('name');
		validate('sort', false);
		
		if (is_numeric($_REQUEST['id'])) {
			$id = '`id` = ' . $_REQUEST['id'];
		}
		
		if (!count($_SESSION['form_error'])) {
			set_times($id);
			
			if (save_row($_table, $_SESSION['form_data'], $id)) {
				if ($id) {
					$id = $_REQUEST['id'];
				} else {
					$id = mysql_insert_id();
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
					mysql_query('DELETE FROM `' . $_table . '` WHERE `id` = \'' . $delete_id . '\' LIMIT 1') or die(mysql_error());
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
			<th>Sort</th>
			<td><? input_text('sort') ?></td>
		</tr>
		
		<? if ($_REQUEST['edit']) { ?>
		<tr>
			<th></th>
			<td><a href="photos.php?gallery_id=<?= $_REQUEST['edit'] ?>">Add / Edit Photos</a></td>
		</tr>
		
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
		<h4 style="border: 1px #000 solid;padding: 5px;background: #0f0; width: 730px;">This list can be dragged and dropped into order.</h4>
	
		<? input_hidden('action','delete') ?>
	
		<table id="listing_table" cellspacing="0" style="width: 750px;">
			<tr>
				<th>&nbsp;</th>
				<th>ID</th>
				
				<?
				   $_listing_fields[] = "photos";
				
				   if (is_array($_listing_fields)) foreach ($_listing_fields as $field) { ?>
				<th><?= ucwords(str_replace('_',' ',$field)) ?></th>
				<? } ?>
				<th></th>
			</tr>
			
			<?
				if (get_rows('SELECT *, (SELECT COUNT(id) FROM photos WHERE gallery_id = '.$_table.'.id) AS photos FROM `' . $_table . '` ORDER BY `sort` ASC'))  while ($row = get_rows()) {
					$row_class = ($row_class == 'row_odd') ? 'row_even' : 'row_odd';
			?>
			<tr class="<?= $row_class ?>" id="listing_table_row_<?= $row['id'] ?>">
				<td width="5%" class="checkbox"><?= input_checkbox('delete[' . $row['id'] . ']',1) ?></td>
				<td width="5%"><label for="checkbox_delete[<?= $row['id'] ?>]_1"><?= $row['id'] ?></label></td>
				<?php
				if (is_array($_listing_fields)) foreach ($_listing_fields as $field) {
					if ($field == "active") {
						$row[$field] = $row[$field] ? "Yes" : "No";
					}
				?>
				<td width="<?= floor(64 / count($_listing_fields)) ?>%"><a href="./<?= section ?>.php?edit=<?= $row['id'] ?>"><?= shorten(strip_tags($row[$field]), floor(60 / count($_listing_fields))) ?></a></td>
				<? 
				}
				?>
				<td width="16%"><a href="photos.php?gallery_id=<?= $row['id'] ?>">Add/Edit Photos</a></td>
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