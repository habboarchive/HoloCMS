<?php
/*---------------------------------------------------+
| HoloCMS - Website and Content Management System
+----------------------------------------------------+
| Copyright © 2008 Meth0d
+----------------------------------------------------+
| HoloCMS is provided "as is" and comes without
| warrenty of any kind.
+---------------------------------------------------*/

$myticket = $myrow['ticket_sso'];

if(empty($myticket) || $myticket == "0" || strlen($myticket) < 39){
	$myticket = GenerateTicket();
	mysql_query("UPDATE users SET ticket_sso = '".$myticket."', ipaddress_last = '".$remote_ip."' WHERE id = '".$my_id."' LIMIT 1") or die(mysql_error());
}
		
?>