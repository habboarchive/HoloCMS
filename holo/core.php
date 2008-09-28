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

/** HOLOCMS MAINCORE
* @author	Meth0d
* @desc		Main HoloCMS Processor
* @usage	N/A
*/

define("IN_HOLOCMS", TRUE);
session_start();

// #########################################################################
// Start the initalization process

@include('./config.php');
@include('../config.php');

// Launch the installer if needed
if(empty($sqlpassword) || empty($sqlusername) || empty($sqldb) || empty($sqlhostname)){

	header("location:install.php");
	exit;

} else {

	if(file_exists('install.php') || file_exists('upgrade.php')){

		echo "<h1>Security Alert</h1><hr>It appears you have already executed the installation script or written your configuration file. To start using your site, for security reasons, please delete install.php and/or upgrade.php from the HoloCMS directory to proceed. If you have not yet completed installation or wish to execute it again, please <a href='install.php'>click here</a>.<hr><i>HoloCMS</i>";
		exit;
	}elseif(file_exists('check.php')){
		header("location:check.php");
	} else {

		include('includes/mysql.php');

	}

}

// Validate the langauge
$language_path = "./".$language."index.php";
$language_path_2 = "../".$language."index.php";

if(file_exists($language_path) || file_exists($language_path_2)){
	$valid_language = true;
} else {
	$language = "en";
	$valid_language = false;
}

// #########################################################################
// Define the variables HoloCMS wants to use later on

$remote_ip = $_SERVER[REMOTE_ADDR];
$configsql = mysql_query("SELECT * FROM cms_system LIMIT 1") or die(mysql_error());
$config = mysql_fetch_assoc($configsql);
$enable_sso = $config['enable_sso'];
$language = $config['language'];
$sitename = $config['sitename'];
$shortname = $config['shortname'];
$ip = $config['ip'];
$dcr = $config['dcr'];
$port = FetchServerSetting('server_game_port');
$fport = FetchServerSetting('server_mus_port');
$texts = $config['texts'];
$variables = $config['variables'];
$reload_url = $config['reload_url'];
$maintenance = $config['site_closed'];
$H = date('H');
$i = date('i');
$s = date('s');
$m = date('m');
$d = date('d');
$Y = date('Y');
$j = date('j');
$n = date('n');
$today = $d;
$month = $m;
$year = $Y;
$date_normal = date('d-m-Y',mktime($m,$d,$Y));
$date_reversed = date('Y-m-d', mktime($m,$d,$y));
$date_full = date('d-m-Y H:i:s',mktime($H,$i,$s,$m,$d,$Y));
$date_time = date('H:i:s',mktime($H,$i,$s));
$date_hc = "".$j."-".$n."-".$Y."";
$regdate = $date_normal;
$s1ql = mysql_query("SELECT * FROM system LIMIT 1");
$r1ow = mysql_fetch_assoc($s1ql);
$online_count = $r1ow['onlinecount'];
$server_on_localhost = $config['localhost'];
$habboversion = "23_deebb3529e0d9d4e847a31e5f6fb4c5b/9";
$forumid = $_GET['id'];
$analytics = stripslashes($config['analytics'])."\n";

// #########################################################################

function FetchServerSetting($strSetting, $switch = false){

	$tmp = mysql_query("SELECT sval FROM system_config WHERE skey = '".$strSetting."' LIMIT 1") or die(mysql_error());
	$tmp = mysql_fetch_assoc($tmp);

	if($switch !== true){
		return $tmp['sval'];
	} elseif($switch == true && $tmp['sval'] == "1"){
		return "Enabled";
	} elseif($switch == true && $tmp['sval'] !== "1"){
		return "Disabled";
	}

}

// #########################################################################

function getContent($strKey){

	$tmp = mysql_query("SELECT contentvalue FROM cms_content WHERE contentkey = '".addslashes($strKey)."' LIMIT 1") or die(mysql_error());
	$tmp = mysql_fetch_assoc($tmp);
	return $tmp['contentvalue'];

}

// #########################################################################

