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
			if(isset($_POST['credits'])) {
				if(is_numeric($_POST['credits'])) {
				$sql = mysql_query("SELECT id,credits FROM users");
				$count = mysql_num_rows($sql);
				while($row = mysql_fetch_assoc($sql)) {
				mysql_query("UPDATE users SET credits = credits + '".$_POST['credits']."' WHERE id='".$row['id']."' LIMIT 1");
				@SendMUSData('UPRC' . $row['id']);
				}
				mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','Gave credits to every user, total users: ".$count." (Amount ".$_POST['credits'].")','massa.php','".$my_id."','','".$date_full."')") or die(mysql_error());
				$msg = "Katsjing, everybody got there credits! Users processed: ".$count."";
				}else{
				$msg = "Oops, please use only numbers and no characters!";
				}
				
				
			}elseif(isset($_POST['code']) || isset($_POST['type'])) {
			$sql = mysql_query("SELECT id FROM users");
			$count = mysql_num_rows($sql);
			while($row = mysql_fetch_assoc($sql)) {
			mysql_query("UPDATE users_badges SET iscurrent = '0' WHERE userid='".$row['userid']."' AND iscurrent = '1'") or die(mysql_error());
			mysql_query("INSERT INTO users_badges (userid,badgeid,iscurrent) VALUES('".$row['id']."','".$_POST['code']."','1')") or die(mysql_error());
			@SendMUSData('UPRS' . $row['id']);
			}
			
			if($_POST['type'] == 2) {
			$sql = mysql_query("SELECT id FROM users");
			$count = mysql_num_rows($sql);
			while($row = mysql_fetch_assoc($sql)) {
			$time = time() + ($_POST['time'] * 24 * 60 * 60);
			mysql_query("INSERT INTO cms_badges (userid,days,badgeid) VALUES ('".$row['id']."','".date('Y-m-d', $time)."','".$_POST['code']."')");
			}
			mysql_query("INSERT INTO system_stafflog (action,message,note,userid,timestamp) VALUES ('Housekeeping','Gave badge to every user (for ".$_POST['time']." days), total users: ".$count." (Badge id: ".$_POST['code'].").','massa.php','".$my_id."','".$date_full."')") or die(mysql_error());
			}else{
			mysql_query("INSERT INTO system_stafflog (action,message,note,userid,timestamp) VALUES ('Housekeeping','Gave badge to every user (perm.), total users: ".$count." (Badge id: ".$_POST['code'].").','massa.php','".$my_id."','".$date_full."')") or die(mysql_error());
			}
			
			$msg = "Katsjing, everybody got there badge! Users processed: ".$count."";
			}elseif(isset($_POST['user']) || isset($_POST['badgec'])) {
			$sql = mysql_query("SELECT id FROM users WHERE name='".$_POST['user']."'");
			if(mysql_num_rows($sql) == 1) {
			$sql = mysql_query("SELECT id FROM users WHERE name='".$_POST['user']."'");
			$row = mysql_fetch_assoc($sql);
			mysql_query("DELETE FROM users_badges WHERE userid='".$row['id']."' AND badgeid='".$_POST['badgec']."'");
			if(mysql_error == 0) {
			$msg = "The badge ".$_POST['badgec']." is succesfully taken off.";
			mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','Badge (".$_POST['badgec'].") taken off','massa.php','".$my_id."','".$row['id']."','".$date_full."')") or die(mysql_error());
			}else{
			$msg = "This user doesn't have this badge!";
			}
			}else{
			$msg = "User doesn't excist!";
			}
		}elseif(isset($_POST['take'])) {
	$sql = mysql_query("SELECT * FROM users_badges WHERE badgeid='".$_POST['take']."'");
	$count = mysql_num_rows($sql);
	while($row = mysql_fetch_assoc($sql)) {
	mysql_query("DELETE FROM users_badges WHERE badgeid='".$_POST['take']."'");
	}
	mysql_query("INSERT INTO system_stafflog (action,message,note,userid,timestamp) VALUES ('Housekeeping','Badge taken off from all users who had the badge ".$_POST['take'].". Users processed: ".$count."','massa.php','".$my_id."','".$date_full."')") or die(mysql_error());
	$msg = "Badges are succesfully taken off! Users processed: ".$count."";
	}
}

