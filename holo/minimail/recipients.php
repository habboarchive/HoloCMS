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

include('../core.php');
include('../includes/session.php');

$i = 0;
$output = "[";
$getem = mysql_query("SELECT * FROM messenger_friendships WHERE userid = '".$my_id."' OR friendid = '".$my_id."'") or die(mysql_error());

while ($row = mysql_fetch_assoc($getem)) {
	$i++;

	if($row['friendid'] == $my_id){
		$friendsql = mysql_query("SELECT * FROM users WHERE id = '".$row['userid']."'");
	} else {
		$friendsql = mysql_query("SELECT * FROM users WHERE id = '".$row['friendid']."'");
	}

	$friendrow = mysql_fetch_assoc($friendsql);

	$name = $friendrow['name'];
	$id = $friendrow['id'];

	$output = $output."{\"id\":".$id.",\"name\":\"".$name."\"},";
}
$output = substr_replace($output,"",-1);
$output = $output."]";
?>
/*-secure-
<?php echo $output; ?>
 */