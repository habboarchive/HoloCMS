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

$hid = FilterText($_GET['hid']);
$first = FilterText($_GET['first']);
?>
<?php if($hid == "h120"){ ?>
<head>
<link rel="stylesheet" href="../web-gallery/v2/styles/rooms.css" type="text/css" />
<script src="../web-gallery/static/js/rooms.js" type="text/javascript"></script>
<script src="../web-gallery/static/js/rooms.js" type="text/javascript"></script>
</head>
<div id="rooms-habblet-list-container-h120" class="recommendedrooms-lite-habblet-list-container">
        <ul class="habblet-list">
<?php
$i = 0;
$getem = mysql_query("SELECT *,COUNT(vote) AS votes FROM rooms, room_votes WHERE room_votes.roomid = rooms.id GROUP BY id ORDER BY votes DESC LIMIT 5") or die(mysql_error());

while ($row = mysql_fetch_assoc($getem)) {
    if($row['owner'] !== ""){ // Public Rooms (and possibly bugged rooms) have no owner, thus do not display them
        $i++;

        if(IsEven($i)){
            $even = "odd";
        } else {
            $even = "even";
        }

        // Calculate percentage
        if($row['visitors_now'] == 0){ $row['visitors_now'] = 1; }
        $data[$i] = ($row['visitors_now'] / $row['visitors_max']) * 100;

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
		
		if($row['showname'] == "1"){ $roomname = $row['name']; }else{ $roomname = ""; }

        printf("<li class=\"%s\">
    <span class=\"clearfix enter-room-link room-occupancy-%s\" title=\"Go to room\" roomid=\"%s\">
	    <span class=\"room-enter\">Enter</span>
	    <span class=\"room-name\">%s</span>
	    <span class=\"room-description\">%s</span>
		<span class=\"room-owner\">Owner: <a href=\"user_profile.php?name=%s\">%s</a></span>
    </span>
</li>", $even, $room_fill, $row['id'], HoloText($roomname), FilterText($row['descr']), $row['owner'], $row['owner']);
    }
}
?>

        </ul>
            <div id="room-more-data-h120" style="display: none">
                <ul class="habblet-list room-more-data">

<?php
$i = 0;
$getem = mysql_query("SELECT *,COUNT(vote) AS votes FROM rooms, room_votes WHERE room_votes.roomid = rooms.id GROUP BY id ORDER BY votes DESC LIMIT 15 OFFSET 5") or die(mysql_error());

while ($row = mysql_fetch_assoc($getem)) {
    if($row['owner'] !== ""){ // Public Rooms (and possibly bugged rooms) have no owner, thus do not display them
        $i++;

        if(IsEven($i)){
            $even = "odd";
        } else {
            $even = "even";
        }

        // Calculate percentage
        if($row['visitors_now'] == 0){ $row['visitors_now'] = 1; }
        $data[$i] = ($row['visitors_now'] / $row['visitors_max']) * 100;

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
		
		if($row['showname'] == "1"){ $roomname = $row['name']; }else{ $roomname = ""; }

        printf("<li class=\"%s\">
    <span class=\"clearfix enter-room-link room-occupancy-%s\" title=\"Go to room\" roomid=\"%s\">
	    <span class=\"room-enter\">Enter</span>
	    <span class=\"room-name\">%s</span>
	    <span class=\"room-description\">%s</span>
		<span class=\"room-owner\">Owner: <a href=\"user_profile.php?name=%s\">%s</a></span>
    </span>
</li>", $even, $room_fill, $row['id'], HoloText($roomname), FilterText($row['descr']), $row['owner'], $row['owner']);
    }
}
?>
                </ul>
            </div>
            <div class="clearfix">
                <a href="#" class="room-toggle-more-data" id="room-toggle-more-data-h120">Show more rooms</a>
            </div>
</div>
<script type="text/javascript">
L10N.put("show.more", "Show more rooms");
L10N.put("show.less", "Show fewer rooms");
var roomListHabblet_h120 = new RoomListHabblet("rooms-habblet-list-container-h120", "room-toggle-more-data-h120", "room-more-data-h120");
</script>
<?php }elseif($hid == "h122"){ ?>
<head>
<script src="../web-gallery/static/js/moredata.js" type="text/javascript"></script>
</head>
<div id="hotgroups-habblet-list-container" class="habblet-list-container groups-list">
    <ul class="habblet-list two-cols clearfix">
<?php
$i = 0;
$j = 1;
$getem = mysql_query("SELECT * FROM groups_details ORDER BY views DESC LIMIT 10") or die(mysql_error());

while ($row = mysql_fetch_assoc($getem)) {
        $i++;

        if(IsEven($i)){
            $left = "right";
        } else {
            $left = "left";
			$j++;
        }
		
        if(IsEven($j)){
            $even = "even";
        } else {
            $even = "odd";
        }

        printf("<li class=\"%s %s\" style=\"background-image: url(./habbo-imaging/badge.php?badge=%s)\">
                <a class=\"item\" href=\"./group_profile.php?id=%s\"><span class=\"index\">%s.</span> %s</a>
            </li>\n", $even, $left, $row['badge'], $row['id'], $i, HoloText($row['name']));
}
?>
</ul>
    <div id="hotgroups-list-hidden-h122" style="display: none">
    <ul class="habblet-list two-cols clearfix">
<?php
$i = 10;
$j = 1;
$getem = mysql_query("SELECT * FROM groups_details ORDER BY views DESC LIMIT 40 OFFSET 10") or die(mysql_error());

while ($row = mysql_fetch_assoc($getem)) {
        $i++;

        if(IsEven($i)){
            $left = "left";
        } else {
            $left = "right";
			$j++;
        }
		
        if(IsEven($j)){
            $even = "odd";
        } else {
            $even = "even";
        }

        printf("<li class=\"%s %s\" style=\"background-image: url(./habbo-imaging/badge.php?badge=%s)\">
                <a class=\"item\" href=\"./group_profile.php?id=%s\"><span class=\"index\">%s.</span> %s</a>
            </li>\n", $even, $left, $row['badge'], $row['id'], $i, HoloText($row['name']));
}
?>
</ul>
</div>
    <div class="clearfix">
        <a href="#" class="hotgroups-toggle-more-data secondary" id="hotgroups-toggle-more-data-h122">Show more Groups</a>
    </div>
<script type="text/javascript">
L10N.put("show.more.groups", "Show more Groups");
L10N.put("show.less.groups", "Show less Groups");
var hotGroupsMoreDataHelper = new MoreDataHelper("hotgroups-toggle-more-data-h122", "hotgroups-list-hidden-h122","groups");
</script>
</div>
<?php } ?>