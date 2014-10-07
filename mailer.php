<?php

include('includes/config.php');

$site = ROOT;

$from = 'Stephen Kellogg and The Sixers<noreply@sk6ers.com>';

if($_POST['submitted']) {

	//error_reporting(E_ALL);
	//error_reporting(E_STRICT);
		
	date_default_timezone_set('America/New York');
	
	$email = $_POST['email'];
	
	if(preg_match('^(([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5}){1,25})+([;.](([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5}){1,25})+)*$^', $email)) {
		$sql = "SELECT * FROM newsletter WHERE email = '$email'";
		$query = mysql_query($sql);
		$check = mysql_num_rows($query);
		if($check) {
			$exists = true;
		}else{
			$exists = false;
		}
	} else {
	
		echo "Incorrect Email";
		die();
	
	}
		
	$code = md5($email.mktime().rand(0,9999));			
	$code = substr($code, 0, 10);	
	
	$to = $email;
	
	$subject = "Stephen Kellogg and The Sixers MP3 Download";
	
	$body = "Thank you for signing up for Stephen Kellogg and The Sixers' newsletter. To download your free mp3 use the following code <br />
	
	<p>Your Code : $code </p>
	
	<p>Go to <a href=\"$site/redeem.php?code=$code\" title=\"Redeem Your MP3\">$site/redeem.php</a> and enter your code!</p>
	
	<p><strong>Stephen Kellogg and The Sixers</strong></p>";
	
	$headers  = "From: $from\r\n";
	$headers .= "Reply-To: $from\r\n";
	$headers .= "Return-Path: $from\r\n";
	$headers .= 'MIME-Version: 1.0' . "\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	if(mail($to, $subject, $body, $headers)) {
		
		if(!$exists) {
			$sql2 = "INSERT INTO newsletter SET email = '$email'";
			mysql_query($sql2);
		}
		
		$sql = "INSERT INTO downloads SET code='$code', email = '$email'";
		
		if(mysql_query($sql)) {
			
			echo 'true';
			
		} else {
			
			echo 'Issue sending email.';
			
		}
		
	} else {
		
		echo "Problem Sending Email. Please try again.";
		
	}

}
?>