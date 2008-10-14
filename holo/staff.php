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

$allow_guests = true;

include('core.php');
include('includes/session.php');

$tmp = getContent('mod_staff-enabled');
if($tmp !== "1"){
        header("Location: index.php"); exit;
}

$pagename = "Staff Team";
$pageid = "8";

include('templates/community/subheader.php');
include('templates/community/header.php');

?>

<div id="container">
	<div id="content">
    <div id="column1" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix green ">

							<h2 class="title">Staff
							</h2>
						<div class="habblet box-content">
<?php
$getem = mysql_query("SELECT name,mission,rank,lastvisit,figure,sex,id FROM users WHERE rank > 4 ORDER BY name") or die(mysql_error());
$staff_members = mysql_num_rows($getem);

if($staff_members < 1){
echo "No staff to display yet.";
} else {
	while ($row = mysql_fetch_array($getem, MYSQL_NUM)) {

	if($row[2] == 7 || $row[2] > 7){ // = 7 or higher - Admin
	$row[2] = "Administrator";
	} else if($row[2] > 5){ // = 6 - Moderator
	$row[2] = "Moderator";
	} else if($row[2] > 4){ // = 5 - Gold
	$row[2] = "Gold Abboh";
	} else if($row[2] > 3){ // = 4 - Silver
	$row[2] = "Silver Abboh";
	}

	if(empty($row[1])){
	$row[1] = "No motto";
	}

	$badge = GetUserBadge($row[0]);
	if($badge !== false){
	$badge = "<img src=\"".$cimagesurl.$badgesurl.$badge.".gif\" /></a>";
	} else {
	$badge= "";
	}

	$groupbadge = GetUserGroupBadge($row[6]);
	if($groupbadge !== false){
	$gbadge = "<a href='group_profile.php?id=".GetUserGroup($row[6])."'><img src='./habbo-imaging/badge.php?badge=".$groupbadge."'></a>";
	} else {
	$gbadge = "";
	}

	if(IsUserOnline($row[6])){
		$online_img = "online_anim";
                $online_caption = "Online now!";
	} else {
		$online_img = "offline";
                $online_caption = "Offline";
	}

printf("<p><img src='http://www.habbo.co.uk/habbo-imaging/avatarimage?figure=%s&size=b&direction=2&head_direction=3&gesture=sml&size=s' alt='%s' align='left' />
<b><a href='user_profile.php?name=%s'>%s</a></b>&nbsp;<img src='./web-gallery/v2/images/habbo_%s.gif' title='%s' alt='%s' border='0'><br />
<i>%s</i><br />
<br />
Rank: %s<br />
Last Visit: %s<br />
<br />%s&nbsp;%s<br /><br /></p>
",$row[4],$row[0],$row[0],$row[0],$online_img,$online_caption,$online_caption,HoloText($row[1]),$row[2],$row[3], $badge, $gbadge);
	}
}
?>
</div>


					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

</div>
<div id="column2" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix <?php echo HoloText(getContent('staff1-color')); ?> ">

							<h2 class="title"><?php echo HoloText(getContent('staff1-heading')); ?>
							</h2>
						<div id="notfound-looking-for" class="box-content">
    <p><?php echo HoloText(getContent('staff1')); ?></p>
</div>


					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

				<div class="habblet-container ">
						<div class="cbb clearfix <?php echo HoloText(getContent('staff2-color')); ?> ">

							<h2 class="title"><?php echo HoloText(getContent('staff2-heading')); ?>
							</h2>
						<div id="notfound-looking-for" class="box-content">
    <p><?php echo HoloText(getContent('staff2')); ?></p>
</div>


					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>


</div>

<?php

include('templates/community/footer.php');

?>
