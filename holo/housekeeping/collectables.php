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

$pagename = "Collectables";

$catId = $_POST['category'];

if(empty($catId) || !is_numeric($catId) || $catId < 1 || $catId > 5){
    $catId = 1;
} else {
    $catId = $catId;
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
 
 <div class='tableborder'>
 <div class='tableheaderalt'><?php echo $sitename; ?> collectables editor</div>
 <table cellpadding='4' cellspacing='0' width='100%'>
 <tr>
  <td class='tablesubheader' width='1%' align='center'>ID</td>
  <td class='tablesubheader' width='8%' align='center'>Month</td>
  <td class='tablesubheader' width='7%' align='center'>Year</td>
  <td class='tablesubheader' width='20%' align='center'>Name</td>
  <td class='tablesubheader' width='31%' align='center'>Description</td>
  <td class='tablesubheader' width='26%' align='center'>Showroom</td>
  <td class='tablesubheader' width='5%' align='center'>Furni</td>
  <td class='tablesubheader' width='2%' align='center'>Edit</td>
 </tr>
<?php
$get_rooms = mysql_query("SELECT * FROM cms_collectables") or die(mysql_error());

while($row = mysql_fetch_assoc($get_rooms)){ ?>
 <tr>
  <td class='tablerow1' align='center'><?php echo $row['id']; ?></td>
  <td class='tablerow2'><?php if($row['month'] == 01 OR $row['month'] == 1) { echo "January"; }elseif($row['month'] == 02 OR $row['month'] == 2) { echo "February"; }elseif($row['month'] == 03 OR $row['month'] == 3) { echo "March"; }elseif($row['month'] == 04 OR $row['month'] == 4) { echo "April"; }elseif($row['month'] == 05 OR $row['month'] == 5) { echo "May"; }elseif($row['month'] == 06 OR $row['month'] == 6) { echo "June"; }elseif($row['month'] == 07 OR $row['month'] == 7) { echo "July"; }elseif($row['month'] == 08 OR $row['month'] == 8) { echo "August"; }elseif($row['month'] == 09 OR $row['month'] == 9) { echo "September"; }elseif($row['month'] == 10) { echo "October"; }elseif($row['month'] == 11) { echo "November"; }elseif($row['month'] == 12) { echo "December"; } ?></div></td>
  <td class='tablerow2'><?php echo $row['year']; ?></div></td>
  <td class='tablerow2'><?php echo HoloText($row['title']); ?></div></td>
  <td class='tablerow2' align='center'><?php echo HoloText($row['description']); ?></td>
  <td class='tablerow2' align='center'><?php if($row['showroom'] != 0) { echo "You can't buy this collectable any more: it's in the showroom."; }else{ echo "Not in the showroom (yet)"; } ?></td>
  <td class='tablerow2' align='center'><img src="<?php echo $row['furni_image_small']; ?>"></td>
  <td class='tablerow2' align='center'><a href='index.php?p=collectables_edit&key=<?php echo $row['id']; ?>&a=edit'><img src='./images/edit.gif' alt='Edit collectables'></a> <a href='index.php?p=collectables_edit&key=<?php echo $row['id']; ?>&a=delete'><img src='./images/delete.gif' alt='Delete collectables'></a></td>
</tr>
<?php } ?>
 
 </table>
</div>
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