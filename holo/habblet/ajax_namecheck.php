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

$name = addslashes($_POST['name']);
$filter = preg_replace("/[^a-z\d\-=\?!@:\.]/i", "", $name);

$tmp = mysql_query("SELECT id FROM users WHERE name = '".$name."' LIMIT 1") or die(mysql_error());
$tmp = mysql_num_rows($tmp);

if($tmp > 0){
	header("X-JSON: {\"registration_name\":\"Sorry, but this username is taken. Please choose another one.\"}");
} elseif($filter !== $name){
	header("X-JSON: {\"registration_name\":\"Sorry, but this username contains invalid characters.\"}");
} elseif(strlen($name) > 24){
	header("X-JSON: {\"registration_name\":\"Sorry, but this username is too long.\"}");
} elseif(strlen($name) < 1){
	header("X-JSON: {\"registration_name\":\"Please enter a username.\"}");
} else {
	$pos = strrpos($refer, "MOD-");
	if ($pos === true) {
		header("X-JSON: {\"registration_name\":\"This name is not allowed.\"}");
	} else {
		header("X-JSON: {}");
	}
}

?>