$pagename = "Content Management";

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
<?php @include('usermenu.php'); ?>
 <!-- / LEFT CONTEXT SENSITIVE MENU -->
 </div>
 </td>
 <td width='78%' valign='top' id='rightblock'>
 <div><!-- RIGHT CONTENT BLOCK -->
 
<?php if(isset($msg)){ ?><p><strong><?php echo $msg; ?></strong></p><?php } ?>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>

<form action='index.php?p=massa_stuff&do=jumpCategory' method='post' name='Jumper!' id='Jumper!'>
<div class='tableborder'>
<div class='tableheaderalt'>Select Category</div>
<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
    <td class='tablerow2'  width='100%'  valign='middle'  align='center'>
        <select name='category' class='dropdown'>
            <option value='1' <?php if($catId == "1"){ echo "selected='selected'"; } ?>>Massa credits</option>
            <option value='2' <?php if($catId == "2"){ echo "selected='selected'"; } ?>>Massa badge (period/perm.)</option>
            <option value='3' <?php if($catId == "3"){ echo "selected='selected'"; } ?>>Remove badge from a certain user</option>
			<option value='4' <?php if($catId == "4"){ echo "selected='selected'"; } ?>>Remove badge from all users with badge code [...]</option>
        </select>
        &nbsp;
        <input type='submit' value='Go' class='realbutton' accesskey='s'>
    </td>
</tr>
</table>
</div>
</form>

<br />
 
<form action='index.php?p=massa_stuff&do=save' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>Massa stuff</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>

<?php if($catId == 1) { ?>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><strong>Amount</strong><div class='graytext'>The amount of credits everybody gets.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='credits' value="" size='3' maxlength='5' class='textinput'></td>
</tr>
<?php }elseif($catId == 2) { ?>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><strong>Badge code</strong><div class='graytext'>What's the badgecode everybody gets?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='code' value="" size='3' maxlength='3' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><strong>Permanent or for a special time?</strong><div class='graytext'>Does everyone gets the badge permanent or for a special time?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='radio' name='type' value='1'> Permanent <input type='radio' name='type' value='2'> For a special time</td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><strong>How long do they get it?</strong><div class='graytext'>For how long do they get the badge? <b>Note:</b> if you've choosen "permanent" it doesn't matter.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><SELECT style="COLOR: black; FONT-FAMILY: Verdana" name=time> <OPTION value="1" selected>1 day</OPTION><OPTION value="2">2 days</OPTION><OPTION value="3">3 days</OPTION><OPTION value="4">4 days</OPTION><OPTION value="5">5 days</OPTION><OPTION value="6">6 days</OPTION><OPTION value="7">1 week</OPTION><OPTION value="14">2 weeks</OPTION><OPTION value="21">3 weeks</OPTION><OPTION value="30">1 month</OPTION><OPTION value="60">2 months</OPTION><OPTION value="90">3 months</OPTION><OPTION value="120">4 months</OPTION><OPTION value="150">5 months</OPTION><OPTION value="180">6 months</OPTION><OPTION value="210">7 months</OPTION><OPTION value="240">8 months</OPTION><OPTION value="270">9 months</OPTION><OPTION value="300">10 months</OPTION><OPTION value="330">11 months</OPTION><OPTION value="365">1 year</OPTION><OPTION value="730">2 year</OPTION><OPTION value="1095">3 year</OPTION><OPTION value="1460">4 year</OPTION><OPTION value="1825">5 year</OPTION><OPTION value="3650">10 year</OPTION> <OPTION value="5475">15 year</OPTION><OPTION value="7300">20 year</OPTION><OPTION value="9125">25 year</OPTION></SELECT></td>
</tr>
<?php }elseif($catId == 3) { ?>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><strong>User</strong><div class='graytext'>From who do you want to take off a badge?</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='user' value="" size='3' maxlength='100' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><strong>Badge code</strong><div class='graytext'>What badge do you want to take off, fill in the badge code.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='badgec' value="" size='3' maxlength='3' class='textinput'></td>
</tr>
<?php }elseif($catId == 4) { ?>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><strong>Badge code</strong><div class='graytext'>What badge do you want to take off, fill in the badge code.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='take' value="" size='3' maxlength='3' class='textinput'></td>
</tr>
<?php }else{ ?>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><strong>Amount</strong><div class='graytext'>The amount of credits everybody gets.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='credits' value="" size='3' maxlength='5' class='textinput'></td>
</tr>
<?php } ?>

<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='Submit' class='realbutton' accesskey='s' name='massa'></td></tr>
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