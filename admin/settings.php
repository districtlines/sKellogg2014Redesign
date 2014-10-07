<?
	include('includes/config.php');
	$_table = section;
	$_listing_fields = generate_fields($_table);
	
	
	//submit
	if ($_REQUEST['action'] == 'submit') {
		$_SESSION['form_error'] = array();
		
		validate('name');
		validate('description');
		validate('data');
		
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
			<th>Description</th>
			<td class="<?= isset($_SESSION['form_error']['description']) ? ' error' : '' ?>">
				<textarea name="description" id="description_field"><?= $_SESSION['form_data']['description'] ?></textarea>
			</td>
		</tr>
		
		<tr>
			<th>Data</th>
			<td class="<?= isset($_SESSION['form_error']['data']) ? ' error' : '' ?>">
				<textarea name="data" id="data_field"><?= $_SESSION['form_data']['data'] ?></textarea>
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
		<h4>
			Sort By:
			
			<select name="sort" id="listing_sort" class="<?= section ?>_sort">
				<option value="id">ID</option>
				
				<? if (is_array($_listing_fields)) foreach ($_listing_fields as $field) { ?>
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
				<td width="<?= floor(90 / count($_listing_fields)) ?>%"><a href="./<?= section ?>.php?edit=<?= $row['id'] ?>"><?= shorten(strip_tags($row[$field]), floor(60 / count($_listing_fields))) ?></a></td>
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