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

$pagename = "Customer Support";

if($_GET['do'] == "pick" && isset($_GET['key']) && is_numeric($_GET['key'])){

	$viewmode = false;

	$check = mysql_query("SELECT id FROM cms_help WHERE id = '".$_GET['key']."' AND picked_up = '0' LIMIT 1") or die(mysql_error());
	$found = mysql_num_rows($check);

	if($found > 0){
		mysql_query("UPDATE cms_help SET picked_up = '1' WHERE id = '".$_GET['key']."' AND picked_up = '0' LIMIT 1") or die(mysql_error());
		$msg = "Help query picked up successfully.";
		mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','Picked up help query (ID: ".$_GET['key'].")','helper.php','".$my_id."','','".$date_full."')") or die(mysql_error());
	} else {
		$msg = "Invalid ID specified or already picked up.";
	}

} elseif($_GET['x'] == "refreshStatic"){

	$viewmode = false;
	$msg = "Overview refreshed.";

} elseif($_GET['do'] == "delete" && isset($_GET['key']) && is_numeric($_GET['key'])){

	$viewmode = false;

	$check = mysql_query("SELECT id FROM cms_help WHERE id = '".$_GET['key']."' AND picked_up = '1' LIMIT 1") or die(mysql_error());
	$found = mysql_num_rows($check);

	if($found > 0){
		mysql_query("DELETE FROM cms_help WHERE id = '".$_GET['key']."' AND picked_up = '1' LIMIT 1") or die(mysql_error());
		$msg = "Help query deleted successfully.";
		mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','Deleted help query (ID: ".$_GET['key'].")','helper.php','".$my_id."','','".$date_full."')") or die(mysql_error());
	} else {
		$msg = "Invalid ID specified or not picked up yet.<br />Please note that all queries <i>must</i> be picked up before they can be deleted.";
	}

} elseif($_GET['do'] == "view" && isset($_GET['key']) && is_numeric($_GET['key'])){

	$check = mysql_query("SELECT * FROM cms_help WHERE id = '".$_GET['key']."' LIMIT 1") or die(mysql_error());
	$found = mysql_num_rows($check);

	if($found > 0){
		$viewmode = true;
		$viewdata = mysql_fetch_assoc($check);
	} else {
		$viewmode = false;
		$msg = "Invalid ID specified.";
	}

} else {

	$viewmode = false;

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
 
<?php if($viewmode == false){ ?>
<?php if(isset($msg)){ ?><p><strong><?php echo $msg; ?></strong></p><?php } ?>
 
<form action='index.php?p=news_manage&do=save' method='post' name='theAdminForm' id='theAdminForm'>
 <div class='tableborder'>
 <div class='tableheaderalt'>Customer Support - Help Queries</div>
 <table cellpadding='4' cellspacing='0' width='100%'>
 <tr>
  <td class='tablesubheader' width='1%' align='center'>ID</td>
  <td class='tablesubheader' width='18%'>Subject</td>
  <td class='tablesubheader' width='25%' align='center'>Date</td>
  <td class='tablesubheader' width='20%' align='center'>Username</td>
  <td class='tablesubheader' width='15%' align='center'>Picked up?</td>
  <td class='tablesubheader' width='20%' align='center'>Room ID</td>
  <td class='tablesubheader' width='1%' align='center'>Reply</td>
  <td class='tablesubheader' width='1%' align='center'>Delete</td>
 </tr>
<?php
$get_articles = mysql_query("SELECT id,username,ip,message,date,picked_up,subject,roomid FROM cms_help ORDER BY id DESC") or die(mysql_error());

while($row = mysql_fetch_assoc($get_articles)){

if(!is_numeric($row['roomid']) || $row['roomid'] < 1){ $roomid = "N/A"; } else { $roomid = $row['roomid']; }
if($row['picked_up'] == 1){ $picked = "Yes"; } else { $picked = "No (<a href='index.php?p=helper&do=pick&key=".$row['id']."'>Pick up</a>)"; }

	printf(" <tr>
  <td class='tablerow1' align='center'>%s</td>
  <td class='tablerow2'><a href='index.php?p=helper&do=view&key=%s'><strong>%s</strong></a></td>
  <td class='tablerow2' align='center'>%s</td>
  <td class='tablerow2' align='center'><a href='user_profile.php?tag=%s' target='_blank'>%s</a></td>
  <td class='tablerow2' align='center'>%s</td>
  <td class='tablerow2' align='center'>%s</td>
  <td class='tablerow2' align='center'><a href='index.php?p=alert&do=quickreply&key=%s'><img src='./images/edit.gif' alt='Quick Reply'></a></td>
  <td class='tablerow2' align='center'><a href='index.php?p=helper&do=delete&key=%s'><img src='./images/delete.gif' alt='Delete'></a></td>															
</tr>", $row['id'], $row['id'], HoloText($row['subject'])), $row['date'], $row['username'], $row['username'], $picked, $roomid, $row['id'], $row['id'];
}
?>
 
 </table>
 <div class='tablefooter' align='center'><div class='fauxbutton-wrapper'><span class='fauxbutton'><a href='index.php?p=helper&x=refreshStatic'>Refresh overview</a></span></div></div>
</div>
<?php } else { ?>
<form action='index.php?p=helper' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>Help Query (<?php echo $viewdata['subject']; ?>)</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Username</b><div class='graytext'>The name of the user that submitted this help query.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='attrb' readonly='readonly' value="<?php echo HoloText($viewdata['username']); ?>" size='25' maxlength='25' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>IP Address</b><div class='graytext'>The IP Address of the user that submitted this help query.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='attrb' readonly='readonly' value="<?php echo HoloText($viewdata['ip']); ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Date</b><div class='graytext'>The date and time this query was submitted.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='attrb' readonly='readonly' value="<?php echo HoloText($viewdata['date']); ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Room ID</b><div class='graytext'>If the query was submitted in the hotel, the Room ID will be shown in this field.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='attrb' readonly='readonly' value="<?php if($viewdata['roomid'] > 0){ echo $viewdata['roomid']; } else { echo "N/A"; } ?>" size='30' class='textinput'></td>
</tr>

<tr><td align='left' class='tablesubheader' colspan='2' >Data</td></tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Message</b><div class='graytext'>The actual query submitted by the user via the Help Tool or ingame CFH tool.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><textarea name='data' cols='60' rows='8' readonly='readonly' wrap='soft' id='sub_desc'   class='multitext'><?php echo HoloText($viewdata['message']); ?></textarea></td>
</tr>

<tr><td align='left' class='tablesubheader' colspan='2' >Options</td></tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Pick Up</b><div class='graytext'>If not picked up already, you can do so here. Picking up a query indicates that you will 'take care of it'.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><?php if($viewdata['picked_up'] == 1){ echo "Already picked up"; } else { echo "<a href='index.php?p=helper&do=pick&key=".$viewdata['id']."'>Pick up</a>"; } ?></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Delete</b><div class='graytext'>If the query is picked up and taken care of, you can delete it here.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><?php echo "<a href='index.php?p=helper&do=delete&key=".$viewdata['id']."'>Delete</a>"; ?></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Quick Reply</b><div class='graytext'>Quickly reply to this Help Inquery.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><?php echo "<a href='index.php?p=alert&do=quickreply&key=".$viewdata['id']."'>Reply</a>"; ?></td>
</tr>

<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='Return to overview' class='realbutton' accesskey='s'></td></tr>
</form></table></div><br />
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