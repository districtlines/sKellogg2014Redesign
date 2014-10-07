<?
	include('includes/config.php');
	$_table = section;
	$_listing_fields = generate_fields($_table);
	
	
	//submit
	if ($_REQUEST['action'] == 'submit') {
		$_SESSION['form_error'] = array();
		
		validate('title');
		validate('content');
		
		validate('sticky',false);
		validate('showing',false);
		validate('summary', true);
		
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
		
		
		
				
		if (is_numeric($_REQUEST['id'])) {
			$id = '`id` = ' . $_REQUEST['id'];
		}else{
			$newEntry = true;
		}
		
		if ($_POST['date']) {
			$_SESSION['form_data']['date'] = strtotime($_POST['date']);
		}
			
		//$_SESSION['form_data']['summary'] = substr($_SESSION['form_data']['content'], 0, 350);
		
		if(!$newEntry)
		{
			$sql = "DELETE FROM recent_activity WHERE type = 'news' AND type_id = '" . $_REQUEST['id'] . "'";
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
			
				$sql = "UPDATE news SET show_date = '{$_SESSION['form_data']['show_date']}' WHERE id = $id";
					
				mysql_query($sql);
			
				//ADD INTO RECENT ACTIVITIES
				$typeSql = 'SELECT id FROM news WHERE title = \'' . $_SESSION['form_data']['title'] . '\'';
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
				$sql = "SELECT sticky FROM news WHERE id='$typeID'";
				$query = mysql_query($sql);
				$check = mysql_num_rows($query);
				if($check)
				{
					while($row = mysql_fetch_assoc($query))
					{
						$sticky = $row['sticky'];

							$sqlSticky = "INSERT INTO stickies (title,content,created,modified,type,type_id) values ('" . $_SESSION['form_data']['title'] . "','" . $_SESSION['form_data']['content'] . "', NOW(), NOW(), 'news','" . $typeID . "')";
					}
				}
				}
					
				
				if($_SESSION['form_data']['sticky'] == "1" && $newEntry)
				{
					$sqlSticky = "INSERT INTO stickies (title,content,created,modified,type,type_id) values ('" . $_SESSION['form_data']['title'] . "','" . $_SESSION['form_data']['content'] . "', NOW(), NOW(), 'news','" . $typeID . "')";
					$querySticky = mysql_query($sqlSticky) or die(mysql_error());
				}
				
				if($_SESSION['form_data']['sticky'] != "1" && !$newEntry)
				{
						
						$sqlSticky = "DELETE FROM stickies WHERE type = 'news' AND type_id = '" . $typeID . "'";
						$querySticky = mysql_query($sqlSticky) or die(mysql_error());
				
				}
				
				if($_SESSION['form_data']['sticky'] == "1" && !$newEntry)
				{
					$sql = "SELECT * FROM stickies WHERE type_id='$typeID' and type='news'";
					$query = mysql_query($sql);
					$check = mysql_num_rows($query);
					if($check)
					{
						$sqlSticky = "UPDATE stickies SET title = '" . $_SESSION['form_data']['title'] . "',content = '" . $_SESSION['content'] . "', created = NOW(), modified = NOW() WHERE type = 'news' AND type_id = '$typeID'";
						$querySticky = mysql_query($sqlSticky) or die(mysql_error());
					}else{
						$sqlSticky = "INSERT INTO stickies (title,content,created,modified,type,type_id) values ('" . $_SESSION['form_data']['title'] . "','" . $_SESSION['form_data']['content'] . "', NOW(), NOW(), 'news','" . $typeID . "')";
					$querySticky = mysql_query($sqlSticky) or die(mysql_error());
					}
				}
				if($_SESSION['form_data']['showing'] == "1")
				{
					$recentSql = "INSERT INTO recent_activity (title,content,created,modified,type,type_id,sticky) VALUES ('" . $_SESSION['form_data']['title'] . "','" . $_SESSION['form_data']['content'] . "', NOW(), NOW(),'news','" . $typeID . "','" . $_SESSION['form_data']['sticky'] . "')";
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
					$sql = "SELECT title FROM ". $_table ." WHERE id = '$delete_id'";
					$query = mysql_query($sql) or die(mysql_error());
					$row = mysql_fetch_assoc($query);
					$name = $row['title'];
					mysql_query('DELETE FROM `' . $_table . '` WHERE `id` = \'' . $delete_id . '\' LIMIT 1') or die(mysql_error());
					mysql_query("DELETE FROM recent_activity WHERE title='$name' AND type='news' LIMIT 1") or die(mysql_error());
					mysql_query("DELETE FROM stickies WHERE title='$name' AND type='news' LIMIT 1") or die(mysql_error());
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

		<?
			$_keys = get_col('SELECT `id` FROM `news` ORDER BY `title` ASC');
			$_displays = get_col('SELECT `title` FROM `news` ORDER BY `title` ASC');
      
      array_unshift($_keys, '-1');
			array_unshift($_displays, 'No parent page');
			
			$body = get_one('SELECT `content` FROM `news` WHERE `id` = \'' . $_REQUEST['edit'] . '\'');
		?>
      
		<tr>
			<th>Title</th>
			<td class="<?= isset($_SESSION['form_error']['title']) ? ' error' : '' ?>"><? input_text('title') ?></td>
		</tr>
		
		<tr>
			<th>Date</th>
			<td class="<?= isset($_SESSION['form_error']['date']) ? ' error' : '' ?>"><? input_text('date','','class="date-pick"') ?></td>
		</tr>
		
	
		
        
        <tr>
			<th>Summary<br /><span style="font-size:11px;">Cannot exceed 350 characters</span></th>
			<td class="<?= isset($_SESSION['form_error']['summary']) ? ' error' : '' ?>"><textarea name="summary" id="ignore_tf"><?=stripslashes(strip_tags($_SESSION['form_data']['summary'], '<a>'));?></textarea></td>
		</tr>
        
		<tr>
			<th>Content</th>
			<td class="<?= isset($_SESSION['form_error']['content']) ? ' error' : '' ?>"><textarea name="content" id="ignore_tf2"><?=stripslashes($_SESSION['form_data']['content']);?></textarea></td>
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
	

	<script type="text/javascript">
	/*<!--
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
	-->*/
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
		
		<div class="submit">
			<? input_submit('submit','Delete Selected Entries') ?>
		</div>
	</form>
<?
	include('includes/footer.php');
?>