<?
	include('includes/config.php');
	
	
	if ($_REQUEST['action'] == 'submit') {
		$_SESSION['form_error'] = array();
		
		validate('username');
		validate('password');
		
		$logged_in_id = get_one('SELECT `id` FROM `users` WHERE `username` = \'' . $_SESSION['form_data']['username'] . '\' AND `password` = \'' . md5($_SESSION['form_data']['password']) . '\' LIMIT 1');
		
		
		if ($logged_in_id) {
			$_SESSION['logged_in_id'] = $logged_in_id;
			redirect('./index.php');
		}
	}
	
	
	
	include('includes/header.php');
?>

	<form action="./<?= section ?>.php" method="post">
	<?= input_hidden('action','submit') ?>
	
	<table id="login_form">
		<tr>
			<th>Username</th>
			<td><?= input_text('username') ?></td>
		</tr>

		<tr>
			<th>Password</th>
			<td><?= input_password('password') ?></td>
		</tr>
		
		<tr>
			<td colspan="2"><?= input_submit('submit','Log In') ?></td>
		</tr>
	</table>
	</form>

<?
	include('includes/footer.php');
?>