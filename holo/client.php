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
include('includes/session.php');

$screen = $myrow['screen'];
$language = $myrow['language'];
$rea = $myrow['rea'];

if($rea == "disabled"){
	include('./includes/sso.php');
}

$ssocheck = mysql_query("SELECT * FROM users WHERE name = '".$rawname."' and password = '".$rawpass."' LIMIT 1") or die(mysql_error());
$ssocheck2 = mysql_fetch_assoc($ssocheck);
if($ssocheck2['ticket_sso'] == "") {
	if(isset($_GET['roomId']) && $_GET['forwardId'] == "2"){
		if(isset($_GET['wide'])) {
			header("location:reauthenticate.php?forwardId=".$_GET['forwardId']."&roomId=".$_GET['roomId']."&wide=".$_GET['wide']);
		} else {
			header("location:reauthenticate.php?forwardId=".$_GET['forwardId']."&roomId=".$_GET['roomId']);
		}
	} elseif(isset($_GET['wide'])) {
			header("location:reauthenticate.php?wide=".$_GET['wide']);
	} else {
			header("location:reauthenticate.php");
	}
}

if(getContent('client-widescreen') == "1"){
        $wide_enabled = true;
} else {
        $wide_enabled = false;
}

if($_GET['wide'] == "false"){
        $wide_enabled = false;
} else {
        $wide_enabled = true;
}

if($screen == "full"){
	$wide_enabled = false;
} else {
	$wide_enabled = true;
}

if($wide_enabled == false){
	$width = "720";
	$height = "540";
	$widemode = "false";
} else {
	$width = "960";
	$height = "540";
	$widemode = "true";
}

if($logged_in){
	require_once('includes/session.php');
} else {
	header("Location: clientutils.php?key=LogInPlease");
	exit;
}

include('./templates/client/subheader.php');
include('./templates/client/header.php');

if($online !== "online" && $enable_status_image == "1"){
echo "<font color='white'><center><b>".$sitename." is offline</b></center></font>";
exit();
}

if($remote_ip == "127.0.0.1" || $remote_ip == "localhost" && $server_on_localhost == 1){
$ip = "127.0.0.1";
}

if(isset($_GET['roomId']) && $_GET['forwardId'] == "2"){
$roomId = $_GET['roomId'];
$checkSQL = mysql_query("SELECT id FROM rooms WHERE id = '".$roomId."' LIMIT 1");
$roomExists = mysql_num_rows($checkSQL);
	if($roomExists > 0){
	$forward = "1";
	echo "<!-- Forwarding to room ".$roomId." -->";
	} else {
	$forward = "0";
	echo "<!-- Room doesn't exist; not forwarding -->";
	}
} else {
echo "<!-- No room forward requested, normal loader -->";
$forward = "0";
}

