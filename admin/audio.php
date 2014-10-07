<?
	include('includes/config.php');
	$_table = section;
	$_listing_fields = generate_fields($_table);
	
	
	if ($_GET['cat_id']) {
		$_SESSION['form_data']['category_id'] = $_GET['cat_id'];
	}
	
	//submit
	if ($_REQUEST['action'] == 'submit') {
		$_SESSION['form_error'] = array();
		
		validate("title");
		validate('sort', false);
		validate("song", false, array(
			'upload' => array()
		));		
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
				
				validate("song", false, array(
					'upload' => array(
						'id' => $id
					)
				));

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
				<th>Track Title</th>
				<td class="<?= isset($_SESSION['form_error']['title']) ? ' error' : '' ?>"><? input_text('title') ?></td>
			</tr>
			
			<tr>
				<th>Song</th>
				<td class="<?= isset($_SESSION['form_error']['song']) ? ' error' : '' ?>"><? input_file('song') ?></td>
			</tr>
			
			<tr>
				<th>Sort</th>
				<td class="<?= isset($_SESSION['form_error']['sort']) ? ' error' : '' ?>"><? input_text('sort') ?></td>
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
			
			<?
				if (get_rows('SELECT * FROM `' . $_table . '` ORDER BY `' . $sort . '` DESC'))  while ($row = get_rows()) {
					$row_class = ($row_class == 'row_odd') ? 'row_even' : 'row_odd';
			?>
			<tr class="<?= $row_class ?>">
				<td width="5%" class="checkbox"><?= input_checkbox('delete[' . $row['id'] . ']',1) ?></td>
				<td width="5%"><label for="checkbox_delete[<?= $row['id'] ?>]_1"><?= $row['id'] ?></label></td>
				<? if (is_array($_listing_fields)) foreach ($_listing_fields as $field){?>
					<td width="<?= floor(90 / count($_listing_fields)) ?>%"><a href="./<?= section ?>.php?edit=<?= $row['id'] ?>"><?= shorten($row[$field], floor(60 / count($_listing_fields))) ?></a></td>
				<? } ?>
				<? } ?>
			</tr>
			
		</table>
		
		<div class="submit">
			<? input_submit('submit','Delete Selected Entries') ?>
		</div>
	</form>
	
	<script type="text/javascript">
		function catSelect(targ,selObj,restore){ //v3.0
			eval(targ+".location='<?= section ?>.php?cat_id="+selObj.options[selObj.selectedIndex].value+"'");
			if (restore) selObj.selectedIndex=0;
		}
		
		$(document).ready(function(){
			
			if ($('#on_sale:checked').val() == true) {
				$('#sale_price').show();
			} else {
				$('#sale_price').hide();
			}
			
			$('#on_sale').change(function(){
				if ($('#on_sale:checked').val() == true) {
					$('#sale_price').show();
				} else {
					$('#sale_price').hide();
				}
			});
		});
	</script>


<?
	include('includes/footer.php');
?>