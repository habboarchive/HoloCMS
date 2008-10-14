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

$pagename = "Clone Checker";

if($do == "jackstart" && isset($key)){
$query = $key;
}

if(isset($_POST['query'])){

	$get_users = mysql_query("SELECT id,name,email,ipaddress_last,hbirth FROM users WHERE ipaddress_last = '" . FilterText($_POST['query']) . "' ORDER BY name ASC") or die(mysql_error());
	$results = mysql_num_rows($get_users);

	if($results > 0){
		$msg = "Showing search results.";
	} else {
		$msg = "Nothing found.";
	}

	$query = $_POST['query'];

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
 
<?php if(isset($msg)){ ?><p><strong><?php echo $msg; ?></p></strong><?php } ?>
 
<form action='index.php?p=clonechecker&do=search' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>Clone Checker</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><strong>IP Address</strong><div class='graytext'>The IP Address you want to retrive the accounts of. You can find a user's IP address by using the <a href='index.php?p=ip'>IP Address</a> tool.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='query' value="<?php echo $query; ?>" size='30' class='textinput'></td>
</tr>

<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='Check' class='realbutton' accesskey='s'></td></tr>
</form></table></div><br />

<?php if($results > 0){ ?>
 <div class='tableborder'>
 <div class='tableheaderalt'>Search Results</div>
 <table cellpadding='4' cellspacing='0' width='100%'>
 <tr>
  <td class='tablesubheader' width='1%' align='center'>ID</td>
  <td class='tablesubheader' width='29%'>Name</td>
  <td class='tablesubheader' width='30%' align='center'>E-Mail Address</td>
  <td class='tablesubheader' width='30%' align='center'>Join date</td>
  <td class='tablesubheader' width='10%' align='center'>Edit</td>
 </tr>
<?php

while($row = mysql_fetch_assoc($get_users)){
	printf(" <tr>
  <td class='tablerow1' align='center'>%s</td>
  <td class='tablerow2'><strong>%s</strong><div class='desctext'>%s [<a href='http://who.is/whois-ip/ip-address/%s/' target='_blank'>WHOIS</a>]</div></td>
  <td class='tablerow2' align='center'><a href='mailto:%s'>%s</a></td>
  <td class='tablerow2' align='center'>%s</td>
  <td class='tablerow2' align='center'><a href='index.php?p=edituser&key=%s'><img src='./images/edit.gif' alt='Edit User Data'></a></td>
</tr>", $row['id'], $row['name'], $row['ipaddress_last'], $row['ipaddress_last'], $row['email'], $row['email'], $row['hbirth'], $row['id']);
}
?>
 
 </table>
</div>
<?php } ?>
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