?>
<div id="clientembed-container">
<?php
$sql = mysql_query("SELECT loader FROM cms_system");
$row = mysql_fetch_assoc($sql);
if($row['loader'] == 1) { ?>
<div id="clientembed-loader" style="display:none"><div><b>opening <?php echo $shortname; ?>...</b></div></div><?php } ?>
<div id="clientembed">
<script type="text/javascript" language="javascript">
try {
var _shockwaveDetectionSuccessful = true;
_shockwaveDetectionSuccessful = ShockwaveInstallation.swDetectionCheck();
if (!_shockwaveDetectionSuccessful) {
    log(50);
}
if (_shockwaveDetectionSuccessful) {
  HabboClientUtils.cacheCheck();
}
} catch(e) {
    try {
		HabboClientUtils.logClientJavascriptError(e);
	} catch(e2) {}
}

	HabboClientUtils.extWrite("<object classid=\"clsid:166B1BCA-3F9C-11CF-8075-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/director/sw.cab#version=10,0,0,0\" id=\"<?php echo $shortname; ?>\" width=\"<?php echo $width; ?>\" height=\"<?php echo $height; ?>\"\>\n<param name=\"src\" value=\"<?php echo $dcr; ?>\"\>\n<param name=\"swRemote\" value=\"swSaveEnabled=\'true\' swVolume=\'true\' swRestart=\'false\' swPausePlay=\'false\' swFastForward=\'false\' swTitle=\'<?php echo $sitename; ?>\' swContextMenu=\'true\' \"\>\n<param name=\"swStretchStyle\" value=\"stage\"\>\n<param name=\"swText\" value=\"\"\>\n<param name=\"bgColor\" value=\"#000000\"\>\n   <param name=\"sw6\" value=\"client.connection.failed.url=<?php echo $path; ?>clientutils.php?key=connection_failed;external.variables.txt=<?php echo $variables; ?>\"\>\n   <param name=\"sw8\" value=\"use.sso.ticket=1;sso.ticket=<?php echo $myticket; ?>\"\>\n   <param name=\"sw2\" value=\"connection.info.host=<?php echo $ip; ?>;connection.info.port=<?php echo $port; ?>\"\>\n   <param name=\"sw4\" value=\"site.url=<?php echo $path; ?>;url.prefix=<?php echo $path; ?>\"\>\n   <param name=\"sw3\" value=\"connection.mus.host=<?php echo $ip; ?>;connection.mus.port=<?php echo $fip; ?>\"\>\n   <param name=\"sw1\" value=\"client.allow.cross.domain=1;client.notify.cross.domain=0\"\>\n   <param name=\"sw7\" value=\"external.texts.txt=<?php echo $texts; ?>;user_isp=<?php echo $remote_ip; ?>\"\>\n   <?php if($forward == "1") { ?><param name=\"sw9\" value=\"forward.type=<?php echo $_GET['forwardId']; ?>;forward.id=<?php echo $roomId; ?>;processlog.url=\"\>\n<?php } ?>    <param name=\"sw5\" value=\"client.reload.url=<?php echo $path; ?>client.php?x=reauthenticate;client.fatal.error.url=<?php echo $path; ?>clientutils.php?key=error\"\>\n<embed src=\"<?php echo $dcr; ?>\" bgColor=\"#000000\" width=\"<?php echo $width; ?>\" height=\"<?php echo $height; ?>\" swRemote=\"swSaveEnabled=\'true\' swVolume=\'true\' swRestart=\'false\' swPausePlay=\'false\' swFastForward=\'false\' swTitle=\'Habbo Hotel\' swContextMenu=\'true\'\" swStretchStyle=\"stage\" swText=\"\" type=\"application/x-director\" pluginspage=\"http://www.macromedia.com/shockwave/download/\" \n    sw6=\"client.connection.failed.url=<?php echo $path; ?>clientutils.php?key=connection_failed;external.variables.txt=<?php echo $variables; ?>\"  \n    sw8=\"use.sso.ticket=1;sso.ticket=<?php echo $myticket; ?>\"  \n    sw2=\"connection.info.host=<?php echo $ip; ?>;connection.info.port=<?php echo $port; ?>\"  \n    sw4=\"site.url=<?php echo $path; ?>;url.prefix=<?php echo $path; ?>\"  \n    sw3=\"connection.mus.host=<?php echo $ip; ?>;connection.mus.port=<?php echo $fip; ?>\"  \n    sw1=\"client.allow.cross.domain=1;client.notify.cross.domain=0\"  \n    sw7=\"external.texts.txt=<?php echo $texts; ?>;user_isp=<?php echo $remote_ip; ?>\"  \n    <?php if($forward == 1) { ?>sw9=\"forward.type=<?php echo $_GET['forwardId']; ?>;forward.id=<?php echo $roomId; ?>;processlog.url=\"<?php } ?>\n    sw5=\"client.reload.url=<?php echo $path; ?>client.php?x=reauthenticate;client.fatal.error.url=<?php echo $path; ?>clientutils.php?key=error\" \></embed\>\n<noembed\>client.pluginerror.message</noembed\>\n</object\>");
