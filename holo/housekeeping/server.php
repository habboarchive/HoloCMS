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

$pagename = "Server Configuration";

if(isset($_POST['game_port'])){

	$game_port = $_POST['game_port'];
	$mus_port = $_POST['mus_port'];
	$game_maxconnections = $_POST['game_maxconnections'];
	$mus_maxconnections = $_POST['mus_maxconnections'];
	$mus_host = $_POST['mus_host'];
	$trading_enable = $_POST['trading_enable'];
	$chatanims_enable = $_POST['chatanims_enable'];

	if(!empty($game_port) && !empty($mus_port) && !empty($game_maxconnections) && !empty($mus_maxconnections) && !empty($mus_host)){

		mysql_query("UPDATE system_config SET sval = '".$game_port."' WHERE skey = 'server_game_port' LIMIT 1") or die(mysql_error());
		mysql_query("UPDATE system_config SET sval = '".$mus_port."' WHERE skey = 'server_mus_port' LIMIT 1") or die(mysql_error());
		mysql_query("UPDATE system_config SET sval = '".$game_maxconnections."' WHERE skey = 'server_game_maxconnections' LIMIT 1") or die(mysql_error());
		mysql_query("UPDATE system_config SET sval = '".$mus_maxconnections."' WHERE skey = 'server_mus_maxconnections' LIMIT 1") or die(mysql_error());
		mysql_query("UPDATE system_config SET sval = '".$mus_host."' WHERE skey = 'server_mus_host' LIMIT 1") or die(mysql_error());
		mysql_query("UPDATE system_config SET sval = '".$trading_enable."' WHERE skey = 'trading_enable' LIMIT 1") or die(mysql_error());
		mysql_query("UPDATE system_config SET sval = '".$chatanims_enable."' WHERE skey = 'chatanims_enable' LIMIT 1") or die(mysql_error());

		$msg = "Settings saved successfully.";

		mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','Updated Server Settings (General Configuration)','server.php','".$my_id."','','".$date_full."')") or die(mysql_error());

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
 
<form action='index.php?p=server&do=save' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>General Configuration</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Game Port</b><div class='graytext'>This is the port the game server will listen on.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='game_port' value="<?php echo FetchServerSetting('server_game_port'); ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>MUS Port</b><div class='graytext'>This is the port the MUS socket will listen on.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='mus_port' value="<?php echo FetchServerSetting('server_mus_port'); ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Maximum allowed connections</b><div class='graytext'>The amount of users online is limited to this value.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='game_maxconnections' value="<?php echo FetchServerSetting('server_game_maxconnections'); ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Maximum allowed MUS connections</b><div class='graytext'>The amount of allowed MUS socket connections is limited to this value.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='mus_maxconnections' value="<?php echo FetchServerSetting('server_mus_maxconnections'); ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>MUS Host</b><div class='graytext'>The only IP address or hostname the MUS socket will accept connections from.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='mus_host' value="<?php echo FetchServerSetting('server_mus_host'); ?>" size='30' class='textinput'></td>
</tr>

<tr><td align='left' class='tablesubheader' colspan='2' >Special Options</td></tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Enable trading</b></td>
<td class='tablerow2'  width='60%'  valign='middle'><select name='trading_enable'  class='dropdown'>
									<option value='1'>Enabled</option>
									<option value='0' <?php if(FetchServerSetting('trading_enable') == "0"){ echo "selected='selected'"; } ?>>Disabled</option>
								    </select>

</td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Enable chat animations</b></td>
<td class='tablerow2'  width='60%'  valign='middle'><select name='chatanims_enable'  class='dropdown'>
									<option value='1'>Enabled</option>
									<option value='0' <?php if(FetchServerSetting('chatanims_enable') == "0"){ echo "selected='selected'"; } ?>>Disabled</option>
								    </select>

</td>
</tr>

<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='Save Configuration' class='realbutton' accesskey='s'></td></tr>
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