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

@include('../config.php');
@include('./config.php');

require_once('../core.php');

if($hkzone !== true){ header("Location: index.php?throwBack=true"); exit; }
if(!session_is_registered(acp)){ header("Location: index.php?p=login"); exit; }

if($_GET['do'] == "prune"){
	if($my_id == $sysadmin){
		mysql_query("TRUNCATE TABLE system_stafflog") or die(mysql_error());
		mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','Staff Log pruned (emptied)','logs.php','".$my_id."','','".$date_full."')") or die(mysql_error());
	} else {
		mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','Access denied to System Administrator [".$sysadmin."] only feature (Prune Staff Logs)','logs.php','".$my_id."','','".$date_full."')") or die(mysql_error());
	}
}

$pagename = "Logs";

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
 
 <div class='tableborder'>
 <div class='tableheaderalt'><?php echo $sitename; ?> Staff Logs</div>
 <table cellpadding='4' cellspacing='0' width='100%'>
 <tr>
  <td class='tablesubheader' width='15%' align='center'>Action</td>
  <td class='tablesubheader' width='15%' align='center'>User</td>
  <td class='tablesubheader' width='15%' align='center'>Target user</td>
  <td class='tablesubheader' width='25%'>Message/Details</td>
  <td class='tablesubheader' width='15%'>Note</td>
  <td class='tablesubheader' width='20%' align='center'>Date</td>
 </tr>
<?php
$get_users = mysql_query("SELECT * FROM system_stafflog ORDER BY id DESC") or die(mysql_error());

while($row = mysql_fetch_assoc($get_users)){

	$userdata = mysql_query("SELECT name FROM users WHERE id = '".$row['userid']."' LIMIT 1");
	$userdata = mysql_fetch_assoc($userdata);

	if(!empty($row['targetid'])){
		$targetdata = mysql_query("SELECT name FROM users WHERE id = '".$row['targetid']."' LIMIT 1");
		$targetdata = mysql_fetch_assoc($targetdata);
	} else {
		$targetdata['name'] = "N/A";
	}
	
	if(!empty($row['note'])){ $note = $row['note']; } else { $note = "<i>None given</i>"; }

	echo "<tr>
	<td class='tablerow1' align='center'>".$row['action']."</td>
	<td class='tablerow2' align='center'>".$userdata['name']." (ID: ".$row['userid'].")</td>
	<td class='tablerow2' align='center'>".$targetdata['name']; if(!empty($row['targetid'])){ echo " (ID: ".$row['targetid'].")"; } echo "</td>
	<td class='tablerow2'>".$row['message']."</td>
	<td class='tablerow2'>".$note."</td>
	<td class='tablerow2' align='center'>".$row['timestamp']."</td>
</tr>";

}
?>
 
 </table>
</div>
<br />
<div align='center'>(<a href='index.php?p=logs&do=prune'><b>Prune Logs</b></a>)</div>
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