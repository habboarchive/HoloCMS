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

$groupid = $_POST['groupId'];
if(!is_numeric($groupid)){ exit; }

$check = mysql_query("SELECT member_rank FROM groups_memberships WHERE userid = '".$my_id."' AND groupid = '".$groupid."' AND member_rank > 1 AND is_pending = '0' LIMIT 1") or die(mysql_error());
$is_member = mysql_num_rows($check);

if($is_member > 0){
	$my_membership = mysql_fetch_assoc($check);
	$member_rank = $my_membership['member_rank'];
} else {
	echo "Editing group settings unsuccessful\n\n<p>\n<a href=\"group_profile.php?id=".$groupid."\" class=\"new-button\"><b>Done</b><i></i></a>\n</p>\n\n<div class=\"clear\"></div>";
	exit;
}

$check = mysql_query("SELECT * FROM groups_details WHERE id = '".$groupid."' LIMIT 1") or die(mysql_error());
$valid = mysql_num_rows($check);

if($valid > 0){
	$groupdata = mysql_fetch_assoc($check);
	$ownerid = $groupdata['ownerid'];
} else {
	echo "Editing group settings unsuccessful\n\n<p>\n<a href=\"group_profile.php?id=".$groupid."\" class=\"new-button\"><b>Done</b><i></i></a>\n</p>\n\n<div class=\"clear\"></div>";
	exit;
}

if($ownerid !== $my_id){ exit; }

$name = trim(addslashes(htmlspecialchars($_POST['name'])));
$description = trim(addslashes(htmlspecialchars($_POST['description'])));
$type = $_POST['type'];
$pane = $_POST['forumType'];
$topic = $_POST['newTopicPermission'];

if($groupdata['type'] == "3" && $_POST['type'] !== "3"){ echo "You may not change the group type if it is set to 3."; exit; } // you can't change the group type once you set it to 4, fool
if($type < 0 || $type > 3){ echo "Invalid group type."; exit; } // this naughty user doesn't even deserve an settings update

if(strlen(stripslashes($name)) > 25){
	echo "Name too long\n\n<p>\n<a href=\"group_profile.php?id=".$groupid."\" class=\"new-button\"><b>Done</b><i></i></a>\n</p>\n\n<div class=\"clear\"></div>";
} elseif(strlen(stripslashes($description)) > 255){
	echo "Description too long\n\n<p>\n<a href=\"group_profile.php?id=".$groupid."\" class=\"new-button\"><b>Done</b><i></i></a>\n</p>\n\n<div class=\"clear\"></div>";
} elseif(strlen(stripslashes($name)) < 1){
	echo "Please give a name\n\n<p>\n<a href=\"group_profile.php?id=".$groupid."\" class=\"new-button\"><b>Done</b><i></i></a>\n</p>\n\n<div class=\"clear\"></div>";	
} else {
	mysql_query("UPDATE groups_details SET name = '".$name."', description = '".$description."', type = '".$type."',pane='".$pane."',topics='".$topic."',roomid='".$_POST['roomId']."' WHERE id = '".$groupid."' AND ownerid = '".$my_id."' LIMIT 1") or die(mysql_error());
	echo "".$_POST['forum_type']." Editing group settings successful\n\n<p>\n<a href=\"group_profile.php?id=".$groupid."\" class=\"new-button\"><b>Done</b><i></i></a>\n</p>\n\n<div class=\"clear\"></div>";
}
?>