function FetchCMSSetting($strSetting){

	$tmp = mysql_query("SELECT ".$strSetting." FROM cms_system LIMIT 1") or die(mysql_error());
	$tmp = mysql_fetch_assoc($tmp);
	return $tmp[$strSetting];

}

// #########################################################################
// If a user is logged out and has a 'remember me' cookie, validate the information
// in the cookie and log the user in if everything's valid.
// Please do not mess with this. It is a fairly simple process, but if it doesn't work
// properly it can cause a huge mess. Everything in this function is commented.

@include('./includes/inc.crypt.php');
@include('../includes/inc.crypt.php');
if(!session_is_registered(username) && $_COOKIE['remember'] == "remember"){

	// Get variables stored in cookies; the username and sha1 hashed password
	$cname = addslashes($_COOKIE['rusername']);
	$cpass_hash = $_COOKIE['rpassword'];

	// Now fetch the password that belongs to this user from the database
	$csql = mysql_query("SELECT password FROM users WHERE name = '".$cname."' LIMIT 1") or die(mysql_error());
	$cnum = mysql_num_rows($csql);

		// If no results are returned (invalid username, destroy the cookie
		if($cnum < 1){
			setcookie("remember", "", time()-60*60*24*100, "/"); setcookie("cookpass", "", time()-60*60*24*100, "/");
			setcookie("rusername", "", time()-60*60*24*100, "/"); setcookie("cookpass", "", time()-60*60*24*100, "/");
			setcookie("rpassword", "", time()-60*60*24*100, "/"); setcookie("cookpass", "", time()-60*60*24*100, "/");
		} else {

			// We found a user, now get his password and hash it
			$crow = mysql_fetch_assoc($csql);
			$correct_pass = $crow['password'];

			// Check if the hashed database password and hash in the cookie match
			// If no, destroy the cookie. If yes, log the user in.
			if($cpass_hash == $correct_pass){
				$_SESSION['username'] = $cname;
				$_SESSION['password'] = $crow['password'];
				mysql_query("UPDATE users SET lastvisit = '".$date_full."' WHERE name = '" . $cname . "'") or die(mysql_error());
				header("Location: security_check.php");
				exit;
			} else {
				setcookie("remember", "", time()-60*60*24*100, "/"); setcookie("cookpass", "", time()-60*60*24*100, "/");
				setcookie("rusername", "", time()-60*60*24*100, "/"); setcookie("cookpass", "", time()-60*60*24*100, "/");
				setcookie("rpassword", "", time()-60*60*24*100, "/"); setcookie("cookpass", "", time()-60*60*24*100, "/");
			}

		}

}

// #########################################################################

function IsEven($intNumber)
{
	if($intNumber % 2 == 0){
		return true;
	} else {
		return false;
	}
}

// #########################################################################

