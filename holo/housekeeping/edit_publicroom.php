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

$pagename = "Edit guestroom";

if(isset($_GET['key'])) {
	if(isset($_POST['name']) || isset($_POST['model']) || isset($_POST['ccts']) || isset($_POST['description']) || isset($_POST['state']) ||  isset($_POST['visitors_max'])) {
	mysql_query("UPDATE rooms SET password='".$_POST['password']."',name='".$_POST['name']."',model='".$_POST['model']."',ccts='".$_POST['ccts']."',floor='".$_POST['floor']."',wallpaper='".$_POST['wallpaper']."',description='".$_POST['description']."',state='".$_POST['state']."',visitors_now='".$_POST['visitors_now']."',visitors_max='".$_POST['visitors_max']."' WHERE id='".$_GET['key']."'") or die(mysql_error());
	$msg = "Succesfully updated!";
	}else{
	$msg = "Fill in all the fields!";
	}
}else{
echo "Give a roomid!";
die;
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
 
<?php
if($_GET['a'] == "delete") {
mysql_query("DELETE FROM rooms WHERE id='".$_GET['key']."' LIMIT 1");
echo "<b>Succesfully deleted!</b><br>";
}

$sql = mysql_query("SELECT * FROM rooms WHERE id='".$_GET['key']."' AND owner IS NULL LIMIT 1");
$row = mysql_fetch_assoc($sql);

if(mysql_num_rows($sql) != 1) {
echo "Room doesn't excist or the room does double excist";
die;
}
if(isset($msg)){ ?><p><strong><?php echo $msg; ?></strong></p><?php } ?>
 
<form method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>Edit publicroom</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Name of room</b><div class='graytext'>What's the name of the room?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type="text" name="name" value="<?php echo $row['name']; ?>"></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Description of room</b><div class='graytext'>What's the description of the room - note, fill in external text data (example: theatredome_halloween)?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type="text" name="description" value="<?php echo $row['description']; ?>"></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>State</b><div class='graytext'>What's the state of the room?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><SELECT style="COLOR: black; FONT-FAMILY: Verdana" name=state> <OPTION value="0"<?php if($row['state'] == 0) { echo " selected"; } ?>>Open</OPTION><OPTION value="1"<?php if($row['state'] == 1) { echo " selected"; } ?>>Closed (bell)</OPTION><OPTION value="2"<?php if($row['state'] == 2) { echo " selected"; } ?>>Password protected</OPTION><OPTION value="3"<?php if($row['state'] == 3) { echo " selected"; } ?>>HC Only</OPTION><OPTION value="4"<?php if($row['state'] == 4) { echo " selected"; } ?>>Staff only</OPTION></SELECT></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Model</b><div class='graytext'>What's the model of the room?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type="text" name="model" value="<?php echo $row['model']; ?>"></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Cct(s)</b><div class='graytext'>What cct(s) is/are used for the room?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type="text" name="ccts" value="<?php echo $row['ccts']; ?>"></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Floor</b></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type="text" name="floor" value="<?php echo $row['floor']; ?>"></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Wallpaper</b></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type="text" name="wallpaper" value="<?php echo $row['wallpaper']; ?>"></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Password of room</b><div class='graytext'>This only works if the room is password protected!</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type="password" name="password" maxlength="20" value="<?php echo $row['password']; ?>"></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Visitors inside right now</b><div class='graytext'>How many visitors are inside right now (you're faking data).</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type="text" name="visitors_now" value="<?php echo $row['visitors_now']; ?>"></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Max visitors inside room</b><div class='graytext'>How many visitors may come inside the room?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type="text" name="visitors_max" value="<?php echo $row['visitors_max']; ?>"></td>
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