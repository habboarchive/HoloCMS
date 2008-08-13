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

include('../core.php');
include('../includes/session.php');

$category = $_POST['eventTypeId']; ?>

<ul class="habblet-list">
<?php
$getem = mysql_query("SELECT * FROM events WHERE category = '$category'");
while ($row = mysql_fetch_assoc($getem)) {
	$roomrow = mysql_fetch_assoc(mysql_query("SELECT * FROM rooms WHERE id = '".$row['roomid']."'"));
	$userrow = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE id = '".$row['userid']."'"));
	$i++;

	if(IsEven($i)){
		$even = "odd";
	} else {
		$even = "even";
	}

	// Calculate percentage
	if($roomrow['incnt_max'] == 0){ $roomrow['incnt_max'] = 1; }
	$data[$i] = ($roomrow['incnt_now'] / $roomrow['incnt_max']) * 100;

	// Base room icon based on this - percantage levels may not be habbolike
	if($data[$i] == 99 || $data[$i] > 99){
		$room_fill = 5;
	} elseif($data[$i] > 65){
		$room_fill = 4;
	} elseif($data[$i] > 32){
		$room_fill = 3;
	} elseif($data[$i] > 0){
		$room_fill = 2;
	} elseif($data[$i] < 1){
		$room_fill = 1;
	}

	printf("<li class=\"%s room-occupancy-%s\" roomid=\"%s\">
<div title=\"Go to the room where this event is held\">
	<span class=\"event-name\"><a href=\"./client.php?forwardId=2&amp;roomId=%s\" onclick=\"HabboClient.roomForward(this, '%s', 'private'); return false;\">%s</a></span>
	<span class=\"event-owner\"> by <a href=\"/user_profile.php?id=%s\">%s</a></span>
	<p>%s (<span class=\"event-date\">%s</span>)</p>
</div>
</li>", $even, $room_fill, $row['roomid'], $row['roomid'], $row['roomid'], $row['name'], $row['userid'], $userrow['name'], $row['description'], $row['date']);
}
?>
</ul>