<?
	include('includes/config.php');
	$_table = section;
	$_listing_fields = generate_fields($_table);
	
	
	//submit
	if ($_REQUEST['action'] == 'submit') {
		$_SESSION['form_error'] = array();
		
		
		validate('title');
		
		if($_SESSION['form_data']['title'] == 'sidebar_album') { 
			
			validate('body', false, array(
				'upload' => array()
			));
			
		} else {
			
			validate('body');
		
		}
		
		if($_SESSION['form_data']['title'] == 'sidebar_soundcloud') {
		
			validate('name',false);
		
		}
		
		if (is_numeric($_REQUEST['id'])) {
			$id = '`id` = ' . $_REQUEST['id'];
		}else{
/* 			$newEntry = true; */
		}
		
		if (!count($_SESSION['form_error'])) {
			set_times($id);
			
			
			
			if (save_row($_table, $_SESSION['form_data'], $id)) {
						
				if ($id) {
					$id = $_REQUEST['id'];
				} else {
					$id = mysql_insert_id();
				}	
				
				if($_SESSION['form_data']['title'] == 'sidebar_album') {
				
					validate('body', false, array(
						'upload' => array(
							'id' => $id
						)
						,
						'thumbnails' => array(
							'thumb' => array(
								'width' => 325,
								'height' => 325,
								'scale_type' => 'scale-crop2'
							)
						)
					));
					
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
	<? if ($_REQUEST['edit']) { ?>
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
		<tr>
			<th>ID</th>
			<td><? input_text('id',$_REQUEST['edit'],' disabled="disabled"') ?></td>
		</tr>

		<?
			$_keys = get_col('SELECT `id` FROM `content_pages` ORDER BY `title` ASC');
			$_displays = get_col('SELECT `title` FROM `content_pages` ORDER BY `title` ASC');
      
      array_unshift($_keys, '-1');
			array_unshift($_displays, 'No parent page');
			
			$body = get_one('SELECT `body` FROM `content_pages` WHERE `id` = \'' . $_REQUEST['edit'] . '\'');
		?>
      
		<tr>
			<th>Section</th>
			<td class="<?= isset($_SESSION['form_error']['title']) ? ' error' : '' ?>"><? input_text('title','','disabled="disabled"') ?></td>
		</tr>
		
		<input type="hidden" name="title" value="<?=$_SESSION['form_data']['title']?>" />
		
		
		<? if($_SESSION['form_data']['title'] == 'sidebar_soundcloud') { ?>
			
			<tr>
				<th>Soundcloud Title</th>
				<td class="<?= isset($_SESSION['form_error']['name']) ? ' error' : '' ?>"><? input_text('name') ?></td>
			</tr>
		
		<?
		}
		
		?>
		
		
		<tr>
			<th>Body<br /><?=$_SESSION['form_data']['title'] == 'sidebar_album' ? '<span style="font-size:11px;">Required size 325 x 325</span>' : '';?></th>
			
			<td class="<?= isset($_SESSION['form_error']['body']) ? ' error' : '' ?>"><? 
				if($_SESSION['form_data']['title'] == 'sidebar_soundcloud') {
					
					textarea('body',$body); 
				
				} else if($_SESSION['form_data']['title'] == 'sidebar_album'){
					
					input_file('body');
				} else if($_SESSION['form_data']['title'] == 'contact_email' || strstr($_SESSION['form_data']['title'],'_url')) {
				
					
					input_text('body',$body);
					
				} else {
				
					textarea('body',$body,'id="ignore_tf"'); 
				
				}
			?></td>
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
	<?php
	}
	?>
	
	
	<h3><span>All Entries</span></h3>
	

	<script type="text/javascript">
	<!--
		tinyMCE.init({
			mode : "exact",
			elements : "rte_body",
			theme : "advanced",
			theme_advanced_buttons1 : "bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,bullist,numlist,undo,redo,link,unlink,image",
			theme_advanced_buttons2 : "",
			theme_advanced_buttons3 : "",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]"
		});
	-->
	</script>
	
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
		
		<? 
			if($_SERVER['REMOTE_ADDR'] == '71.48.252.181') {
		?>
		<div class="submit">
			<? input_submit('submit','Delete Selected Entries') ?>
		</div>
		<? } ?>
	</form>
<?
	include('includes/footer.php');
?>