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

include('core.php');

session_start();

if(isset($_GET) && $_GET['do'] == "version"){
echo "<h1>HoloCMS Version</h1><hr />".$sitename." is running HoloCMS v".$holocms['version']."<hr /><p><strong>HoloCMS Version:</strong> ".$holocms['version']." ".$holocms['stable']." [".$holocms['title']."]<br />\n<strong>HoloCMS Build Date:</strong> ".$holocms['date']."<br />\n<strong>Holograph Emulator Compatability:</strong> Build for <a href='http://trac2.assembla.com/holograph/changeset/".$holograph['revision']."' target='_blank'>Revision ".$holograph['revision']."</a> (".$holograph['type'].")</p>\n<hr /><i>".$remote_ip." @ ".$path."</i>";
exit;
}

if(!session_is_registered(username)){

	if(isset($_POST['username']) && isset($_POST['password'])){
		$username = FilterText($_POST['username']);
		// $password = HoloHash($_POST['password']);
		$password = HoloHash($_POST['password'], $username);
		$remember_me = $_POST['_login_remember_me'];

		if(empty($username) || empty($password)){
			$login_error = "Please do not leave any fields blank.";
		} else {
			$sql = mysql_query("SELECT id FROM users WHERE name = '".$username."' AND password = '".$password."' LIMIT 1") or die(mysql_error());
			$rows = mysql_num_rows($sql);
			if($rows < 1){
				$login_error = "Invalid username or password.";
			} else {
				$userdata = mysql_fetch_assoc($sql);
				$userid = $userdata['id'];
				$check = mysql_query("SELECT * FROM users_bans WHERE userid = '".$userid."' OR ipaddress = '".$remote_ip."' LIMIT 1") or die(mysql_error());
				$is_banned = mysql_num_rows($check);
				if($is_banned < 1){
					$_SESSION['username'] = $username;
					$_SESSION['password'] = $password;
					if($remember_me == "true"){
						setcookie("remember", "remember", time()+60*60*24*100, "/");
						setcookie("rusername", $_SESSION['username'], time()+60*60*24*100, "/");
						setcookie("rpassword", $_SESSION['password'], time()+60*60*24*100, "/");
					}
					$sql3 = mysql_query("UPDATE users SET lastvisit = '".$date_full."' WHERE name = '".$username."'") or die(mysql_error());
					header("location:security_check.php"); exit;
				} else {
					$bandata = mysql_fetch_assoc($check);
					$reason = $bandata['descr'];
					$expire = $bandata['date_expire'];

					$xbits = explode(" ", $expire);
					$xtime = explode(":", $xbits[1]);
					$xdate = explode("-", $xbits[0]);

					$stamp_now = mktime(date('H'),date('i'),date('s'),$today,$month,$year);
					$stamp_expire = mktime($xtime[0], $xtime[1], $xtime[2], $xdate[0], $xdate[1], $xdate[2]);

					if($stamp_now < $stamp_expire){
						$login_error = "You have been banned! The reason for this ban is \"".$reason."\". The ban will expire at ".$expire.".";
					} else { // ban expired
						mysql_query("DELETE FROM users_bans WHERE userid = '".$userid."' OR ipaddress = '".$remote_ip."' LIMIT 1") or die(mysql_error());
						$_SESSION['username'] = $username;
						$_SESSION['password'] = $password;
						if($remember_me == "true"){
							setcookie("remember", "remember", time()+60*60*24*100, "/");
							setcookie("rusername", $_SESSION['username'], time()+60*60*24*100, "/");
						setcookie("rpassword", HoloHash($_SESSION['password'], $_SESSION['username']), time()+60*60*24*100, "/");
						}
						$sql3 = mysql_query("UPDATE users SET lastvisit = '".$date_full."' WHERE name = '".$username."'") or die(mysql_error());
						header("location:security_check.php"); exit;
					}
				}
			}
		}
	}

	if(isset($_GET['error'])){
		$errorno = $_GET['error'];
		if($errorno == 1){
			$login_error = "Invalid username or password.";
		} elseif($errorno == 2){
			$login_error = "Invalid username or password.";
		} elseif(isset($_GET['ageLimit']) && $_GET['ageLimit'] == "true"){
			$login_error = "You are too young to register.";
		}
	}

	include("locale/".$language."/login.php");

	include('templates/login/subheader.php');
	include('templates/login/header.php');
	include('templates/login/column1.php');

?>

<div id="column2" class="column">
				<div class="habblet-container ">

<?php
	if(isset($login_error)){
		echo "\n<div class=\"action-error flash-message\">\n <div class=\"rounded\">\n  <ul>\n   <li>".$login_error."</li>\n  </ul>\n </div>\n</div>\n";
	}
?>
						<div class="cbb loginbox clearfix">
    <h2 class="title">Sign in</h2>

    <div class="box-content clearfix" id="login-habblet">
        <form action="index.php?do=process_login" method="post" class="login-habblet">

            <ul>
                <li>
                    <label for="login-username" class="login-text">Username</label>
                    <input tabindex="1" type="text" class="login-field" name="username" id="login-username" value="<?php echo $_POST['username']; ?>" />
                </li>
                <li>
                    <label for="login-password" class="login-text">Password</label>
                    <input tabindex="2" type="password" class="login-field" name="password" id="login-password" />
	                    <input type="submit" value="Sign in" class="submit" id="login-submit-button"/>
	                    <a href="#" id="login-submit-new-button" class="new-button" style="float: left; margin-left: 0;display:none"><b style="padding-left: 10px; padding-right: 7px; width: 55px">Sign in</b><i></i></a>
                </li>
                <li class="no-label">
                    <input tabindex="3" type="checkbox" name="_login_remember_me" id="login-remember-me" value="true"/>
                    <label for="login-remember-me">Remember me</label>
                </li>
                <li class="no-label">
                    <a href="register.php" class="login-register-link"><span>Register for free</span></a>
                </li>
                <li class="no-label">
                    <a href="forgot.php" id="forgot-password"><span>I forgot my password/username</span></a>
                </li>
            </ul>
        </form>

    </div>
</div>
<div id="remember-me-notification" class="bottom-bubble" style="display:none;">
	<div class="bottom-bubble-t"><div></div></div>
	<div class="bottom-bubble-c">
	By selecting 'remember me' you will stay signed in on this computer until you click 'Sign Out'. If this is a public computer please do not use this feature.
	</div>
	<div class="bottom-bubble-b"><div></div></div>
</div>
<script type="text/javascript">
	HabboView.add(LoginFormUI.init);
	HabboView.add(RememberMeUI.init);
</script>



								</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

				<div class="habblet-container ">

						<div class="ad-container">
<div id="geoip-ad" style="display:none"></div>
</div>



				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

				<div class="habblet-container ">





				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

				<div class="habblet-container ">

						<div class="ad-container">
<a href="register.php"><img src="./web-gallery/v2/images/landing/uk_party_frontpage_image.gif" alt="" /></a>
</div>



				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

</div>

<?php

include('templates/login/footer.php');

} else {
header("location:me.php");
}

?>
