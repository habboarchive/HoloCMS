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

$pagename = "Welcome Message Options";

if(isset($_POST['welcomemessage_enable'])){

	$welcomemessage_enable = $_POST['welcomemessage_enable'];
	$welcomemessage_text = $_POST['welcomemessage_text'];

	if(!empty($welcomemessage_text)){

		mysql_query("UPDATE system_config SET sval = '".$welcomemessage_enable."' WHERE skey = 'welcomemessage_enable' LIMIT 1") or die(mysql_error());
		mysql_query("UPDATE system_strings SET var_en = '".$welcomemessage_text."' WHERE stringid = 'welcomemessage_text' LIMIT 1") or die(mysql_error());

		$msg = "Settings saved successfully.";

		mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','Updated Server Settings (Welcome Message Options)','welcomemsg.php','".$my_id."','','".$date_full."')") or die(mysql_error());

	} else {

		$msg = "Please do not leave any fields blank. Settings not saved.";

	}

}

@include('subheader.php');
@include('header.php');
?>
<table cellpadding='0' cellspacing='8' width='100%' id='tablewrap'>
<tr> <td width='22%' valign='top' id='leftblock'>
 <div>
 <!-- LEFT CONTEXT SENSITIVE MENU -->
<?php @include('servermenu.php'); ?>
 <!-- / LEFT CONTEXT SENSITIVE MENU -->
 </div>
 </td>
 <td width='78%' valign='top' id='rightblock'>
 <div><!-- RIGHT CONTENT BLOCK -->
 
<?php if(isset($msg)){ ?><p><strong><?php echo $msg; ?></strong></p><?php } ?>
 
<form action='index.php?p=welcomemsg&do=save' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>Welcome Message Options</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Enable Welcome Message</b></td>
<td class='tablerow2'  width='60%'  valign='middle'><select name='welcomemessage_enable'  class='dropdown'>
									<option value='1'>Enabled</option>
									<option value='0' <?php if(FetchServerSetting('welcomemessage_enable') == "0"){ echo "selected='selected'"; } ?>>Disabled</option>
								    </select>

</td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Welcome Message</b><div class='graytext'>The actual message that will be shown to the user upon login, if enabled.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><textarea name='welcomemessage_text' cols='60' rows='5' wrap='soft' id='sub_desc'   class='multitext'><?php $check = mysql_query("SELECT var_en FROM system_strings WHERE stringid = 'welcomemessage_text' LIMIT 1") or die(mysql_error()); $row = mysql_fetch_assoc($check); echo $row['var_en']; ?></textarea></td>
</tr>

<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='Save Options' class='realbutton' accesskey='s'></td></tr>
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