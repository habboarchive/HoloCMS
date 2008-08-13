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

include('core.php');

// Split and count the data we've just recieved
$note = explode("/", $_POST['stickienotes']);
$widget = explode("/", $_POST['widgets']);
$sticker = explode("/", $_POST['stickers']);
$background = explode(":", $_POST['background']);

$check = mysql_query("SELECT groupid,active FROM cms_homes_group_linker WHERE userid = '".$my_id."' AND active = '1' LIMIT 1") or die(mysql_error());
$linked = mysql_num_rows($check);

if($linked > 0){
	$linkdata = mysql_fetch_assoc($check);
	$groupid = $linkdata['groupid'];
}

if(!empty($background[1])){
	$bg = str_replace("b_","",$background[1]);
	echo $bg;
	$check = mysql_query("SELECT id FROM cms_homes_inventory WHERE userid = '".$my_id."' AND type = '4' AND data = '" . addslashes($bg) . "' LIMIT 1") or die(mysql_error());
	$valid = mysql_num_rows($check);
	if($valid > 0){
		if(!isset($groupid)){
			$check = mysql_query("SELECT data FROM cms_homes_stickers WHERE userid = '".$my_id."' AND groupid = '-1' AND type = '4' LIMIT 1") or die(mysql_error());
		} else {
			$check = mysql_query("SELECT data FROM cms_homes_stickers WHERE groupid = '".$groupid."' AND type = '4' LIMIT 1") or die(mysql_error());
		}
		$exists = mysql_num_rows($check);
		if($exists > 0){
			if(!isset($groupid)){
				mysql_query("UPDATE cms_homes_stickers SET data = '" . $bg . "' WHERE type = '4' AND userid = '".$my_id."' AND groupid = '-1' LIMIT 1") or die(mysql_error());
			} else {
				mysql_query("UPDATE cms_homes_stickers SET data = '" . $bg . "' WHERE type = '4' AND groupid = '".$groupid."' LIMIT 1") or die(mysql_error());
			}
		} else {
			if(!isset($groupid)){
				mysql_query("INSERT INTO cms_homes_stickers (userid,groupid,x,y,z,data,type,subtype,skin) VALUES ('" . $my_id . "','-1','-1','-1','-1','" . $bg . "','4','0','-1')") or die(mysql_error());
			} else {
				mysql_query("INSERT INTO cms_homes_stickers (userid,groupid,x,y,z,data,type,subtype,skin) VALUES ('" . $my_id . "','".$groupid."','-1','-1','-1','" . $bg . "','4','0','-1')") or die(mysql_error());
			}
		}
	}
}

// Loop through each array of data we encountered and save the stuff that was passed onto us
foreach($widget as $raw){
	$bits = explode(":", $raw);
	$id = $bits[0];
	$data = addslashes($bits[1]);
	if(!empty($data) && !empty($id) && is_numeric($id)){
		$coordinates = explode(",", $data);
		$x = $coordinates[0];
		$y = $coordinates[1];
		$z = $coordinates[2];
		if(is_numeric($x) && is_numeric($y) && is_numeric($z)){
			if(isset($groupid)){
				mysql_query("UPDATE cms_homes_stickers SET x = '".$x."', y = '".$y."', z = '".$z."' WHERE id = '".$id."' AND type = '2' AND groupid = '".$groupid."' LIMIT 1"); // or die(mysql_error());
			} else {
				mysql_query("UPDATE cms_homes_stickers SET x = '".$x."', y = '".$y."', z = '".$z."' WHERE id = '".$id."' AND type = '2' AND userid = '".$my_id."' AND groupid = '-1' LIMIT 1"); // or die(mysql_error());
			}
		}
	}
}

foreach($sticker as $raw){
	$bits = explode(":", $raw);
	$id = $bits[0];
	$data = addslashes($bits[1]);
	if(!empty($data) && !empty($id) && is_numeric($id)){
		$coordinates = explode(",", $data);
		$x = $coordinates[0];
		$y = $coordinates[1];
		$z = $coordinates[2];
		if(is_numeric($x) && is_numeric($y) && is_numeric($z)){
			if(isset($groupid)){
				mysql_query("UPDATE cms_homes_stickers SET x = '".$x."', y = '".$y."', z = '".$z."' WHERE id = '".$id."' AND type = '1' AND groupid = '".$groupid."' LIMIT 1"); // or die(mysql_error());
			} else {
				mysql_query("UPDATE cms_homes_stickers SET x = '".$x."', y = '".$y."', z = '".$z."' WHERE id = '".$id."' AND type = '1' AND userid = '".$my_id."' AND groupid = '-1' LIMIT 1"); // or die(mysql_error());
			}
		}
	}
}

foreach($note as $raw){
	$bits = explode(":", $raw);
	$id = $bits[0];
	$data = addslashes($bits[1]);
	if(!empty($data) && !empty($id) && is_numeric($id)){
		$coordinates = explode(",", $data);
		$x = $coordinates[0];
		$y = $coordinates[1];
		$z = $coordinates[2];
		if(is_numeric($x) && is_numeric($y) && is_numeric($z)){
			if(isset($groupid)){
				mysql_query("UPDATE cms_homes_stickers SET x = '".$x."', y = '".$y."', z = '".$z."' WHERE id = '".$id."' AND type = '3' AND groupid = '".$groupid."' LIMIT 1"); // or die(mysql_error());
			} else {
				mysql_query("UPDATE cms_homes_stickers SET x = '".$x."', y = '".$y."', z = '".$z."' WHERE id = '".$id."' AND type = '3' AND userid = '".$my_id."' AND groupid = '-1' LIMIT 1"); // or die(mysql_error());
			}
		}
	}
}

if(isset($groupid)){
	echo "\n<script language=\"JavaScript\" type=\"text/javascript\">\nwaitAndGo('group_profile.php?id=" . $groupid . "');\n</script>";
	exit;
} else {
	echo "\n<script language=\"JavaScript\" type=\"text/javascript\">\nwaitAndGo('user_profile.php?name=" . stripslashes($name) . "');\n</script>";
	exit;
}
