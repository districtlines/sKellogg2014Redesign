<?php

include_once('includes/config.php');

header('Content-type: application/octet-stream');
header('Content-disposition:  attachment; filename="emails.csv"');


$sql = "SELECT * FROM emails ORDER BY id ASC";
$query = mysql_query($sql) or die(mysql_error());
$check = mysql_num_rows($query);

if ($check) {
    while ($row = mysql_fetch_assoc($query)) {
       	$email = $row['email'];
       	echo "\"$email\"\n";
    }
} else {
    echo "No one has signed up for Stephen Kellogg and The Sixers' Newsletter";
}