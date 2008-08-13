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
if(function_exists(SendMUSData) !== true){ include('../includes/mus.php'); }

$pagename = "Alert";

if(isset($_POST['alert'])){
    if(!empty($_POST['alert'])){
        $counter = 0;
        $alert = stripslashes($_POST['alert']);
        $get_users = mysql_query("SELECT id FROM users");
        while($row = mysql_fetch_assoc($get_users)){
            $counter++;
            mysql_query("INSERT INTO cms_alerts (userid,type,alert) VALUES ('".$row['id']."','2','".$alert."')");
            if($_POST['musalert'] == "on"){
                @SendMUSData('HKTM' . $row['id'] . chr(2) . $alert);
            }
        }
        mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','Mass Alert to all users on site','massalert.php','".$my_id."','','".$date_full."')") or die(mysql_error());
        $msg = "Gave mass alert. Users processed: " . $counter;
    } else {
        $msg = "Please do not leave the alert field blank.";
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
 
<?php if(isset($msg)){ ?><p><strong><?php echo $msg; ?></strong></p><?php } ?>
 
<form action='index.php?p=massalert&do=placeAlert' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>Mass Site Alert</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Alert</b><div class='graytext'>The actual message that will be shown to the user.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><textarea name='alert' cols='60' rows='5' wrap='soft' id='sub_desc' class='multitext'><?php echo stripslashes($_POST['alert']); ?></textarea></td>
</tr>

<tr>
<td class='tablerow1' width='40%' valign='middle'><strong>Server Alert</strong><div class='graytext'>If you check this, this alert will also be sent to online users on the server as a MOD-alert. If you do, please do not use HTML.</div></td>
<td class='tablerow1' width='60%' valign='middle'> <input type='checkbox' name='musalert' <?php if($_POST['musalert'] == "on"){ echo "checked='checked'"; } ?>> </td>
</tr>

<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='Give Alert' class='realbutton' accesskey='s'></td></tr>
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