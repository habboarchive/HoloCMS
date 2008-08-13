<?php
/*===================================================+
|| # HoloCMS - Website and Content Management System
|+===================================================+
|| # Copyright © 2008 Meth0d. All rights reserved.
|| # http://www.meth0d.org
|+===================================================+
|| # HoloCMS is provided "as is" and comes without
|| # warrenty of any kind. HoloCMS is free software!
|+===================================================*/

require_once('core.php');

echo "<h1>User Password Hasher</h1><hr />";

$stats['total'] = 0;
$stats['skipped'] = 0;
$stats['hashed'] = 0;

$get_em = mysql_query("SELECT id,name,password FROM users ORDER BY name ASC") or die(mysql_error());
while($row = mysql_fetch_assoc($get_em)){
	$stats['total']++;
	
	if(strlen($row['password']) == 32){
		echo "<font size='2'>Skipped [ALREADY HASHED] " . $row['name'] . " (ID: " . $row['id'] . ")</font><br />";
		$stats['skipped']++;
	} else {
		$new_password = HoloHash($row['password']);
		mysql_query("UPDATE users SET password = '".$new_password."' WHERE id = '".$row['id']."' LIMIT 1") or die(mysql_error());
		echo "<b>Hashed " . $row['name'] . "'s password (ID: " . $row['id'] . ") (" . $row['password'] . " to " . $new_password . ")<br /></b>";
		$stats['hashed']++;
	}
}

echo "<br />Job completed successfully!<br />
<br />
Total users processed: ".$stats['total']."<br />
Skipped: ".$stats['skipped'].",  Hashed: ".$stats['hashed'];

?>