
<? $active = 'contact'; ?>
<? $pageTitle = 'Contact'; ?>
<? $curr_page = 'contact'; ?>
<? include('./includes/header.php'); ?>
<?
	if($_POST)
	{
		$fname = $_REQUEST['fname'];
		$lname = $_REQUEST['lname'];
		$email = $_REQUEST['email'];
		$phone = $_REQUEST['phone'];
		$comments = $_REQUEST['comments'];

		$errors = array();
		
		$sql = "SELECT body FROM content_pages WHERE title = 'contact_email' LIMIT 1";
		$res = mysql_query($sql) or die(mysql_error());
		$formEmail = mysql_fetch_row($res);
		
		if(empty($phone)) 
	    {
	    	$phone = "N/A";
		}		
		if(empty($fname)) {
			$errors[] = 'Please insert your first name.';
		}
		if(empty($lname)) {
			$errors[] = 'Please insert your last name.';
		}
		
		if(empty($email)) {
			$errors[] = 'Please insert an email address.';
		}
		
		$name = $fname . ' ' . $lname;
		
		if(empty($errors)) {
	    $from = $email;
		$to = $formEmail[0];	    
	    
	    $subject = "Stephen Kellogg and the Sixers";
	    $headers =
	            "MIME-Version: 1.0" . "\r\n" .
	            "Content-type: text/html; charset=iso-8859-1" . "\r\n" .
	            "To: Stephen Kellog <" . $to . ">" . "\r\n" .
	            "From: " . $name . " <" . $from . ">" . "\r\n";
	            "CC: $cc \r\n";
	    $body =
	            "<b>Name:</b> " . $name . "<br />" .
	            "<b>E-Mail:</b> " . $email . "<br />" .
	            "<b>Phone:</b> " . $phone . "<br />" .
	            "<b>Message Body:</b><br />" .
	            $comments;

	    if(!mail($to, $subject, $body, $headers)) {
	    	$status = "Error sending the form please try again.";
	    } else {
	    	$success = "We'll get back to you shortly. <br /> Thank you!";
	    }
		}
	}else{

	}
?>

	<div class="events">
		<h2 class="heading">Contact</h2>
		
		<div id="contact_form">
		<?php
			if(!empty($errors)) {
		?>
			<div class="notice">
				<?php
					echo "<ul class=\"errors\">";
					foreach($errors as $error)
					{
						echo "<li>$error</li>";
					}
					echo "</ul><div class=\"clear\"></div>";
				?>
			</div>
		<? } ?>
		
		<? if($success)
				{
					echo $success;
				?>
					
					
					<form method="" action="">
					
						<div class="left">
							<label for="fname">First Name: </label>
							<input type="text" name="fname" disabled="disabled" value="<?=$fname?>" />
							<div class="clear"></div>
						</div>
						
						<div class="right">
							<label for="lname">Last Name: </label>
							<input type="text" name="lname" disabled="disabled" value="<?=$lname?>" />
							<div class="clear"></div>
						</div>
						
						<div class="clear"></div>
						
						<div class="left">
							<label for="email">Email: </label>
							<input type="text" name="email" disabled="disabled" value="<?=$email?>" />
							<div class="clear"></div>
						</div>
						
						<div class="right">
							<label for="phone">Phone: </label>
							<input type="text" name="phone" disabled="disabled" value="<?=$phone?>" />
							<div class="clear"></div>
						</div>
						
						<div class="clear"></div>
						
						<div>
							<label for="comments">Comments: </label>
							<textarea name="comments" disabled="disabled"><?=$comments?></textarea>
							<div class="clear"></div>
						</div>
						
						<input id="form_submit" class="button" type="submit" value="Send" disabled="disabled" />
						
					</form>
					
					
					
				<?	
					
				}else{
				?>
					<form method="post" action="contact.php">
					
						<div class="left">
							<label for="fname">First Name: </label>
							<input type="text" name="fname" />
							<div class="clear"></div>
						</div>
						
						<div class="right">
							<label for="lname">Last Name: </label>
							<input type="text" name="lname" />
							<div class="clear"></div>
						</div>
						
						<div class="clear"></div>
						
						<div class="left">
							<label for="email">Email: </label>
							<input type="text" name="email" />
							<div class="clear"></div>
						</div>
						
						<div class="right">
							<label for="phone">Phone: </label>
							<input type="text" name="phone" />
							<div class="clear"></div>
						</div>
						
						<div class="clear"></div>
						
						<div>
							<label for="comments">Comments: </label>
							<textarea name="comments"></textarea>
							<div class="clear"></div>
						</div>
						
						<input id="form_submit" class="button" type="submit" value="Send" />
						
					</form>
				<?
				}
				?>
		</div><!-- END contact_form -->
		
		<div id="contact_contacts">
			<?
					$sql = "SELECT * FROM content_pages WHERE title='contact'";
					$query = mysql_query($sql);
					$check = mysql_num_rows($query);
					if($check)
					{	
						while($row=mysql_fetch_assoc($query))
						{
							echo "<div class=\"user_input\">".nl2br($row['body'])."</div>";
						}
					}else{
						echo "<p>No Content has been added.</p><hr />";
					}	
				?>
		</div>
	</div>
</div>

</div><!-- end leftCntr -->

 
<script type="text/javascript">
$(document).ready(function() {
	$(".desc_btn").click(function() {
		var btnID = $(this).attr('rel');
		console.log(btnID);
		if($(this).hasClass('hidden')) {
			$(this).attr('class','visible desc_btn');
			$(this).html('-');
			$("#description_"+btnID).find('td').attr('class', 'description visible');
			console.log($("#description_"+btnID).find('td'));
		} else {
			$(this).attr('class','hidden desc_btn');
			$(this).html('+');
			$("#description_"+btnID).find('td').attr('class', 'description hidden');
		}
		
		return false;
	});
});
</script>
				
<!-- <? include('./includes/sidebar.php'); ?> -->

<? include('./includes/footer.php'); ?>