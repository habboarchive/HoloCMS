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

set_time_limit(7200);

require_once('../core.php');
if($hkzone !== true){ header("Location: index.php?throwBack=true"); exit; }
if(!session_is_registered(acp)){ header("Location: index.php?p=login"); exit; }

$pagename = "Create Newsletter";

if($do == "publish" && isset($_POST['body'])){

	$num = $key;

	$subject = FilterText($_POST['subject'], true);
	$where = $_POST['where'];
	$body = $_POST['body'];
	$header = HoloText(getContent('newsletter-1header'), true);
	$footer = HoloText(getContent('newsletter-2footer'), true);
	$from = HoloText(getContent('newsletter-3from'), true);
	$fromname = HoloText(getContent('newsletter-4fromname'), true);
	$headers  = '';
	$headers  = 'Return-Path: <'.$from.'>' . "\r\n";
	$headers  = 'From: '.$fromname.' <'.$from.'>' . "\r\n";
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-Type: multipart/related; ' . "\r\n";
	$headers .= '	boundary="----=_Part_1218195_2242574.1223844236223"' . "\r\n";
	$headers .= 'Precedence: Bulk' . "\r\n";
	if($where == ""){ $where = "newsletter = '1'"; }
	$sql = mysql_query("SELECT * FROM users WHERE ".$where);
	$i = 0;
	while($row = mysql_fetch_assoc($sql)){
$to = $row['email'];
$message = '------=_Part_1218195_2242574.1223844236223
Content-Type: multipart/alternative; 
	boundary="----=_Part_1218194_9233741.1223844236223"

------=_Part_1218194_9233741.1223844236223
Content-Type: text/plain; charset=ISO-8859-1
Content-Transfer-Encoding: 7bit

'.preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", strip_tags(str_replace("<br />", "\n", str_replace("%name%", $row['name'], $header.$body.$footer)))).'

------=_Part_1218194_9233741.1223844236223
Content-Type: text/html;charset=ISO-8859-1
Content-Transfer-Encoding: 7bit

'.str_replace("%name%", $row['name'], $header.$body.$footer).'

------=_Part_1218194_9233741.1223844236223--';
mail($to, $subject, $message, $headers);
$i++;
}
$msg = "Attempted to send the newsletter to ".$i." users.";
}

$template = '<h1 style="font-size: 13px; font-family: verdana,times,times new roman; color: rgb(71, 131, 157); padding-left: 20px;">Hi %name% from everyone at '.$sitename.'!</h1>

<p style="font-family: Verdana; color: black; font-size: 12px; padding-left: 20px; padding-right: 20px;">
<br />This a sample message, edit it to your likings.</p>

<span style="font-family: Verdana;"></span>

<h1 style="font-size: 13px; font-family: verdana,times,times new roman; color: rgb(71, 131, 157); padding-left: 20px;"><a href="'.$path.'">Visit '.$shortname.' Now! &gt;&gt;</a></h1>';

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
<form action='index.php?p=newsletter&do=publish' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>Compose News Article</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Subject</b><div class='graytext'>The subject of the newsletter.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='subject' value="<?php echo $_POST['subject']; ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>WHERE clause</b><div class='graytext'>For advanced users, the WHERE clause of the MySQL query for getting users. DO NOT EDIT UNLESS YOU KNOW WHAT YOU ARE DOING!</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='where' value="<?php if($_POST['where'] == ""){ echo "newsletter = '1' AND email_verified = '1'"; }else{ echo $_POST['where']; } ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Body</b><div class='graytext'>The body of the newsletter. (Header and footer is already there!)<br />HTML is allowed here. Use %name% for the user's name.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><textarea name='body' cols='60' rows='5' wrap='soft' id='sub_desc'   class='multitext'><?php if($_POST['body'] == ""){ echo $template; }else{ echo HoloText($_POST['body'], true); } ?></textarea></td>
</tr>

<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='Publish Newsletter' class='realbutton' accesskey='s'></td></tr>
</form></table></div><br />
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