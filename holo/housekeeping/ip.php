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

$pagename = "Retrive IP Address";

if(isset($_POST['query'])){

	$check = mysql_query("SELECT name,ipaddress_last,id FROM users WHERE name LIKE '" . FilterText($_POST['query']) . "' LIMIT 1") or die(mysql_error());
	$result = mysql_num_rows($check);

	if($result > 0){
		$row = mysql_fetch_assoc($check);
		$msg = "<b>The following user was found:</b><br />&nbsp;&nbsp;&nbsp;&nbsp;";
		$msg = $msg . $row['name'] . " (ID: " . $row['id'] . ") - IP Address: " . $row['ipaddress_last'];
		$msg = $msg . "<br /><br />[<a href='http://who.is/whois-ip/ip-address/".$row['ipaddress_last']."/' target='_blank'>Whois</a> | <a href='index.php?p=clonechecker&do=jackstart&key=".$row['ipaddress_last']."'>Check for clones</a>]";
	} else {
		$msg = "Sorry, but this user was not found or does not exist. Please check your spelling and try again.";
	}

}

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
 
<?php if(isset($msg)){ ?><p><?php echo $msg; ?></p><?php } ?>
 
<form action='index.php?p=ip&do=search' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>Search User - Retrive IP Address</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><strong>Username</strong></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='query' value="" size='30' class='textinput'></td>
</tr>

<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='Get IP Address' class='realbutton' accesskey='s'></td></tr>
</form></table></div><br />	 </div><!-- / RIGHT CONTENT BLOCK -->
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