function bbcode_format($str){

	// Parse smilies
	if(getContent('enable-smilies') == "1"){
		$str = str_replace(":)", " <img src='./web-gallery/smilies/smile.gif' alt='Smiley' title='Smiley' border='0'> ", $str);
		$str = str_replace(";)", " <img src='./web-gallery/smilies/wink.gif' alt='Smiley' title='Smiley' border='0'> ", $str);
		$str = str_replace(":P", " <img src='./web-gallery/smilies/tongue.gif' alt='Smiley' title='Smiley' border='0'> ", $str);
		$str = str_replace(";P", " <img src='./web-gallery/smilies/winktongue.gif' alt='Smiley' title='Smiley' border='0'> ", $str);
		$str = str_replace(":p", " <img src='./web-gallery/smilies/tongue.gif' alt='Smiley' title='Smiley' border='0'> ", $str);
		$str = str_replace(";p", " <img src='./web-gallery/smilies/winktongue.gif' alt='Smiley' title='Smiley' border='0'> ", $str);
		$str = str_replace("(L)", " <img src='./web-gallery/smilies/heart.gif' alt='Smiley' title='Smiley' border='0'> ", $str);
		$str = str_replace("(l)", " <img src='./web-gallery/smilies/heart.gif' alt='Smiley' title='Smiley' border='0'> ", $str);
		$str = str_replace(":o", " <img src='./web-gallery/smilies/shocked.gif' alt='Smiley' title='Smiley' border='0'> ", $str);
		$str = str_replace(":O", " <img src='./web-gallery/smilies/shocked.gif' alt='Smiley' title='Smiley' border='0'> ", $str);
	}

	// Parse BB code
        $simple_search = array(
                                '/\[b\](.*?)\[\/b\]/is',
                                '/\[i\](.*?)\[\/i\]/is',
                                '/\[u\](.*?)\[\/u\]/is',
                                '/\[s\](.*?)\[\/s\]/is',
                                '/\[quote\](.*?)\[\/quote\]/is',
                                '/\[link\=(.*?)\](.*?)\[\/link\]/is',
                                '/\[url\=(.*?)\](.*?)\[\/url\]/is',
                                '/\[color\=(.*?)\](.*?)\[\/color\]/is',
                                '/\[size=small\](.*?)\[\/size\]/is',
                                '/\[size=large\](.*?)\[\/size\]/is',
                                '/\[code\](.*?)\[\/code\]/is',
                                '/\[habbo\=(.*?)\](.*?)\[\/habbo\]/is',
                                '/\[room\=(.*?)\](.*?)\[\/room\]/is',
                                '/\[group\=(.*?)\](.*?)\[\/group\]/is'
                                );

        $simple_replace = array(
                                '<strong>$1</strong>',
                                '<em>$1</em>',
                                '<u>$1</u>',
                                '<s>$1</s>',
                                "<div class='bbcode-quote'>$1</div>",
                                "<a href='$1'>$2</a>",
                                "<a href='$1'>$2</a>",
                                "<font color='$1'>$2</font>",
                                "<font size='1'>$1</font>",
                                "<font size='3'>$1</font>",
                                '<pre>$1</pre>',
                                "<a href='./user_profile.php?id=$1'>$2</a>",
                                "<a onclick=\"roomForward(this, '$1', 'private'); return false;\" target=\"client\" href=\"./client.php?forwardId=2&roomId=$1\">$2</a>",
                                "<a href='./group_profile.php?id=$1'>$2</a>"
                                );

        $str = preg_replace ($simple_search, $simple_replace, $str);

        return $str;
}

// #########################################################################

function GenerateTicket(){

	$data = "ST-";

	for ($i=1; $i<=6; $i++){
		$data = $data . rand(0,9);
	}

	$data = $data . "-";

	for ($i=1; $i<=20; $i++){
		$data = $data . rand(0,9);
	}

	$data = $data . "-holo-fe";
	$data = $data . rand(0,5);

	return $data;
}

// #########################################################################

// Collectable check (showroom). It can be that a collectable isn't in the collectables showroom. We're gonna do that now.
$sql = mysql_query("SELECT * FROM cms_collectables");

while($row = mysql_fetch_assoc($sql)) {
$date = (date('m') - 1);
    if($date >= $row['month']) {
    mysql_query("UPDATE cms_collectables SET showroom='1' WHERE id='".$row['id']."' LIMIT 1");
    }

    if(date('Y') != $row['year']) {
    mysql_query("UPDATE cms_collectables SET showroom='1' WHERE id='".$row['id']."' LIMIT 1");
    }
}

// #########################################################################

