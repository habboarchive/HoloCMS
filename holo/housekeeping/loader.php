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

$pagename = "Loader Configuration";

if(isset($_POST['ip'])){

	$ip = FilterText($_POST['ip']);
	$texts = FilterText($_POST['texts']);
	$variables = FilterText($_POST['variables']);
	$dcr = FilterText($_POST['dcr']);
	$reload_url = FilterText($_POST['reload_url']);
	$localhost = $_POST['localhost'];

	if(!empty($ip) && !empty($texts) && !empty($variables) && !empty($dcr) && !empty($reload_url)){

		mysql_query("UPDATE cms_system SET ip = '".$ip."', texts = '".$texts."', variables = '".$variables."', dcr = '".$dcr."', reload_url = '".$reload_url."', localhost = '".$localhost."', loader='".$_POST['loader']."' LIMIT 1") or die(mysql_error());
		$msg = "Settings saved successfully.";
		mysql_query("UPDATE cms_content SET contentvalue = '".$_POST['widescreen']."' WHERE contentkey='client-widescreen'");

		mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','Updated CMS Settings (Loader Configuration)','loader.php','".$my_id."','','".$date_full."')") or die(mysql_error());

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
<?php @include('sitemenu.php'); ?>
 <!-- / LEFT CONTEXT SENSITIVE MENU -->
 </div>
 </td>
 <td width='78%' valign='top' id='rightblock'>
 <div><!-- RIGHT CONTENT BLOCK -->
 
<?php if(isset($msg)){ ?><p><strong><?php echo $msg; ?></strong></p><?php } ?>
 
<form action='index.php?p=loader&do=save' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>Loader setup</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>External IP Address</b><div class='graytext'>The <b>external</b> IP address or hostname Holograph Emulator is located on. The second field is the port, wich is automaticly detected.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'>
<input type='text' name='ip' value="<?php echo FetchCMSSetting('ip'); ?>" size='30' class='textinput'>&nbsp;:&nbsp;
<input type='text' name='nothing' value="<?php echo FetchServerSetting('server_game_port'); ?>" disabled='disabled' size='6' maxlength='6' class='textinput'>
</td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>External Texts</b><div class='graytext'>URL that points to your external texts.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='texts' value="<?php echo FetchCMSSetting('texts'); ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>External Variables</b><div class='graytext'>URL that points to your external variables.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='variables' value="<?php echo FetchCMSSetting('variables'); ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Habbo DCR</b><div class='graytext'>URL that points to your Habbo DCR file. Crossdomain protection will be bypassed automaticly where needed.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='dcr' value="<?php echo FetchCMSSetting('dcr'); ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Reload URL</b><div class='graytext'>URL that points to the loader.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='reload_url' value="<?php echo FetchCMSSetting('reload_url'); ?>" size='30' class='textinput'></td>
</tr>

<?php $wide = mysql_query("SELECT * FROM cms_content WHERE contentkey='client-widescreen'");
$rowtje = mysql_fetch_assoc($wide);
?>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Widescreen</b><div class='graytext'>This determines wether the game client should display in widescreen mode or not.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><select name='widescreen' class='dropdown'><option value='1'>Enabled</option><option <?php if($rowtje['contentvalue'] == 0) { echo "selected=selected"; } ?> value='0'>Disabled</option></select></td>
</tr>

<?php $V23 = mysql_query("SELECT * FROM cms_system");
$rok = mysql_fetch_assoc($V23);
?>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>V23 loader</b><div class='graytext'>Do you want to get the V23 loader with 'Opening hotelname...' or do you just want the old loader?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><select name='loader' class='dropdown'><option value='1'>V23 loader</option><option <?php if($rok['loader'] == 0) { echo "selected=selected"; } ?> value='0'>Non-V23 loader</option></select></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Server is on Localhost</b><div class='graytext'>If Holograph Emulator is running on the same server/computer as HoloCMS, set this to 'Yes'.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><select name='localhost'  class='dropdown'>
									<option value='1'>Yes</option>
									<option value='0' <?php if(FetchCMSSetting('localhost') == "0"){ echo "selected='selected'"; } ?>>No</option>
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