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

$pagename = "Remote Banning";

// The huge/large piece of code below is complex calculation of dates. eg. if you ban someone for 2 hours on the 31st
// of December around 22:34, there's a lot of stuff that you have to process. This thing was a real bitch to code.
// - Is it the end of the day? If so, increase the day number.
// - Is it the end of the month? If so, increase the month number and re-calculate the new day.
// --> Does this month we're in have 30 or 31 days?
// - Is it the end of a year? If so, increase the year number.
// Finally, we have to form it into a proper date again.

if(isset($_POST['uid'])){
    if(!is_numeric($_POST['uid'])){
        $msg = "Invalid User ID";
    } else {
        $check = mysql_query("SELECT name FROM users WHERE id = '".$_POST['uid']."' LIMIT 1") or die(mysql_error());
        $valid = mysql_num_rows($check);
        if($valid < 1){
            $msg = "Sorry, but this user does not exist!";
        } else {
            $uid = $_POST['uid'];
            $length = $_POST['length'];
            $ip = addslashes($_POST['ip']);
            $reason = addslashes($_POST['reason']);
            $date = $date_full;
            $date = explode(" ", $date);
            $time = explode(":", $date[1]);
            $date = explode("-", $date[0]);
            if($length == "2h"){
                $time[0] = $time[0] + 2;
            } elseif($length == "4h"){
                $time[0] = $time[0] + 4;
            } elseif($length == "12h"){
                $time[0] = $time[0] + 12;
            } elseif($length == "1d"){
                $date[0] = $date[0] + 1;
            } elseif($length == "2d"){
                $date[0] = $date[0] + 2;
            } elseif($length == "7d"){
                $date[0] = $date[0] + 7;
            } elseif($length == "2w"){
                 $date[0] = $date[0] + 14;
            } elseif($length == "1m"){
                $date[1] = $date[1] + 1;
            } elseif($length == "6m"){
                $date[1] = $date[1] + 6;
            } elseif($length == "1y"){
                $date[2] = $date[2] + 1;
            } elseif($length == "perm"){
                $date[2] = $date[2] + 25;
            } else {
                $msg = "Invalid ban length!";
            }
                         
            if($time[0] > 23){
                $diff = $time[0] - 24;
                $time[0] = $diff + 1;
                $date[0] = $date[0] + 1;
            }
                
            if(IsEven($month)){
                if($date[0] > 30){
                    $diff = $date[0] - 30;
                    $date[0] = $diff;
                    $date[1] = $date[1] + 1;
                    }
            } else {
                if($date[0] > 31){
                    $diff = $date[0] - 31;
                    $date[0] = $diff;
                       $date[1] = $date[1] + 1;
                }
            }
                
            if($date[1] > 11){
                $diff = 12 - $date[1];
                $date[1] = $diff;
                $date[2] + 1;
            }
            
            // if under 10 and no zero in front, append one
            if($date[0] < 10 && strrpos($date[0], "0") === false){ $date[0] = "0" . $date[0]; }
            if($date[1] < 10 && strrpos($date[1], "0") === false){ $date[1] = "0" . $date[1]; }
            if($time[0] < 10 && strrpos($time[0], "0") === false){ $time[0] = "0" . $time[0]; }
                
            $ban_date = $date[0] . "-" . $date[1] . "-" . $date[2] . " " . $time[0] . ":" . $time[1] . ":" . $time[2];
            $tmp = mysql_fetch_assoc($check);
            
            if($uid !== $sysadmin || $my_id == $sysadmin){
                mysql_query("INSERT INTO users_bans (userid,ipaddress,date_expire,descr) VALUES ('".$uid."','".$ip."','".$ban_date."','".$reason."')") or die(mysql_error());
                mysql_query("UPDATE users SET ticket_sso = '' WHERE id = '".$uid."' LIMIT 1") or die(mysql_error()); // Let's null his SSO ticket just to make sure
                mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','Remotely banned (".$reason.")','bantool.php','".$my_id."','".$uid."','".$date_full."')") or die(mysql_error());
            
                $msg = "User " . $tmp['name'] . " has been banned untill " . $ban_date . " successfully. An ingame notification has been sent (that is, if the user is online).";
                @SendMUSData('HKSB' . $uid . chr(2) . $reason);
            } else {
                $msg = "You may not ban the System Administrator!";
                mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','Access Denied; tried to ban System Administrator','bantool.php','".$my_id."','".$uid."','".$date_full."')") or die(mysql_error());
            }
        }
    }
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
 
<form action='index.php?p=ban&do=banthebastard' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>(Remote) Banning</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>User ID</b><div class='graytext'>The ID of the user you want to ban. To retrive a User's ID, use <a href='index.php?p=uid'>the User ID Tool</a>.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='uid' value="<?php echo $_POST['uid']; ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Ban Reason</b><div class='graytext'>The reason for this ban that will be shown to the user.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='reason' value="<?php echo $_POST['reason']; ?>" size='50' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>IP Address</b> (Optional)<div class='graytext'>If you want to ban a users IP also, fill in this field. To retrive a User's IP Address, use <a href='index.php?p=ip'>the IP Address Tool</a>.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='ip' value="<?php echo $_POST['ip']; ?>" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Ban Length</b></td>
<td class='tablerow2'  width='60%'  valign='middle'><select name='length'  class='dropdown'>
                                                                        <?php if(isset($_POST['length'])){ echo "<option value='".$_POST['length']."'>Do not change (".$_POST['length'].")</option>"; } ?>
									<option value='2h'>2 Hours</option>
									<option value='4h'>4 Hours</option>
                                                                        <option value='12h'>12 Hours</option>
                                                                        <option value='1d'>24 Hours</option>
                                                                        <option value='2d'>2 Days</option>
                                                                        <option value='7d'>7 Days</option>
                                                                        <option value='2w'>2 Weeks</option>
                                                                        <option value='1m'>1 Month</option>
                                                                        <option value='6m'>6 Months</option>
                                                                        <option value='1y'>1 Year</option>
                                                                        <option value='perm'>Permenantly (25 Years)</option>
								    </select>

</td>
</tr>

<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='Ban' class='realbutton' accesskey='s'></td></tr>
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