if(session_is_registered('username')){

	$rawname = $_SESSION['username']; // Has slashes added and lacking proper capitals
	$rawpass = $_SESSION['password']; // HoloHash()'ed user password

	$usersql = mysql_query("SELECT * FROM users WHERE name = '".$rawname."' AND password = '".$rawpass."' LIMIT 1");
	$myrow = mysql_fetch_assoc($usersql);

	$password_correct = mysql_num_rows($usersql);

	$my_id = $myrow['id'];
	$user_rank = $myrow['rank'];

	$check = mysql_query("SELECT * FROM users_bans WHERE userid = '".$my_id."' OR ipaddress = '".$remote_ip."' LIMIT 1") or die(mysql_error());
	$is_banned = mysql_num_rows($check);

	if($password_correct !== 1){ // Invalid credentials. Possible session hijack attempt, so we log the user out.

		session_destroy();
		header("location:index.php?error=1");
		exit;

	} elseif($is_banned > 0){

		$bandata = mysql_fetch_assoc($check);
		$reason = $bandata['descr'];
		$expire = $bandata['date_expire'];

		$xbits = explode(" ", $expire);
		$xtime = explode(":", $xbits[1]);
		$xdate = explode("-", $xbits[0]);

		$stamp_now = time();
		$stamp_expire = mktime($xtime[0], $xtime[1], $xtime[2], $xdate[0], $xdate[1], $xdate[2]);

		if($stamp_now < $stamp_expire){

			$login_error = "You have been banned! The reason for this ban is \"" . $reason . "\". The ban will expire at " . $expire . ".";
			include('logout.php');
			session_destroy();
			exit;

		} else {

			// ban expired
			mysql_query("DELETE FROM users_bans WHERE userid = '".$my_id."' OR ipaddress = '".$remote_ip."' LIMIT 1") or die(mysql_error());

		}
	}

	if($enable_sso == 1 && $password_correct == 1){

		$myticket = $myrow['ticket_sso'];

		// if(empty($myticket) || $myticket == "0" || strlen($myticket) < 39){
		// 	$myticket = GenerateTicket();
		// 	mysql_query("UPDATE users SET ticket_sso = '".$myticket."', ipaddress_last = '".$remote_ip."' WHERE id = '".$my_id."' LIMIT 1") or die(mysql_error());
		// }

	} else {
		$myticket = "ST-NoTicketToGenerate-holo-fe";
	}

	$logged_in = true;
	$name = stripslashes($myrow['name']);

} else {

	$user_rank = 0;
	$name = "Guest";
	$my_id = "GUEST";
	$myticket = "ST-Guest-holo-fe";
	$logged_in = false;

}

// #########################################################################
// Gift check (noob/welcome stuff)
/*
$sql = mysql_query("SELECT noob,gift,sort,roomid,lastgift FROM users WHERE id='".$my_id."' LIMIT 1");
$row = mysql_fetch_assoc($sql);
if($row['gift'] < 3) {
if($row['noob'] == 1) {
    if($row['lastgift'] < date("d-m-Y")) {
        mysql_query("INSERT INTO cms_noobgifts (userid,gift,read) VALUES ('".$my_id."','".$row['gift']."','0')");
        mysql_query("UPDATE users SET lastgift='".date("d-m-Y")."',gift=gift+'1' WHERE id='".$my_id."' LIMIT 1");
    }
}
}
// #########################################################################
*/
if($enable_status_image == "1"){
	if($server_on_localhost != 0 || $ip == "127.0.0.1"){
		$fip = "127.0.0.1";
	} else {
		$fip = $ip;
	}

	$fp = @fsockopen($fip, $fport, $errno, $errstr, 1);

	if($fp){
		$online = "online";
		fclose($fp);
	} else {
		$online = "offline";
	}
}else{
	$online = "online";
}

// #########################################################################

if($user_rank > 5){
	if(session_is_registered(hkusername) && session_is_registered(hkpassword)){
		$rank['iAdmin'] = "1";
	} else {
		$rank['iAdmin'] = "0";
	}
} else {
	$rank['iAdmin'] = "0";
}

// #########################################################################

function GetUserBadge($strName){ // supports user IDs also

	if(is_numeric($strName)){
		$check = mysql_query("SELECT id FROM users WHERE id = '".$strName."' AND badge_status = '1' LIMIT 1") or die(mysql_error());
	} else {
		$check = mysql_query("SELECT id FROM users WHERE name = '".addslashes($strName)."' AND badge_status = '1' LIMIT 1") or die(mysql_error());
	}

	$exists = mysql_num_rows($check);

		if($exists > 0){
			$usrrow = mysql_fetch_assoc($check);
			$check = mysql_query("SELECT * FROM users_badges WHERE userid = '".$usrrow['id']."' AND iscurrent = '1' LIMIT 1") or die(mysql_error());
			$hasbadge = mysql_num_rows($check);
			if($hasbadge > 0){
				$badgerow = mysql_fetch_assoc($check);
				return $badgerow['badgeid'];
			} else {
				return false;
			}
		} else {
			return false;
		}
}

// #########################################################################

