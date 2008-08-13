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

$pagename = "Stickers & Backgrounds";

$url = "$_POST[url]";
$price = "$_POST[price]";
$name = "$_POST[name]";
$type = "$_POST[type]";

if(isset($_POST['url'])){
if(!empty($_POST['url']) && !empty($_POST['price']) && !empty($_POST['name']) && !empty($_POST['type'])){

$filename = '../web-gallery/styles/myhabbo/assets.css';
$url = "$_POST[url]";
$price = "$_POST[price]";
$name = "$_POST[name]";
$type = "$_POST[type]";
$stickyname = explode(" ", $name);
$image = getimagesize ($url);
$h = $image[0];
$w = $image[1];
if($type != "27" OR $type != "50") {
$somecontent = "
div.s_".$stickyname[0]." {
    width: ".$h."px; height: ".$w."px;
    background-image: url(".$url.");
}
div.s_".$stickyname[0]."_pre {
    background: url(".$url.") no-repeat 50% 50%;
}";
}else{
$somecontent = "
div.b_".$stickyname[0]." {
    background-image: url(".$url.");
}
div.b_".$stickyname[0]."_pre {
    background-image: url(".$url.");
}";
}

if (is_writable($filename)) {
    if (!$handle = fopen($filename, 'a')) {
         $msg =  "Can't open file ($filename)";
         exit;
    }
    if (fwrite($handle, $somecontent) === FALSE) {
        $msg = "Can't add sticker";
        exit;
    }
    $msg = "Success, your sticker/background has been added";

    fclose($handle);

} else {
    $msg = "The file $filename is not writable";
}


            if($type == "27" OR $type == "50") {
			mysql_query("INSERT INTO cms_homes_catalouge (name,type,subtype,data,price,category) VALUES ('".$name."','4', '0', '".$stickyname[0]."', '".$price."', '".$type."')") or die(mysql_error());
			}else{
            mysql_query("INSERT INTO cms_homes_catalouge (name,type,subtype,data,price,category) VALUES ('".$name."','1', '0', '".$stickyname[0]."', '".$price."', '".$type."')") or die(mysql_error());
            }
			mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','Created Sticker or Background ".addslashes($_POST['$name'])." worth ".$price." credits','add_homes_stuff.php','".$my_id."','','".$date_full."')") or die(mysql_error());

        }
    } else {

        $msg = "Please do not leave any fields blank.";

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
 <?php if(isset($msg)){ ?><p><strong><?php echo $msg; ?></p></strong><?php } ?>
<form action='index.php?p=add_homes&do=create' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>Create Sticker or Background</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><strong>URL</strong><div class='graytext'>
    The URL of the image</div></td>
<td class='tablerow2'  width='60%'  valign='middle'>
<input type='text' name='url' size='43' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><strong>Category</strong><div class='graytext'>
    The category the item will be on</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><select size='1' name='type'>
<option value='19'>General</option>
<option value='50'>Staff</option>
<option value="3">Trax</option>
<option value="5">Plastic</option>
<option value="6">Bling</option>
<option value="7">Borders</option>
<option value="8">Crazy</option>
<option value="9">Cute</option>
<option value="10">Scary</option>
<option value="11">Deals</option>
<option value="12">Aarows &amp; Pointers</option>
<option value="13">Plants</option>
<option value="14">FX</option>
<option value="15">Snowstorm</option>
<option value="16">Battleball</option>
<option value="17">Habbo Club</option>
<option value="18">Personality</option>
<option value="20">Other</option>
<option value="21">Alpha Wood</option>
<option value="27">Backgrounds</option>
</select></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><strong>Name</strong><div class='graytext'>
    The name that will be displayed with the image in the homes and groups 
    catalogue</div></td>
<td class='tablerow2'  width='60%'  valign='middle'>
<input type='text' name='name' value="" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><strong>Price</strong><div class='graytext'>
    The amount of credits the item will cost</div></td>
<td class='tablerow2'  width='60%'  valign='middle'>
<input type='text' name='price' value="" size='30' class='textinput'></td>
</tr>

<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='Create Sticker' class='realbutton' accesskey='s'></td></tr>
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