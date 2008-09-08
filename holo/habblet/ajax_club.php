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

if(!session_is_registered(username)){ echo "<p>\nPlease log in first.\n</p>\n\n<p>\n<a href=\"#\" class=\"new-button\" onclick=\"habboclub.closeSubscriptionWindow(); return false;\"><b>Done</b><i></i></a>\n</p>"; exit; }

echo "<p>
You are";

if(!IsHCMember($my_id)){
echo " not";
}

echo " a member of ".$shortname." Club
</p>
<p>";
if(IsHCMember($my_id)){
	echo "You have " . HCDaysLeft($my_id) . " Club Day(s) left";
} else {
	echo "&nbsp;";
}
echo "</p>";

?>



