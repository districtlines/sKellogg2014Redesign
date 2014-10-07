<?php

	 set_time_limit(0);
		include('includes/config.php');
	 
	 // This returns what the cURL comes back with ...
	 function curl($url) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_URL, $url);
			
			$return = curl_exec($ch);
			
			if(curl_errno($ch) > 0) {
				 $return['error'] = curl_error($ch);
				 $return['errno'] = curl_errno($ch);
			}
			
			curl_close($ch);
			return $return;
	 }

	 $last_run_q = "SELECT twitter_id FROM twitter ORDER BY id DESC LIMIT 1;";
	 $last_run_r = mysql_query($last_run_q);
	 if(mysql_num_rows($last_run_r)){
	 	
	 	$last_run = mysql_result($last_run_r, 0, "twitter_id");
	 	
	 }
	 $cols = array('id', "text", "created_at", "source", "in_reply_to_status_id", "in_reply_to_screen_name", "in_reply_to_user_id");
	 
	 if($last_run) {
		 
		$curl_url = "http://api.twitter.com/1/statuses/user_timeline.json?screen_name=sk6ers&since_id=$last_run&count=30";
		
	 } else {
	 	
	 	$curl_url = "http://api.twitter.com/1/statuses/user_timeline.json?screen_name=sk6ers&count=5";
	 
	 }
	 
	 $json = curl($curl_url);
	 //$json = curl("http://api.twitter.com/1/statuses/user_timeline.json?screen_name=breanneduren&count=5");
	 //print_r($json);
	 $statues = array_reverse(json_decode($json, true));
	 
	 //print_r($statues[0]);
	 //die();
	 if($statues) {
		 foreach($statues as $status) {
				foreach($cols as $columns) {
					 switch($columns) {
							case "id":
								 $vals["twitter_id"] = $status[$columns];
								 break;
							case "created_at":
								 $vals[$columns] = 'FROM_UNIXTIME('.strtotime($status[$columns]).')';
								 break;
							default:
								 $vals[$columns] = "'".addslashes($status[$columns])."'";
								 break;
					 }
				}
				
				$sql = "SELECT id FROM twitter WHERE twitter_id = '{$vals['twitter_id']}'";
				$query = mysql_query($sql) or die(mysql_error());
				$check = mysql_num_rows($query);
				if (!$check) {
					
					$query = "INSERT INTO twitter (".implode(",", array_keys($vals)).", created, modified) VALUES (".implode(",", array_values($vals)).", NOW(), NOW())";
					mysql_query($query) or die(mysql_error() . " --- " . $query);
					
					$twitterText = $vals['text'];
					$type = 'twitter';
					
					$query = "INSERT INTO recent_activity (content, type, created, modified) VALUES (\"$twitterText\", \"$type\", NOW(), NOW())";
					mysql_query($query) or die(mysql_error() . " --- " . $query);
					
				}
		 }
	 }

	 
?>