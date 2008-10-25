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

if(!isset($_POST['category']) || !isset($_GET['category'])){ // do not try to save when it's a category jump
    foreach($_POST as $key => $value){
        if(strlen($value) > 0 && strlen($key) > 0){
            mysql_query("UPDATE cms_content SET contentvalue = '".FilterText($value, true)."' WHERE contentkey = '".FilterText($key, true)."' LIMIT 1") or die(mysql_error());
            $msg = "Settings saved successfully.<br />"; // I know, we shouldn't do this in a loop but meh, doesn't really matter that much, does it?
        }
    }
}

// Note this tool is pretty dynamic. If you add a option in the database, it will be open for editing on this page - automaticly.

$pagename = "Content Management";

$catId = HoloText($_POST['category']);
if($catId == ""){ $catId = HoloText($_GET['category']); }

if(empty($catId) || !is_numeric($catId) || $catId < 1 || $catId > 6){
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
 
<?php if(isset($msg)){ ?><p><strong><?php echo $msg; ?></strong></p><?php } ?>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>

<form action='index.php?p=content&do=jumpCategory' method='post' name='Jumper!' id='Jumper!'>
<div class='tableborder'>
<div class='tableheaderalt'>Select Category</div>
<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
    <td class='tablerow2'  width='100%'  valign='middle'  align='center'>
        <select name='category' class='dropdown'>
            <option value='1' <?php if($catId == "1"){ echo "selected='selected'"; } ?>>General Options</option>
            <option value='2' <?php if($catId == "2"){ echo "selected='selected'"; } ?>>Staff Module</option>
            <option value='3' <?php if($catId == "3"){ echo "selected='selected'"; } ?>>Credits Page</option>
            <option value='4' <?php if($catId == "4"){ echo "selected='selected'"; } ?>>Discussion Board Module</option>
            <option value='5' <?php if($catId == "5"){ echo "selected='selected'"; } ?>>Newsletter</option>
        </select>
        &nbsp;
        <input type='submit' value='Go' class='realbutton' accesskey='s'>
    </td>
</tr>
</table>
</div>
</form>

<br />
 
<form action='index.php?p=content&do=save' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>Manage Content Options</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>

<?php

    $get_settings = mysql_query("SELECT * FROM cms_content WHERE category = '".$catId."' ORDER BY contentkey ASC") or die(mysql_error());
    while($row = mysql_fetch_assoc($get_settings)){
	if($row['contentkey'] !== "client-widescreen") {
        echo "<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>".$row['setting_title']."</b><div class='graytext'>".$row['setting_desc']."</div></td>
<td class='tablerow2'  width='60%'  valign='middle'>";
    if($row['fieldtype'] == "1"){
        // Dynamicly calculate the size of the boxes. Please note that due to the word-is-too-long-so-we-break-the-line
        // stuff we can't really determine it here so it may not be '100%' correct (eg. one line too few creating scrollbars.)
        $rows = ceil(strlen(stripslashes($row['contentvalue'])) / 60);
        // If it is too long, by the means of more than 10 rows, we stick with 10 (avoiding boxes that are way to large).
        if($rows > 10){ $rows = 10; }
        // Default amount of cols is 60, but we'll adjust it if it's only one line
        if($rows < 2){ $cols = strlen(stripslashes($row['contentvalue'])); } else { $cols = "60"; }
        if($rows < 2 && $cols > 3){ $cols = $cols + 10; } else { $cols = $cols + 1; } // give a little extra space
        echo "<textarea name='".$row['contentkey']."' cols='" . $cols . "' rows='" . $rows . "' wrap='soft' id='sub_desc'   class='multitext'>".stripslashes($row['contentvalue'])."</textarea>";
    } elseif($row['fieldtype'] == "2"){
        echo "<select name='".$row['contentkey']."' class='dropdown'><option value='1'>Enabled</option><option value='0'"; if($row['contentvalue'] == "0"){ echo " selected='selected'"; } echo ">Disabled</option></select>";
    } elseif($row['fieldtype'] == "3"){
        echo "<select name='".$row['contentkey']."' class='dropdown'><option value='red'"; if($row['contentvalue'] == "red"){ echo " selected='selected'"; } echo ">Red</option><option value='blue'"; if($row['contentvalue'] == "blue"){ echo " selected='selected'"; } echo ">Blue</option><option value='white'"; if($row['contentvalue'] == "white"){ echo " selected='selected'"; } echo ">White (no Header)</option><option value='green'"; if($row['contentvalue'] == "green"){ echo " selected='selected'"; } echo ">Green</option></select>";        
    }
    echo "</td>
</tr>";
    }
   }
?>

<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='Save Settings' class='realbutton' accesskey='s'></td></tr>
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