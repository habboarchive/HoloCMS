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

require_once('../core.php');
if($hkzone !== true){ header("Location: index.php?throwBack=true"); exit; }
if(!session_is_registered(acp)){ header("Location: index.php?p=login"); exit; }

$pagename = "Ban Listing";

@include('subheader.php');
@include('header.php');
?>
<table cellpadding='0' cellspacing='8' width='100%' id='tablewrap'>
<tr> <td width='22%' valign='top' id='leftblock'>
 <div>
 <!-- LEFT CONTEXT SENSITIVE MENU -->
<?php @include('usermenu.php'); ?>
 <!-- / LEFT CONTEXT SENSITIVE MENU -->
 </div>
 </td>
 <td width='78%' valign='top' id='rightblock'>
 <div><!-- RIGHT CONTENT BLOCK -->
 
 <p>Notice: Expired bans will be automaticly removed upon login of the previously banned user.</p>
 
 <div class='tableborder'>
 <div class='tableheaderalt'>Active Ban Listing</div>
 <table cellpadding='4' cellspacing='0' width='100%'>
 <tr>
  <td class='tablesubheader' width='25%' align='center'>User</td>
  <td class='tablesubheader' width='30%'>Reason</td>
  <td class='tablesubheader' width='20%' align='center'>Expires on</td>
  <td class='tablesubheader' width='25%' align='center'>IP Address</td>
 </tr>
<?php
$get_users = mysql_query("SELECT * FROM users_bans") or die(mysql_error());

while($row = mysql_fetch_assoc($get_users)){
	
	if(empty($row['ipaddress'])){ $row['ipaddress'] = "<i>Not an IP Ban</i>"; }
	
	$check = mysql_query("SELECT name FROM users WHERE id = '".$row['userid']."' LIMIT 1") or die(mysql_error());
	$valid = mysql_num_rows($check);
	if($valid > 0){
		$tmp = mysql_fetch_assoc($check);
		$username = $tmp['name'];
	} else {
		$username = "<i>Invalid user!</i>";
	}
	printf(" <tr>
  <td class='tablerow1' align='center'>%s (ID: %s)</td>
  <td class='tablerow1' align='center'>%s</td>
  <td class='tablerow1' align='center'>%s</td>
  <td class='tablerow1' align='center'>%s</td>
</tr>", $username, $row['userid'], $row['descr'], $row['date_expire'], $row['ipaddress']);
}
?>
 
 </table>
</div>
	 </div><!-- / RIGHT CONTENT BLOCK -->
	 </td></tr>
</table>
</div><!-- / OUTERDIV -->
<div align='center'><br />
<?php
$mtime = explode(' ', microtime());
$totaltime = $mtime[0] + $mtime[1] - $starttime;
printf('Time: %.3f', $totaltime);
?>
</div>