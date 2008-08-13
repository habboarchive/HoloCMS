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

include('../core.php');
include('../includes/session.php');

$groupid = $_POST['groupId'];

if(!empty($groupid) && is_numeric($groupid)){
    $check = mysql_query("SELECT * FROM groups_details WHERE id = '".$groupid."' LIMIT 1") or die(mysql_error());
    $exists = mysql_num_rows($check);
} else {
    echo "<div class=\"groups-info-basic\">
	<div class=\"groups-info-close-container\"><a href=\"#\" class=\"groups-info-close\"></a></div>
        Invalid group or no group supplied.
          </div>";
    exit;
}

if($exists < 1){ exit; }

$data = mysql_fetch_assoc($check);

echo "<div class=\"groups-info-basic\">
	<div class=\"groups-info-close-container\"><a href=\"#\" class=\"groups-info-close\"></a></div>
	
	<div class=\"groups-info-icon\"><a href=\"group_profile.php?id=".$groupid."\"><img src=\"./habbo-imaging/badge-fill/".$data['badge'].".gif\" /></a></div>
	<h4><a href=\"group_profile.php?id=".$groupid."\">".stripslashes($data['name'])."</a></h4>
	
	<p>
Group created:<br />
<b>".$data['created']."</b>
	</p>
	
	<div class=\"groups-info-description\">".stripslashes(nl2br($data['description']))."</div>

</div>";