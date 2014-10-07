<?
	include('includes/config.php');
	
	if($_POST['email']) {
		
		$email = $_POST['email'];
		
		if(!preg_match('^(([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5}){1,25})+([;.](([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5}){1,25})+)*$^', $email)) {
			echo "Please supply a valid email address.";
			
			die();
		}
				
		$sql = "SELECT * FROM newsletter WHERE email = '$email'";
		
		$query = mysql_query($sql);
		
		if(mysql_num_rows($query)) {
		
			echo "This email is already subscribed.";
			
			die();
		
		} else {
			
			$add = '';
			
			if($_POST['merch']) {
			
				$add = ", merch_volunteer = 1";
			
			}
		
			$sql = "INSERT INTO newsletter SET email = '$email'{$add}";
			
			if( mysql_query($sql) ) {
				
				echo "true";
				
				$to = $email;
				
				$from = 'Stephen Kellogg';
				
				$body = "Thanks for joining the Stephen Kellogg newsletter.";
				
				$subject = 'Stephen Kellogg Newsletter';
				
				$headers  = "From: $from\r\n";
				$headers .= "Reply-To: $from\r\n";
				$headers .= "Return-Path: $from\r\n";
				$headers .= 'MIME-Version: 1.0' . "\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				
				mail($to, $subject, $body, $headers);
				
				die();
			
			}
			
		}
		
	}
	
?>