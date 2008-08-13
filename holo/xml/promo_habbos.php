<?php
/*---------------------------------------------------+
| HoloCMS - Website and Content Management System
+----------------------------------------------------+
| Copyright © 2008 Meth0d
+----------------------------------------------------+
| HoloCMS is provided "as is" and comes without
| warrenty of any kind. 
+---------------------------------------------------*/

include '../config.php';
include '../core.php';

// This file will not generate a valid XML document, although we're getting close.

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<habbos>\n";

$sqll = mysql_query("SELECT id,name,mission,figure FROM users ORDER BY RAND() LIMIT 10") or die(mysql_error());
while ($row = mysql_fetch_array($sqll, MYSQL_NUM)) {

	$form_badge = GetUserBadge($row[1]);
	$form_group_badge = GetUserGroupBadge($row[0]);

	if($form_badge !== false){
		$form_badge = $cimagesurl.$badgesurl.$form_badge.".gif";
	} else {
		$form_badge = "";	
	}

	if($form_group_badge !== false){
		$form_group_badge = "groupBadge=\"".$path."habbo-imaging/badge.php?badge=".$form_group_badge."\"";
	} else {
		$form_group_badge = "";
	}

	if(IsUserOnline($row[0]) == true){
	$status = "1";
	} else {
	$status = "0";
	}

	printf("<habbo id=\"%s\" name=\"%s\" motto=\"%s\" url=\"user_profile.php?name=%s\" image=\"http://www.habbo.co.uk/habbo-imaging/avatarimage?figure=%s&size=b&direction=4&head_direction=3&gesture=sml\" badge=\"%s\" status=\"%s\" %s />\n", $row[0], $row[1], stripslashes($row[2]), $row[1], $row[3], $form_badge, $status, $form_group_badge);

}

echo "</habbos>";
?>
