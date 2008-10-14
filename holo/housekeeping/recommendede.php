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

if($_GET['a'] == "delete"){
mysql_query("DELETE FROM cms_recommended WHERE id = '".$_GET['key']."'");
header("Location: index.php?p=recommendedm");
}

if(!isset($_GET['a'])){
	$_GET['a'] = "add";
}

$pagename = "Recommended";

@include('subheader.php');
@include('header.php');

if(isset($_POST['edit']) && isset($_POST['title']) && isset($_POST['description']) && isset($_POST['furni_image']) && isset($_POST['furni_image_small']) && isset($_POST['year']) && isset($_POST['month']) && isset($_POST['tid'])) {
mysql_query("UPDATE cms_recommended SET type='".$_POST['type']."',rec_id='".$_POST['rec_id']."' WHERE id='".$_GET['key']."' LIMIT 1") or die(mysql_error());
$msg = "Succesfully modified!";
}elseif(isset($_POST['add'])) {
mysql_query("INSERT INTO cms_recommended (type,rec_id) VALUES ('".$_POST['type']."','".$_POST['rec_id']."')") or die(mysql_error());
$msg = "Recommended added.";
}else{
$msg = "Fill in everything!";
}

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
 
<form action='index.php?p=recommendede&a=<?php echo $_GET['a']; ?><?php if(isset($_GET['key'])) { echo "&key=".$_GET['key'].""; } ?>' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'><?php if($_GET['a'] == "add"){ echo "Add "; }elseif($_GET['a'] == "edit") { echo "Edit "; } ?>Recommended</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<?php if($_GET['a'] == "add") { ?>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Type</b><div class='graytext'>Type either 'group' or 'room' (not yet supported)</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='type' value="<?php echo $_POST['type']; ?>" maxlength='5' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>ID</b><div class='graytext'>The ID of the recommendation.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='rec_id' value="<?php echo $_POST['rec_id']; ?>" maxlength='175' class='textinput'></td>
</tr>

<?php }elseif($_GET['a'] == "edit") {
		if(isset($_GET['key'])) {
		$sql = mysql_query("SELECT * FROM cms_recommended WHERE id='".HoloText($_GET['key'])."' LIMIT 1");
		$row = mysql_fetch_assoc($sql);?>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Type</b><div class='graytext'>Type either 'group' or 'room' (not yet supported)</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='type' value="<?php echo $row['type']; ?>" maxlength='5' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>ID</b><div class='graytext'>The ID of the recommendation.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='rec_id' name='description' value="<?php echo $row['rec_id']; ?>" maxlength='175' class='textinput'></td>
</tr>

<?php }
} ?>

<?php if(isset($_GET['a']) && !isset($_GET['key'])) {
		if($_GET['a'] == "edit") {
echo "If you want to edit a recommended, please go to the over-view and than click on the edit-icon.";
	}
}	?>
<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' name='<?php if($_GET['a'] == "add") { echo "add"; }else{ echo "edit"; } ?>' value='<?php if($_GET['a'] == "add") { echo "Add recommended"; }else{ echo "Edit recommended"; } ?>' class='realbutton' accesskey='s'></td></tr>
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