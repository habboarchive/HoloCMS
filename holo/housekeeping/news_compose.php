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

$pagename = "Compose New Article";

if($do == "save" && isset($_POST['topstory'])){

	$num = $key;

	$title = FilterText($_POST['title']);
	$category = FilterText($_POST['category']);
	$topstory = FilterText($_POST['topstory']);
	$short_story = FilterText($_POST['short_story']);
	$story = FilterText($_POST['story']);
	$name = FilterText($_POST['author']);

	mysql_query("INSERT INTO cms_news (title,category,topstory,short_story,story,author,date) VALUES ('".$title."','".$category."','".$topstory."','".$short_story."','".$story."','".$name."','".$date_reversed."')") or die(mysql_error());

	header("Location: index.php?p=news_manage&do=bounce");

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
 
<form action='index.php?p=news_compose&do=save' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>Compose News Article</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Title</b><div class='graytext'>The full title of your article.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='title' value="<?php echo $article['title']; ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>News Category</b></td>
<td class='tablerow2'  width='60%'  valign='middle'><select name='category' class='dropdown'><option value='HoloCMS' <?php if($article['category'] == "HoloCMS"){ echo 'selected'; } ?>>HoloCMS</option> <option value='News' <?php if($article['category'] == "News"){ echo 'selected'; } ?>>News</option> <option value='Furniture' <?php if($article['category'] == "Furniture"){ echo 'selected'; } ?>>Furniture</option> <option value='Updates' <?php if($article['category'] == "Updates"){ echo 'selected'; } ?>>Updates</option> <option value='Server' <?php if($article['category'] == "Server"){ echo 'selected'; } ?>>Server</option> <option value='Credits' <?php if($article['category'] == "Credits"){ echo 'selected'; } ?>>Credits</option> <option value='<?php echo $shortname; ?> Club' <?php if($article['category'] == "".$shortname." Club"){ echo 'selected'; } ?>><?php echo $shortname; ?> Club</option> <option value='Maintenance' <?php if($article['category'] == "Maintenance"){ echo 'selected'; } ?>>Maintenance</option> <option value='Technical' <?php if($article['category'] == "Technical"){ echo 'selected'; } ?>>Technical</option> <option value='Downtime' <?php if($article['category'] == "Downtime"){ echo 'selected'; } ?>>Downtime</option> <option value='Website' <?php if($article['category'] == "Website"){ echo 'selected'; } ?>>Website</option> <option value='Special Offers' <?php if($article['category'] == "Special Offers"){ echo 'selected'; } ?>>Special Offers</option> <option value='Sponsored' <?php if($article['category'] == "Sponsored"){ echo 'selected'; } ?>>Sponsored</option> <option value='Events & Competitions' <?php if($article['category'] == "Security"){ echo 'selected'; } ?>>Events & Competitions</option> <option value='Security' <?php if($article['category'] == "Security"){ echo 'selected'; } ?>>Security</option> <option value='Staff' <?php if($article['category'] == "Staff"){ echo 'selected'; } ?>>Staff</option> <option value='Announcements' <?php if($article['category'] == "Announcements"){ echo 'selected'; } ?>>Announcements</option> <option value='Tips' <?php if($article['category'] == "Tips"){ echo 'selected'; } ?>>Tips</option> <option value='Holograph Emulator' <?php if($article['category'] == "Holograph Emulator"){ echo 'selected'; } ?>>Holograph Emulator</option> <option value='Other'>Other</option> </select> 
</td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Topstory Image</b><div class='graytext'>The URL to the topstory image.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='topstory' value="http://images.habbohotel.co.uk/c_images/Top_Story_Images/top_story_web60.png" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Short Story</b><div class='graytext'>A small introduction to the article.<br />HTML is not allowed here.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><textarea name='short_story' cols='60' rows='5' wrap='soft' id='sub_desc'   class='multitext'><?php echo HoloText($article['short_story']); ?></textarea></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Story</b><div class='graytext'>The actual news message.<br />HTML is allowed here.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><textarea name='story' cols='60' rows='5' wrap='soft' id='sub_desc'   class='multitext'><?php echo HoloText($article['story']); ?></textarea></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Author</b><div class='graytext'>Who's the author of the article?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='author' value="<?php echo $article['author']; ?>" size='30' class='textinput'></td>
</tr>

<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='Publish Article' class='realbutton' accesskey='s'></td></tr>
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