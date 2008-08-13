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

$refer = $_SERVER['HTTP_REFERER']; $pos = strrpos($refer, "group_profile.php"); if ($pos === false) { exit; }
$groupid = $_POST['groupId'];

$targets = $_POST['targetIds'];

$targets = explode(",", $targets);
if(!is_numeric($groupid)){ exit; }

$check = mysql_query("SELECT * FROM groups_details WHERE id = '".$groupid."' LIMIT 1") or die(mysql_error());
$valid = mysql_num_rows($check);
if($valid < 1){ exit; }

// Loop through all the members
foreach($targets as $member){
        if(is_numeric($member)){
                $valid = mysql_evaluate("SELECT COUNT(*) FROM users WHERE id = '".$member."' LIMIT 1");
                if($valid > 0){
                        mysql_query("UPDATE groups_memberships SET is_pending = '0' WHERE userid = '".$member."' AND groupid = '".$groupid."' LIMIT 1") or die(mysql_error());
                }
        }
}

echo "OK"; exit;

?>