function GetUserGroup($my_id){
$check = mysql_query("SELECT groupid FROM groups_memberships WHERE userid = '".$my_id."' AND is_current = '1' LIMIT 1") or die(mysql_error());
$has_fave = mysql_num_rows($check);

	if($has_fave > 0){

		$row = mysql_fetch_assoc($check);
		$groupid = $row['groupid'];

		return $groupid;

	} else {

		return false;

	}
}

// #########################################################################

function GetUserGroupBadge($my_id){
$check = mysql_query("SELECT groupid FROM groups_memberships WHERE userid = '".$my_id."' AND is_current = '1' LIMIT 1") or die(mysql_error());
$has_badge = mysql_num_rows($check);

	if($has_badge > 0){

		$row = mysql_fetch_assoc($check);
		$groupid = $row['groupid'];

		$check = mysql_query("SELECT badge FROM groups_details WHERE id = '".$groupid."' LIMIT 1") or die(mysql_error());

		$row = mysql_fetch_assoc($check);
		$badge = $row['badge'];

		return $badge;

	} else {

		return false;

	}
}

// #########################################################################

// Calculate the amount of HC Days left
function HCDaysLeft($my_id){

	// Query for the info we need to calculate
	$sql = mysql_query("SELECT months_left,date_monthstarted FROM users_club WHERE userid = '".$my_id."' LIMIT 1") or die(mysql_error());
	$tmp = mysql_fetch_assoc($sql);
	$valid = mysql_num_rows($sql);

	if($valid > 0){

		// Collect the variables we need from the query result
		$months_left = $tmp['months_left'];
		$month_started = $tmp['date_monthstarted'];

		// We take 31 days for every month left, assuming each month has 31 days
		$days_left = $months_left * 31;

		// Split up the day/month/year so we can use it with mktime
		$tmp = explode("-", $month_started);
		$day = $tmp[0];
		$month = $tmp[1];
		$year = $tmp[2];

		// First of all make the dates we want to compare, do some math
		$then = mktime(0, 0, 0, $month, $day, $year, 0);
		$now = time();
		$difference = $now - $then;

		// If this month expired already
		if ($difference < 0){
			$difference = 0;
		}

		// Now do some math
		$days_expired = floor($difference/60/60/24);

		// $days_expired stands for the days we already wasted in this month
		// 31 days for each month added together, minus the days we've wasted in the current month, is the amount of days we have left, totally
		$days_left = $days_left - $days_expired;

		return $days_left;

	} else {
		return 0;
	}
}

// #########################################################################

if($maintenance == "1" && !$is_maintenance && $rank['iAdmin'] < 1){
	header("Location: maintenance.php");
	exit;
} elseif($rank['iAdmin'] == 1 && $maintenance == 1){
	$notify_maintenance = true;
}

// #########################################################################

function IsHCMember($my_id){
    if(HCDaysLeft($my_id) > 0 ){
        return true;
    } else {
        // Make sure that HC members are _not_ rank 2 and that they do not have their gay little badge
        $check = mysql_query("SELECT * FROM users_club WHERE userid = '".$my_id."' LIMIT 1");
        $clubrecord = mysql_num_rows($check);
        if($clubrecord > 0){
            mysql_query("UPDATE users SET badge_status = '0', hc_before='1' WHERE id = '".$my_id."' LIMIT 1") or die(mysql_error());
            mysql_query("UPDATE users SET rank = '1' WHERE id = '".$my_id."' AND rank = '2' LIMIT 1") or die(mysql_error());
            mysql_query("DELETE FROM users_badges WHERE badgecode = 'HC1' OR badgeid = 'HC2' AND userid = '".$my_id."' LIMIT 1");
            mysql_query("DELETE FROM users_club WHERE userid = '".$my_id."' LIMIT 1") or die(mysql_error());
            if(function_exists(SendMUSData) !== true){ include('includes/mus.php'); }
            @SendMUSData('UPRS' . $my_id);
        }
        return false;
    }
}

// #########################################################################

