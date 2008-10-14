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

$pagename = "Unban";

if(isset($_POST['query'])){
    $query = FilterText($_POST['query']);
    $rows = mysql_evaluate("SELECT COUNT(*) FROM users_bans WHERE userid = '".$query."' OR descr = '".$query."' OR date_expire = '".$query."' OR ipaddress = '".$query."'");
    mysql_query("DELETE FROM users_bans WHERE userid = '".$query."' OR descr = '".$query."' OR date_expire = '".$query."' OR ipaddress = '".$query."'") or die(mysql_error());
    mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','(Mass) Unban performed [Query: ".FilterText($query)."]','unbantool.php','".$my_id."','','".$date_full."')") or die(mysql_error());
    $msg = "Operation applied successfully. Affected bans: " . $rows;
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
 
<?php if(isset($msg)){ ?><p><strong><?php echo $msg; ?></strong></p><?php } ?>
 
<form action='index.php?p=unban&do=run' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>(Mass) Unban</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
    
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>IP Address</b> OR <b>User ID</b> OR <b>Expire Date</b> OR <b>Reason</b><div class='graytext'>Variable wich the ban must contain to unban it.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='query' value="<?php echo $_POST['query']; ?>" size='30' class='textinput'></td>
</tr>

<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='Unban' class='realbutton' accesskey='s'></td></tr>
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