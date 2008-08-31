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

$pagename = "HoloCMS Configuration";

if(isset($_POST['sitename'])){

	$sitename = addslashes($_POST['sitename']);
	$shortname = addslashes($_POST['shortname']);
	$enable_sso = $_POST['enable_sso'];
	$start_credits = $_POST['credits'];
	$language = addslashes($_POST['language']);
	$analytics = addslashes($_POST['analytics']);

	if(!empty($sitename) && !empty($shortname) && !empty($language)){

		mysql_query("UPDATE cms_system SET sitename = '".$sitename."', shortname = '".$shortname."', enable_sso = '".$enable_sso."', language = '".$language."' , start_credits='".$start_credits."', analytics = '".$analytics."' LIMIT 1") or die(mysql_error());
		$msg = "Settings saved successfully.";

		mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','Updated CMS Settings (General Configuration)','cms_config.php','".$my_id."','','".$date_full."')") or die(mysql_error());

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
 
<form action='index.php?p=site&do=save' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>HoloCMS Configuration</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Sitename</b><div class='graytext'>This is the full name of your site, eg. 'Holo Hotel'.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='sitename' value="<?php echo FetchCMSSetting('sitename'); ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Shortname</b><div class='graytext'>This is the 'shortname' of your site, eg. 'Holo'.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='shortname' value="<?php echo FetchCMSSetting('shortname'); ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Enable SSO</b><div class='graytext'>If enabled, SSO tickets will be generated and users will be logged on using SSO.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><select name='enable_sso'  class='dropdown'>
									<option value='1'>Enabled</option>
									<option value='0' <?php if(FetchCMSSetting('enable_sso') == "0"){ echo "selected='selected'"; } ?>>Disabled</option>
								    </select>
</td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Language</b><div class='graytext'>This is the 2-character language code your site will read locale files from, eg. 'en'. If the language is invalid, English will be used.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='language' value="<?php echo FetchCMSSetting('language'); ?>" size='2' maxlength='2' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Start credits</b><div class='graytext'>How many credits do people get if they register on the site?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='credits' value="<?php echo FetchCMSSetting('start_credits'); ?>" size='2' maxlength='5' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Google Analytics Code</b><div class='graytext'>Code for Google Analytics placed in every page.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><textarea name='analytics' cols='61' rows='3' wrap='soft' id='sub_desc' class='multitext'><?php echo stripslashes(FetchCMSSetting('analytics')); ?></textarea></td>
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