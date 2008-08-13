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
$sticker = $_POST['stickerId'];

	if(is_numeric($sticker)){
		if($linked > 0){
			$sql = mysql_query("SELECT * FROM cms_homes_stickers WHERE groupid = '".$groupid."' AND type = '1' AND id = '".$sticker."' LIMIT 1") or die(mysql_error());
		} else {
			$sql = mysql_query("SELECT * FROM cms_homes_stickers WHERE userid = '".$my_id."' AND groupid = '-1' AND type = '1' AND id = '".$sticker."' LIMIT 1") or die(mysql_error());
		}
	} else {
		exit;
	}

	$num = mysql_num_rows($sql);

	if($num > 0){
		if($linked > 0){
			mysql_query("DELETE FROM cms_homes_stickers WHERE groupid = '".$groupid."' AND type = '1' AND id = '".$sticker."' LIMIT 1") or die(mysql_error());
		} else {
			mysql_query("DELETE FROM cms_homes_stickers WHERE userid = '".$my_id."' AND groupid = '-1' AND type = '1' AND id = '".$sticker."' LIMIT 1") or die(mysql_error());
		}
	$dat = mysql_fetch_assoc($sql);
	$check = mysql_query("SELECT id FROM cms_homes_inventory WHERE type = '1' AND data = '".$dat['data']."' AND userid = '".$my_id."' LIMIT 1") or die(mysql_error());
	$exists = mysql_num_rows($check);
		if($exists > 0){
		mysql_query("UPDATE cms_homes_inventory SET amount = amount + 1 WHERE userid = '".$my_id."' AND data = '".$dat['data']."' AND type = '1' LIMIT 1") or die(mysql_error());
		} else {
		mysql_query("INSERT INTO cms_homes_inventory (userid,type,subtype,data,amount) VALUES ('".$my_id."','1','1','".$dat['data']."','1')") or die(mysql_error());
		}
	echo "SUCCESS";
	} else {
	echo "ERROR";
	}

?>