</script>
<noscript>
<object classid="clsid:166B1BCA-3F9C-11CF-8075-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/director/sw.cab#version=10,0,0,0" id="<?php echo $shortname; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>">
<param name="src" value="<?php echo $dcr; ?>">
<param name="swRemote" value="swSaveEnabled='true' swVolume='true' swRestart='false' swPausePlay='false' swFastForward='false' swTitle='<?php echo $sitename; ?>' swContextMenu='true' ">
<param name="swStretchStyle" value="stage">
<param name="swText" value="">
<param name="bgColor" value="#000000">
   <?php if($forward == "1"){ ?>
   <param name="sw9" value="forward.type=<?php echo $_GET['forwardId']; ?>;forward.id=<?php echo $roomId; ?>;processlog.url=">
   <?php } ?>
   <param name="sw6" value="client.connection.failed.url=<?php echo $path; ?>clientutils.php?key=connection_failed;external.variables.txt=<?php echo $variables; ?>">
   <param name="sw8" value="use.sso.ticket=1;sso.ticket=<?php echo $myticket; ?>">
   <param name="sw2" value="connection.info.host=<?php echo $ip; ?>;connection.info.port=<?php echo $port; ?>">
   <param name="sw4" value="site.url=<?php echo $path; ?>;url.prefix=<?php echo $path; ?>">
   <param name="sw3" value="connection.mus.host=<?php echo $ip; ?>;connection.mus.port=<?php echo $fport; ?>">
   <param name="sw1" value="client.allow.cross.domain=1;client.notify.cross.domain=0">
   <param name="sw7" value="external.texts.txt=<?php echo $texts; ?>;user_isp=<?php echo $remote_ip; ?>">
   <param name="sw5" value="client.reload.url=<?php echo $path; ?>client.php?x=reauthenticate;client.fatal.error.url=<?php echo $path; ?>clientutils.php?key=error">
<!--[if IE]>client.pluginerror.message<![endif]-->
<embed src="<?php echo $dcr; ?>" bgColor="#000000" width="<?php echo $width; ?>" height="<?php echo $height; ?>" swRemote="swSaveEnabled='true' swVolume='true' swRestart='false' swPausePlay='false' swFastForward='false' swTitle='<?php echo $sitename; ?>' swContextMenu='true'" swStretchStyle="stage" swText="" type="application/x-director" pluginspage="http://www.macromedia.com/shockwave/download/"
    sw6="client.connection.failed.url=<?php echo $path; ?>clientutils.php?key=connection_failed;external.variables.txt=<?php echo $variables; ?>"
    sw8="use.sso.ticket=1;sso.ticket=<?php echo $myticket; ?>"
    sw2="connection.info.host=<?php echo $ip; ?>;connection.info.port=<?php echo $port; ?>"
    sw4="site.url=<?php echo $path; ?>;url.prefix=<?php echo $path; ?>"
    sw3="connection.mus.host=<?php echo $ip; ?>;connection.mus.port=<?php echo $fip; ?>"
    sw1="client.allow.cross.domain=1;client.notify.cross.domain=0"
    sw7="external.texts.txt=<?php echo $texts; ?>;user_isp=<?php echo $remote_ip; ?>"
	<?php if($forward == 1) { ?>
	sw9="forward.type=<?php echo $_GET['forwardId']; ?>;forward.id=<?php echo $roomId; ?>;processlog.url="
	<?php } ?>
    sw5="client.reload.url=<?php echo $path; ?>client.php?x=reauthenticate;client.fatal.error.url=<?php echo $path; ?>clientutils.php?key=error" ></embed>
<noembed>client.pluginerror.message</noembed>
</object>
</noscript>

</div>
<?php if($row['loader'] == 1) { ?>
<script type="text/javascript">
HabboClientUtils.showLoader(["opening <?php echo $shortname; ?>&nbsp;&nbsp;&nbsp;", "opening <?php echo $shortname; ?>.&nbsp;&nbsp;", "opening <?php echo $shortname; ?>..&nbsp;", "opening <?php echo $shortname; ?>..."]);
</script><?php } ?>
</div>
<?php echo $analytics; ?>
</body>
</html>