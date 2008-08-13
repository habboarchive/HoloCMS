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

if(is_numeric($groupid) && $groupid > 0){

	$check = mysql_query("SELECT type FROM groups_details WHERE id = '".$groupid."' LIMIT 1") or die(mysql_error());
	$exists = mysql_num_rows($check);

	if($exists > 0){

		$check2 = mysql_query("SELECT groupid FROM groups_memberships WHERE userid = '".$my_id."' AND groupid = '".$groupid."' LIMIT 1") or die(mysql_errors());
		$already_member = mysql_num_rows($check2);

		if($already_member > 0){

			mysql_query("DELETE FROM groups_memberships WHERE userid = '".$my_id."' AND groupid = '".$groupid."' LIMIT 1") or die(mysql_error());
			echo "<script type=\"text/javascript\">\nlocation.href = habboReqPath + \"group_profile.php?id=".$groupid."\";\n</script>";
			echo "<p>You have successfully left this group.</p>";
			echo "<p>Please wait, you are being redirected..</p>";

		} else {

			exit;

		}

	} else {

		exit;

	}

}

?>