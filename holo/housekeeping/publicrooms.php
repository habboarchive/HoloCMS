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

$pagename = "Publicroom editor";

if(isset($_POST['category'])) {
	if($_POST['category'] == 2) {?>
<html>
<head>
  <title>Redirecting...</title>
  <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
  <style type="text/css">body { background-color: #e3e3db; text-align: center; font: 11px Verdana, Arial, Helvetica, sans-serif; } a { color: #fc6204; }</style>
</head>
<body>

<script type="text/javascript">window.location.replace('index.php?=add_publicroom');</script><noscript><meta http-equiv="Refresh" content="0;URL=index.php?=add_publicroom"></noscript>

<p class="btn">If you are not automatically redirected, please <a href="index.php?=add_publicroom" id="manual_redirect_link">click here</a></p>

</body>
</html>
<?php
die;
	}
}

@include('subheader.php');
@include('header.php');

$catId = $_POST['category'];

if(empty($catId) || !is_numeric($catId) || $catId < 1 || $catId > 5){
    $catId = 1;
} else {
    $catId = $catId;
}
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
 
 
 
 <table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>

<form action='index.php?p=editor_publicrooms&do=jumpCategory' method='post' name='Jumper!' id='Jumper!'>
<div class='tableborder'>
<div class='tableheaderalt'>Select Category</div>
<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
    <td class='tablerow2'  width='100%'  valign='middle'  align='center'>
        <select name='category' class='dropdown'>
            <option value='1' <?php if($catId == "1"){ echo "selected='selected'"; } ?>>View and edit</option>
            <option value='2' <?php if($catId == "2"){ echo "selected='selected'"; } ?>>Add</option>
        </select>
        &nbsp;
        <input type='submit' value='Go' class='realbutton' accesskey='s'>
    </td>
</tr>
</table>
</div>
</form>

<br />
 
 
 <?php if(isset($_POST['1'])) { ?>
 <div class='tableborder'>
 <div class='tableheaderalt'><?php echo $sitename; ?> publicrooms editor</div>
 <table cellpadding='4' cellspacing='0' width='100%'>
 <tr>
  <td class='tablesubheader' width='2%' align='center'>Room ID</td>
  <td class='tablesubheader' width='1%' align='center'>Model</td>
  <td class='tablesubheader' width='32%' align='center'>Roomname</td>
  <td class='tablesubheader' width='32%' align='center'>Description</td>
  <td class='tablesubheader' width='31%' align='center'>Type</td>
  <td class='tablesubheader' width='2%' align='center'>Edit</td>
 </tr>
<?php
$get_rooms = mysql_query("SELECT * FROM rooms WHERE owner IS NULL") or die(mysql_error());

while($row = mysql_fetch_assoc($get_rooms)){ ?>
 <tr>
  <td class='tablerow1' align='center'><?php echo $row['id']; ?></td>
  <td class='tablerow2'><?php echo HoloText($row['model']); ?></div></td>
  <td class='tablerow2' align='center'><?php echo HoloText($row['name']); if($row['name'] == "" OR $row['name'] == " ") { echo "<b><i>No roomname</i></b>"; } ?></td>
  <td class='tablerow2' align='center'><?php echo HoloText($row['description']); if($row['description'] == "" OR $row['description'] == " ") { echo "<b><i>No roomdescription</i></b>"; } ?></td>
  <td class='tablerow2' align='center'><?php if($row['state'] == 0) { echo "Open"; }elseif($row['state'] == 1) { echo "Locked (visitors have to ring bell)"; }elseif($row['state'] == 2) { echo "Password protected"; }elseif($row['state'] == 3) { echo "HC Only"; }elseif($row['state'] == 4) { echo "Staff only"; }else{ echo "Unknown"; } ?></td>
  <td class='tablerow2' align='center'><a href='index.php?p=editpublicroom&key=<?php echo $row['id']; ?>&a=edit'><img src='./images/edit.gif' alt='Edit publicroom'></a> <a href='index.php?p=editpublicroom&key=<?php echo $row['id']; ?>&a=delete'><img src='./images/delete.gif' alt='Delete publicroom'></a></td>
</tr>
<?php } ?>
 
 </table>
</div>
<?php }else{ ?>
 <div class='tableborder'>
 <div class='tableheaderalt'><?php echo $sitename; ?> publicrooms editor</div>
 <table cellpadding='4' cellspacing='0' width='100%'>
 <tr>
  <td class='tablesubheader' width='2%' align='center'>Room ID</td>
  <td class='tablesubheader' width='1%' align='center'>Model</td>
  <td class='tablesubheader' width='32%' align='center'>Roomname</td>
  <td class='tablesubheader' width='32%' align='center'>Description</td>
  <td class='tablesubheader' width='31%' align='center'>Type</td>
  <td class='tablesubheader' width='2%' align='center'>Edit</td>
 </tr>
<?php
$get_rooms = mysql_query("SELECT * FROM rooms WHERE owner IS NULL") or die(mysql_error());

while($row = mysql_fetch_assoc($get_rooms)){ ?>
 <tr>
  <td class='tablerow1' align='center'><?php echo $row['id']; ?></td>
  <td class='tablerow2'><?php echo HoloText($row['model']); ?></div></td>
  <td class='tablerow2' align='center'><?php echo HoloText($row['name']); if($row['name'] == "" OR $row['name'] == " ") { echo "<b><i>No roomname</i></b>"; } ?></td>
  <td class='tablerow2' align='center'><?php echo HoloText($row['description']); if($row['description'] == "" OR $row['description'] == " ") { echo "<b><i>No roomdescription</i></b>"; } ?></td>
  <td class='tablerow2' align='center'><?php if($row['state'] == 0) { echo "Open"; }elseif($row['state'] == 1) { echo "Locked (visitors have to ring bell)"; }elseif($row['state'] == 2) { echo "Password protected"; }elseif($row['state'] == 3) { echo "HC Only"; }elseif($row['state'] == 4) { echo "Staff only"; }else{ echo "Unknown"; } ?></td>
  <td class='tablerow2' align='center'><a href='index.php?p=editpublicroom&key=<?php echo $row['id']; ?>&a=edit'><img src='./images/edit.gif' alt='Edit publicroom'></a> <a href='index.php?p=editpublicroom&key=<?php echo $row['id']; ?>&a=delete'><img src='./images/delete.gif' alt='Delete publicroom'></a></td>
</tr>
<?php } ?>
 
 </table>
</div>
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