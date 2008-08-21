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
include('./includes/session.php');

$check = mysql_query("SELECT groupid,active FROM cms_homes_group_linker WHERE userid = '".$my_id."' AND active = '1' LIMIT 1") or die(mysql_error());
$linked = mysql_num_rows($check);

if($linked > 0){
	$linkdata = mysql_fetch_assoc($check);
	$groupid = $linkdata['groupid'];
}

// Collect variables
$widget = $_POST['widgetId'];

	if(is_numeric($widget)){
		if($linked > 0){
			$sql = mysql_query("SELECT * FROM cms_homes_stickers WHERE groupid = '".$groupid."' AND type = '2' AND id = '".$widget."' LIMIT 1") or die(mysql_error());
		} else {
			$sql = mysql_query("SELECT * FROM cms_homes_stickers WHERE userid = '".$my_id."' AND groupid = '-1' AND type = '2' AND id = '".$widget."' LIMIT 1") or die(mysql_error());
		}
	} else {
		exit;
	}

	$num = mysql_num_rows($sql);

	if($num > 0){
		$row = mysql_fetch_assoc($sql);
		if($linked > 0){
			mysql_query("DELETE FROM cms_guestbook WHERE widget_id = '".$widget."'");
			mysql_query("DELETE FROM cms_homes_stickers WHERE groupid = '".$groupid."' AND type = '2' AND id = '".$widget."' LIMIT 1") or die(mysql_error());
		} else {
			mysql_query("DELETE FROM cms_guestbook WHERE widget_id = '".$widget."'");
			mysql_query("DELETE FROM cms_homes_stickers WHERE userid = '".$my_id."' AND groupid = '-1' AND type = '2' AND id = '".$widget."' LIMIT 1") or die(mysql_error());
		}
	echo "SUCCESS";
	} else {
	echo "ERROR";
	}

?>