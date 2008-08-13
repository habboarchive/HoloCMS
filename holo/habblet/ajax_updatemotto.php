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

$allow_guests = false;

require_once('../core.php');
require_once('../includes/session.php');
if(function_exists(SendMUSData) !== true){ include('../includes/mus.php'); }

if(isset($_POST['motto'])){

	if(strlen($_POST['motto']) > 38){
		echo $myrow['mission'];
	} else {
		$motto = addslashes(htmlspecialchars($_POST['motto']));
		mysql_query("UPDATE users SET mission = '".$motto."' WHERE id = '".$my_id."' LIMIT 1") or die(mysql_error());
		echo $_POST['motto'];
		@SendMUSData('UPRA' . $my_id);
	}

} else {
	echo $myrow['mission'];
}