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
    $alert = FilterText($_POST['alert']);
    $username = $_POST['username'];
    $check = mysql_query("SELECT id FROM users WHERE name = '".$username."' LIMIT 1") or die(mysql_error());
    $valid = mysql_num_rows($check);
    if($valid > 0){
        $tmp = mysql_fetch_assoc($check);
        $userid = $tmp['id'];
        if(is_numeric($userid)){
            mysql_query("INSERT INTO cms_alerts (userid,type,alert) VALUES ('".$userid."','2','".$alert."')") or die(mysql_error());
            mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','Sent alert to user','alert.php','".$my_id."','".$userid."','".$date_full."')") or die(mysql_error());
            $msg = "Gave alert to user.";
            if($_POST['musalert'] == "on"){
                @SendMUSData('HKMW' . $userid . chr(2) . $alert);
            }
        } else {
            $msg = "Error processing user";
        }
    } else {
        $msg = "This user does not exist.";
    }
} elseif($_GET['do'] == "quickreply" && is_numeric($_GET['key'])){
    $check = mysql_query("SELECT * FROM cms_help WHERE id = '".$_GET['key']."' LIMIT 1") or die(mysql_error());
    $tmp = mysql_fetch_assoc($check);
    if($tmp['picked_up'] !== "1"){
        mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','Automaticly picked up help query (ID: ".$_GET['key'].")','alert.php','".$my_id."','','".$date_full."')") or die(mysql_error());
        mysql_query("UPDATE cms_help SET picked_up = '1' WHERE id = '".$_GET['key']."' LIMIT 1") or die(mysql_error());
    }
    $_POST['alert'] = "Hello, my name is ".$name.". I am sending you this message in reply to your help inquiry sent on ".$tmp['date'].", with the subject '".HoloText($tmp['subject'])."'.\n\n".$name."\n".$shortname." Player Support";
    $_POST['username'] = $tmp['username'];
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
 
<form action='index.php?p=alert&do=placeAlert' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>Alert User</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Username</b><div class='graytext'>The name of the user you want to give this alert to.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='username' value="<?php echo $_POST['username']; ?>" size='50' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Alert</b><div class='graytext'>The actual message that will be shown to the user.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><textarea name='alert' cols='60' rows='5' wrap='soft' id='sub_desc' class='multitext'><?php echo HoloText($_POST['alert']); ?></textarea></td>
</tr>

<tr>
<td class='tablerow1' width='40%' valign='middle'><strong>Server Alert</strong><div class='graytext'>If you check this, this alert will also be sent to the user on the server as a MOD-alert. If you do, please do not use HTML.</div></td>
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