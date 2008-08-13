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

$pagename = "Manage Existing Articles";

if($do == "delete" && is_numeric($key)){

	$check = mysql_query("SELECT num FROM cms_news WHERE num = '".$key."' LIMIT 1") or die(mysql_error());
	$exists = mysql_num_rows($check);

	if($exists > 0){
		mysql_query("DELETE FROM cms_news WHERE num = '".$key."' LIMIT 1") or die(mysql_error());
		$msg = "Article deleted successfully.";
	} else {
		$msg = "Unable to delete article; this article does not exist!";
	}

} elseif($do == "edit" && is_numeric($key)){

	$check = mysql_query("SELECT * FROM cms_news WHERE num = '".$key."' LIMIT 1") or die(mysql_error());
	$exists = mysql_num_rows($check);

	if($exists > 0){
		$article = mysql_fetch_assoc($check);
		$editor_mode = true;
	} else {
		$msg = "Unable to edit article; this article does not exist!";
	}

} elseif($do == "save" && is_numeric($key) && isset($_POST['topstory'])){

	$check = mysql_query("SELECT num FROM cms_news WHERE num = '".$key."' LIMIT 1") or die(mysql_error());
	$exists = mysql_num_rows($check);

	if($exists > 0){

		$num = $key;

		$title = addslashes($_POST['title']);
		$category = addslashes($_POST['category']);
		$topstory = addslashes($_POST['topstory']);
		$short_story = addslashes($_POST['short_story']);
		$story = addslashes($_POST['story']);

		mysql_query("UPDATE cms_news SET title = '".$title."', category = '".$category."', topstory = '".$topstory."', short_story = '".$short_story."', story = '".$story."' WHERE num = '".$num."' LIMIT 1") or die(mysql_error());

		$msg = "Article updated successfully.";
		$editor_mode = false;

	} else {

		$msg = "Unable to edit article; this article does not exist!";

	}

} elseif($do == "bounce"){

	$msg = "Article published successfully.";

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
 
<?php if($editor_mode !== true){ ?>
<?php if(isset($msg)){ ?><p><strong><?php echo $msg; ?></strong></p><?php } ?>
 
<form action='index.php?p=news_manage&do=save' method='post' name='theAdminForm' id='theAdminForm'>
 <div class='tableborder'>
 <div class='tableheaderalt'>News Articles</div>
 <table cellpadding='4' cellspacing='0' width='100%'>
 <tr>
  <td class='tablesubheader' width='1%' align='center'>ID</td>
  <td class='tablesubheader' width='28%'>Title</td>
  <td class='tablesubheader' width='10%' align='center'>Date</td>
  <td class='tablesubheader' width='10%' align='center'>Author</td>
  <td class='tablesubheader' width='10%' align='center'>Edit</td>
  <td class='tablesubheader' width='12%' align='center'>Delete</td>
 </tr>
<?php
$get_articles = mysql_query("SELECT num,title,short_story,date,author FROM cms_news ORDER BY num DESC") or die(mysql_error());

while($row = mysql_fetch_assoc($get_articles)){
	printf(" <tr>
  <td class='tablerow1' align='center'>%s</td>
  <td class='tablerow2'><strong>%s</strong><div class='desctext'>%s</div></td>
  <td class='tablerow2' align='center'>%s</td>
  <td class='tablerow2' align='center'>%s</td>
  <td class='tablerow2' align='center'><a href='index.php?p=news_manage&do=edit&key=%s'><img src='./images/edit.gif' alt='Edit'></a></td>
  <td class='tablerow2' align='center'><a href='index.php?p=news_manage&do=delete&key=%s'><img src='./images/delete.gif' alt='Delete'></a></td>															
</tr>", $row['num'], htmlspecialchars(stripslashes($row['title'])), htmlspecialchars(stripslashes($row['short_story'])), $row['date'], $row['author'], $row['num'], $row['num']);
}
?>
 
 </table>
 <div class='tablefooter' align='center'><div class='fauxbutton-wrapper'><span class='fauxbutton'><a href='index.php?p=news_compose'>Compose New News Item</a></span></div></div>
</div>
<?php } else { ?>
<form action='index.php?p=news_manage&do=save&key=<?php echo $article['num']; ?>' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>Edit News Article (<?php echo $article['title']; ?>)</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Title</b><div class='graytext'>The full title of your article.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='title' value="<?php echo stripslashes($article['title']); ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>News Category</b></td>
<td class='tablerow2'  width='60%'  valign='middle'><select name='category' class='dropdown'><option value='HoloCMS' <?php if($article['category'] == "HoloCMS"){ echo 'selected'; } ?>>HoloCMS</option> <option value='News' <?php if($article['category'] == "News"){ echo 'selected'; } ?>>News</option> <option value='Furniture' <?php if($article['category'] == "Furniture"){ echo 'selected'; } ?>>Furniture</option> <option value='Updates' <?php if($article['category'] == "Updates"){ echo 'selected'; } ?>>Updates</option> <option value='Server' <?php if($article['category'] == "Server"){ echo 'selected'; } ?>>Server</option> <option value='Credits' <?php if($article['category'] == "Credits"){ echo 'selected'; } ?>>Credits</option> <option value='<?php echo $shortname; ?> Club' <?php if($article['category'] == "".$shortname." Club"){ echo 'selected'; } ?>><?php echo $shortname; ?> Club</option> <option value='Maintenance' <?php if($article['category'] == "Maintenance"){ echo 'selected'; } ?>>Maintenance</option> <option value='Technical' <?php if($article['category'] == "Technical"){ echo 'selected'; } ?>>Technical</option> <option value='Downtime' <?php if($article['category'] == "Downtime"){ echo 'selected'; } ?>>Downtime</option> <option value='Website' <?php if($article['category'] == "Website"){ echo 'selected'; } ?>>Website</option> <option value='Special Offers' <?php if($article['category'] == "Special Offers"){ echo 'selected'; } ?>>Special Offers</option> <option value='Sponsored' <?php if($article['category'] == "Sponsored"){ echo 'selected'; } ?>>Sponsored</option> <option value='Events & Competitions' <?php if($article['category'] == "Security"){ echo 'selected'; } ?>>Events & Competitions</option> <option value='Security' <?php if($article['category'] == "Security"){ echo 'selected'; } ?>>Security</option> <option value='Staff' <?php if($article['category'] == "Staff"){ echo 'selected'; } ?>>Staff</option> <option value='Announcements' <?php if($article['category'] == "Announcements"){ echo 'selected'; } ?>>Announcements</option> <option value='Tips' <?php if($article['category'] == "Tips"){ echo 'selected'; } ?>>Tips</option> <option value='Holograph Emulator' <?php if($article['category'] == "Holograph Emulator"){ echo 'selected'; } ?>>Holograph Emulator</option> <option value='Other'>Other</option> </select> 
</td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Topstory Image</b><div class='graytext'>The URL to the topstory image.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='topstory' value="<?php echo $article['topstory']; ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Short Story</b><div class='graytext'>A small introduction to the article.<br />HTML is not allowed here.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><textarea name='short_story' cols='60' rows='5' wrap='soft' id='sub_desc'   class='multitext'><?php echo stripslashes($article['short_story']); ?></textarea></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Story</b><div class='graytext'>The actual news message.<br />HTML is allowed here.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><textarea name='story' cols='60' rows='5' wrap='soft' id='sub_desc'   class='multitext'><?php echo stripslashes($article['story']); ?></textarea></td>
</tr>

<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='Update Article' class='realbutton' accesskey='s'></td></tr>
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