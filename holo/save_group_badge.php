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

$groupid = $_POST['groupId'];
$badge = $_POST['code'];
$appkey = $_POST['__app_key'];

if(!is_numeric($groupid)){ exit; }
if($appkey !== "Meth0d.org"){ exit; }

$badge = str_replace("NaN", "", $badge); // NaN = invalid stuff

$check = mysql_query("SELECT member_rank FROM groups_memberships WHERE userid = '".$my_id."' AND groupid = '".$groupid."' AND member_rank > 1 AND is_pending = '0' LIMIT 1") or die(mysql_error());
$is_member = mysql_num_rows($check);

if($is_member > 0){
    $my_membership = mysql_fetch_assoc($check);
    $member_rank = $my_membership['member_rank'];
    if($member_rank < 2){ exit; }
} else {
    exit;
}

$check = mysql_query("SELECT * FROM groups_details WHERE id = '".$groupid."' LIMIT 1") or die(mysql_error());
$valid = mysql_num_rows($check);
$row = mysql_fetch_assoc($check);
if($valid > 0){ $groupdata = mysql_fetch_assoc($check); } else { exit; }
if($badge != $row[badge]) {
if($row[badge] != b0503Xs09114s05013s05015) {
$image = "habbo-imaging/badge-fill/$row[badge].gif";
if(file_exists($image)) {
unlink($image);
}
} else {
mysql_query("UPDATE groups_details SET badge = '".addslashes($badge)."' WHERE id = '".$groupid."' LIMIT 1");
header("Location: group_profile.php?id=".$groupid."&x=BadgeUpdated"); exit;
}
}
mysql_query("UPDATE groups_details SET badge = '".addslashes($badge)."' WHERE id = '".$groupid."' LIMIT 1");
header("Location: group_profile.php?id=".$groupid."&x=BadgeUpdated"); exit;

?> 