function GiveHC($user_id, $months){

$sql = mysql_query("SELECT * FROM users_club WHERE userid = '".$user_id."' LIMIT 1") or die(mysql_error());
$valid = mysql_num_rows($sql);

    if($valid > 0){
        mysql_query("UPDATE users SET rank = '2' WHERE rank = '1' AND id = '".$user_id."' LIMIT 1") or die(mysql_error());
        mysql_query("UPDATE users_club SET months_left = months_left + ".$months." WHERE userid = '".$user_id."' LIMIT 1") or die(mysql_error());
        $check = mysql_query("SELECT * FROM users_badges WHERE badgeid = 'HC1' AND userid = '".$user_id."' LIMIT 1") or die(mysql_error());
        $found = mysql_num_rows($check);
        if($found !== 1){ // No badge. Poor thing.
            mysql_query("UPDATE users SET badge_status = '0' WHERE id = '".$user_id."' LIMIT 1") or die(mysql_error());
            mysql_query("UPDATE users_badges SET iscurrent = '0' WHERE userid = '".$user_id."'") or die(mysql_error());
            mysql_query("INSERT INTO users_badges (userid,badgeid,iscurrent) VALUES ('".$user_id."','HC1','1')") or die(mysql_error());
        }
    } else {
        $m = date('m');
        $d = date('d');
        $Y = date('Y');
        $date = date('d-m-Y', mktime($m,$d,$Y));
        mysql_query("INSERT INTO users_club (userid,date_monthstarted,months_expired,months_left) VALUES ('".$user_id."','".$date."','0','0')") or die(mysql_error());
        GiveHC($user_id, $months);
    }

    if(function_exists(SendMUSData) !== true){ include('includes/mus.php'); }
    @SendMUSData('UPRS' . $user_id);
    @SendMUSData('UPRC' . $user_id);
}

// #########################################################################

if(session_is_registered(username)){
$blob = time();
mysql_query("UPDATE users SET online = '".$blob."', ipaddress_last = '".$remote_ip."' WHERE id = '".$my_id."' LIMIT 1") or die(mysql_error());
	//if($phail == true){
	//echo "<b>Please wait..</b><br />Please wait while we update your HoloDB compatability..<br />";
	//mysql_query("ALTER TABLE `users` ADD `online` TEXT NOT NULL ;") or die(mysql_error());
	//echo "Done! Please reload this page to proceed. You will not see this message again.";
	//exit;
	//}
}

// #########################################################################

function IsUserOnline($intUID){
$result = mysql_query("SELECT online FROM users WHERE id = '".$intUID."' LIMIT 1") or die(mysql_error());
$timeout = 600; // 10 minutes ?

	if(mysql_num_rows($result) < 1){
		return false;
	} else {
		$result = mysql_fetch_array($result);
		$result = $result[0];
		$result = $result + $timeout;
		if($result >= time()){
			return true;
		} else {
			return false;
		}
	}
}

// #########################################################################

function IsUserBanned($my_id){

	$check = mysql_query("SELECT * FROM users_bans WHERE userid = '".$my_id."' LIMIT 1") or die(mysql_error());
	$is_banned = mysql_num_rows($check);

	if(!is_numeric($my_id)){ return false; }

	if($is_banned > 0){
		$bandata = mysql_fetch_assoc($check);
		$reason = $bandata['descr'];
		$expire = $bandata['date_expire'];

		$xbits = explode(" ", $expire);
		$xtime = explode(":", $xbits[1]);
		$xdate = explode("-", $xbits[0]);

		$stamp_now = time();
		$stamp_expire = mktime($xtime[0], $xtime[1], $xtime[2], $xdate[0], $xdate[1], $xdate[2]);

		if($stamp_now < $stamp_expire){
			return true;
		} else { // ban expired
			mysql_query("DELETE FROM users_bans WHERE userid = '".$my_id."' LIMIT 1") or die(mysql_error());
			return false;
		}
	} else {
		return false;
	}
}

// #########################################################################

function mysql_evaluate($query, $default_value="undefined") {
	$result = mysql_query($query) or die(mysql_error());

	if(mysql_num_rows($result) < 1){
		return $default_value;
	} else {
		return mysql_result($result, 0);
	}
}

// #########################################################################

@include('./includes/version.php');
@include('../includes/version.php');

?>