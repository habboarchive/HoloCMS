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

// simple check to avoid most direct access
$refer = $_SERVER['HTTP_REFERER'];
$pos = strrpos($refer, "group_profile.php");
if ($pos === false) { exit; }

$groupid = $_POST['groupId'];
if(!is_numeric($groupid)){ exit; }

$check = mysql_query("SELECT member_rank FROM groups_memberships WHERE userid = '".$my_id."' AND groupid = '".$groupid."' AND member_rank > 1 AND is_pending = '0' LIMIT 1") or die(mysql_error());
$is_member = mysql_num_rows($check);

if($is_member > 0){
    $my_membership = mysql_fetch_assoc($check);
    $member_rank = $my_membership['member_rank'];
} else {
    exit;
}

$check = mysql_query("SELECT * FROM groups_details WHERE id = '".$groupid."' LIMIT 1") or die(mysql_error());
$valid = mysql_num_rows($check);

if($valid > 0){
    $groupdata = mysql_fetch_assoc($check);
    $ownerid = $groupdata['ownerid'];
} else {
    exit;
}

if($ownerid !== $my_id){
    exit;
} elseif($ownerid == $my_id){

error_reporting(0);
$image = "habbo-imaging/badge-fill/$groupdata[badge].gif";
if(file_exists($image)) {
unlink($image);
}
error_reporting(1);
    mysql_query("DELETE FROM groups_details WHERE id = '".$groupid."' LIMIT 1");
    mysql_query("DELETE FROM groups_memberships WHERE groupid = '".$groupid."'");
    mysql_query("DELETE FROM cms_homes_group_linker WHERE groupid = '".$groupid."'");
    mysql_query("DELETE FROM cms_homes_stickers WHERE groupid = '".$groupid."'");
    echo "<p>\nThe group has been deleted successfully.\n</p>\n\n<p>\n<a href=\"me.php\" class=\"new-button\"><b>OK</b><i></i></a>\n</p>\n\n<div class=\"clear\"></div>";
}

?>