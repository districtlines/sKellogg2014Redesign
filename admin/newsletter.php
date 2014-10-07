<?php

require('includes/config.php');

$fn = date('m_d_Y')."_mailing_list.csv";
header("Expires: 0");
header("Cache-control: private");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Description: File Transfer");
header("Content-Type: application/octet-stream");
header("Content-disposition: attachment; filename=".$fn);

$sql = "SELECT * FROM newsletter";
$query = mysql_query($sql) or die(mysql_error());

echo "\"Email\",\"Merch Volunteer\"\n";

while ($row = mysql_fetch_assoc($query)) {
	
	echo "\"". $row['email'] ."\",\"" . ($row['merch_volunteer'] ? 'X' : '') . "\"\n";
	
}

?>