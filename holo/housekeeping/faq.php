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

if(!isset($_POST['category'])){ // do not try to save when it's a category jump

}

$pagename = "FAQ";

if($_GET['cat'] != ""){
	$page = "items";
	$cat = $_GET['cat'];
}elseif($_GET['edit'] == "cat"){
	$page = "editcat";
	$id = $_GET['key'];
}elseif($_GET['edit'] == "item"){
	$page = "edititem";
	$id = $_GET['key'];
}elseif($_GET['delete'] == "cat"){
	$page = "main";
	$id = $_GET['key'];
	mysql_query("DELETE FROM cms_faq WHERE catid = '".$id."'");
	mysql_query("DELETE FROM cms_faq WHERE id = '".$id."' LIMIT 1");
	$msg = "Category deleted."; 
}elseif($_GET['delete'] == "item"){
	$page = "main";
	$id = $_GET['key'];
	mysql_query("DELETE FROM cms_faq WHERE id = '".$id."' LIMIT 1");
	$msg = "Item deleted.";
}elseif($_GET['do'] == "savecat"){
	$page = "editcat";
	$id = $_POST['id'];
	if($id == ""){
		mysql_query("INSERT INTO cms_faq (type,title,content) VALUES ('cat','".$_POST['title']."','".addslashes($_POST['content'])."')");
	}else{
		mysql_query("UPDATE cms_faq SET title = '".$_POST['title']."', content = '".addslashes($_POST['content'])."' WHERE id = '".$id."' LIMIT 1");
	}
	$msg = "Category saved.";
}elseif($_GET['do'] == "saveitem"){
	$page = "edititem";
	$id = $_POST['id'];
	$catid = $_POST['catid'];
	if($id == ""){
		mysql_query("INSERT INTO cms_faq (type,title,content,catid) VALUES ('item','".$_POST['topic']."','".addslashes($_POST['content'])."','".$catid."')");
	}else{
		mysql_query("UPDATE cms_faq SET title = '".$_POST['topic']."', content = '".addslashes($_POST['content'])."' WHERE id = '".$id."' LIMIT 1");
	}
	$msg = "Item saved.";
}elseif($_GET['cat'] == "" OR $_GET['cat'] == "0"){
	$page = "main";
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
<?php if($page == "main"){ ?>
 <div class='tableborder'>
 <div class='tableheaderalt'>FAQ Categorys</div>
 <table cellpadding='4' cellspacing='0' width='100%'>
 <tr>
  <td class='tablesubheader' width='10%' align='center'>ID</td>
  <td class='tablesubheader' width='25%' align='center'>Name</td>
  <td class='tablesubheader' width='55%' align='center'>Description</td>
  <td class='tablesubheader' width='10%' align='center'>Edit</td>
 </tr>
<?php
$sql = mysql_query("SELECT * FROM cms_faq WHERE type = 'cat' ORDER BY id ASC") or die(mysql_error());

while($row = mysql_fetch_assoc($sql)){ ?>
<tr>
  <td class='tablerow1' align='center'><?php echo $row['id']; ?></td>
  <td class='tablerow2' align='center'><a href="index.php?p=faq&cat=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a></td>
  <td class='tablerow1' align='center'><?php echo htmlspecialchars(stripslashes($row['content'])); ?></div></td>
  <td class='tablerow2' align='center'><a href='index.php?p=faq&key=<?php echo $row['id']; ?>&edit=cat'><img src='./images/edit.gif' alt='Edit'></a> <a href='index.php?p=faq&key=<?php echo $row['id']; ?>&delete=cat'><img src='./images/delete.gif' alt='Delete'></a></td>
</tr>
<?php } ?>
 
 </table>
 <div class='tablefooter' align='center'><div class='fauxbutton-wrapper'><span class='fauxbutton'><a href='index.php?p=faq&edit=cat'>Create New Category</a></span></div></div>
</div>
<?php }elseif($page == "editcat"){ ?>
<?php if(isset($msg)){ ?><p><strong><?php echo $msg; ?></strong></p><?php } ?>
<form action='index.php?p=faq&do=savecat' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>Edit/Create Category</div>
<?php
if(isset($_GET['key'])){
	$row = mysql_fetch_assoc(mysql_query("SELECT * FROM cms_faq WHERE id = '".$_GET['key']."' LIMIT 1"));
	$title = $row['title'];
	$content = stripslashes($row['content']);
}else{
	$title = $_POST['title'];
	$content = $_POST['content'];
}
?>
<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Title</b><div class='graytext'>The title of your category.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='title' value="<?php echo $title; ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Content</b><div class='graytext'>The content displayed when clicked.<br />HTML is allowed here.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><textarea name='content' cols='60' rows='5' wrap='soft' id='sub_desc'   class='multitext'><?php echo $content; ?></textarea></td>
</tr>

<input type="hidden" name="id" value="<?php echo $_GET['key']; ?>">

<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='Save' class='realbutton' accesskey='s'></td></tr>
</form></table></div><br />
<?php }elseif($page == "items"){ 
$id = $_GET['cat'];
$sql = mysql_query("SELECT * FROM cms_faq WHERE id = '".$id."'");
$row = mysql_fetch_assoc($sql);
?>
 <div class='tableborder'>
 <div class='tableheaderalt'>Items for: <?php echo $row['title']; ?></div>
 <table cellpadding='4' cellspacing='0' width='100%'>
 <tr>
  <td class='tablesubheader' width='10%' align='center'>ID</td>
  <td class='tablesubheader' width='40%' align='center'>Topic</td>
  <td class='tablesubheader' width='40%' align='center'>Content</td>
  <td class='tablesubheader' width='10%' align='center'>Edit</td>
 </tr>
<?php
$sql = mysql_query("SELECT * FROM cms_faq WHERE catid = '".$id."'");

while($row1 = mysql_fetch_assoc($sql)){ ?>
<tr>
  <td class='tablerow1' align='center'><?php echo $row1['id']; ?></td>
  <td class='tablerow2' align='center'><?php echo $row1['title']; ?></td>
  <td class='tablerow1' align='center'><?php echo htmlspecialchars(stripslashes($row1['content'])); ?></div></td>
  <td class='tablerow2' align='center'><a href='index.php?p=faq&key=<?php echo $row1['id']; ?>&edit=item'><img src='./images/edit.gif' alt='Edit'></a> <a href='index.php?p=faq&key=<?php echo $row1['id']; ?>&delete=item'><img src='./images/delete.gif' alt='Delete'></a></td>
</tr>
<?php } ?>
 
 </table>
 <div class='tablefooter' align='center'><div class='fauxbutton-wrapper'><span class='fauxbutton'><a href='index.php?p=faq&catid=<?php echo $id; ?>&edit=item'>Create New Item</a></span></div></div>
</div>
<?php }elseif($page == "edititem"){ ?>
<?php if(isset($msg)){ ?><p><strong><?php echo $msg; ?></strong></p><?php } ?>
<form action='index.php?p=faq&do=saveitem' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>Edit/Create Item</div>
<?php
if(isset($_GET['key'])){
	$row = mysql_fetch_assoc(mysql_query("SELECT * FROM cms_faq WHERE id = '".$_GET['key']."' LIMIT 1"));
	$topic = $row['title'];
	$content = stripslashes($row['content']);
}else{
	$topic = $_POST['topic'];
	$content = $_POST['content'];
}
?>
<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Topic</b><div class='graytext'>The topic of your item.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='topic' value="<?php echo $topic; ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Content</b><div class='graytext'>The content displayed when clicked.<br />HTML is allowed here.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><textarea name='content' cols='60' rows='5' wrap='soft' id='sub_desc'   class='multitext'><?php echo $content; ?></textarea></td>
</tr>

<input type="hidden" name="catid" value="<?php echo $_GET['catid']; ?>">
<input type="hidden" name="id" value="<?php echo $_GET['key']; ?>">

<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='Save' class='realbutton' accesskey='s'></td></tr>
</form></table></div><br />
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