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

$pagename = "Wordfilter Options";

if(isset($_POST['wordfilter_enable'])){

	$wordfilter_enable = $_POST['wordfilter_enable'];
	$wordfilter_censor = $_POST['wordfilter_censor'];

	if(!empty($wordfilter_censor)){

		mysql_query("UPDATE system_config SET sval = '".$wordfilter_enable."' WHERE skey = 'wordfilter_enable' LIMIT 1") or die(mysql_error());
		mysql_query("UPDATE system_config SET sval = '".$wordfilter_censor."' WHERE skey = 'wordfilter_censor' LIMIT 1") or die(mysql_error());

		$msg = "Settings saved successfully.";

		mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','Updated Server Settings (Wordfilter Options)','wordfilter.php','".$my_id."','','".$date_full."')") or die(mysql_error());

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
 
<form action='index.php?p=wordfilter&do=save' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>Wordfilter Options</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Enable woldfilter</b></td>
<td class='tablerow2'  width='60%'  valign='middle'><select name='wordfilter_enable'  class='dropdown'>
									<option value='1'>Enabled</option>
									<option value='0' <?php if(FetchServerSetting('wordfilter_enable') == "0"){ echo "selected='selected'"; } ?>>Disabled</option>
								    </select>

</td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Worldfilter censor</b><div class='graytext'>The word that will replace filtered words.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='wordfilter_censor' value="<?php echo FetchServerSetting('wordfilter_censor'); ?>" size='30' class='textinput'></td>
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