<?php

require_once('core.php');
require_once('includes/session.php');

$key = $_GET['key'];

switch($key){
	case "friends_all": $mode = 1; break;
	case "groups": $mode = 2; break;
	case "rooms": $mode = 3; break;
}

if(!isset($mode) || !isset($key)){ $mode = 1; }

switch($mode){
	case 1:
		$str = "friends";
		$get_em = mysql_query("SELECT friendid,userid FROM messenger_friendships WHERE userid = '".$my_id."' OR friendid = '".$my_id."' ORDER BY friendid LIMIT 500") or die(mysql_error());
		break;
	case 2:
		$str = "groups";
		$get_em = mysql_query("SELECT * FROM groups_memberships WHERE userid = '".$my_id."' AND is_pending = '0' ORDER BY member_rank LIMIT 10") or die(mysql_error());
		break;
	case 3:
		$str = "rooms";
		$get_em = mysql_query("SELECT * FROM rooms WHERE owner  = '".$name."' ORDER BY name ASC LIMIT 100") or die(mysql_error());
		break;
}

$results = mysql_num_rows($get_em);
$oddeven = 0;

	if($results > 0){
		if($mode == 1){
		echo "<ul id=\"offline-friends\">\n";
			while ($row = mysql_fetch_assoc($get_em)){
			if($row['userid'] == $my_id){
				$userdatasql = mysql_query("SELECT name FROM users WHERE id = '".$row['friendid']."' LIMIT 1") or die(mysql_error());
			} else {
				$userdatasql = mysql_query("SELECT name FROM users WHERE id = '".$row['userid']."' LIMIT 1") or die(mysql_error());
			}
			$user_exists = mysql_num_rows($userdatasql);
				if($user_exists > 0){
					$userrow = mysql_fetch_assoc($userdatasql);
					$oddeven++;
					if(IsEven($oddeven)){ $even = "odd"; } else { $even = "even"; }
					printf("        <li class=\"%s\"><a href=\"user_profile.php?name=%s\">%s</a></li>\n",$even,$userrow['name'],$userrow['name']);
				}
			}
		echo "\n</ul>";
		} elseif($mode == 2){
		echo "<ul id=\"quickmenu-groups\">\n";

		$num = 0;

			while($row = mysql_fetch_assoc($get_em)){

				$num++;

				$group_id = $row['groupid'];

				$check = mysql_query("SELECT name,ownerid FROM groups_details WHERE id = '".$group_id."' LIMIT 1") or die(mysql_error());
				$groupdata = mysql_fetch_assoc($check);

				echo "<li class=\"";
				if(IsEven($num)){ echo "odd"; } else { echo "even"; }
				echo "\">";

				if($row['is_current'] == 1){ echo "<div class=\"favourite-group\" title=\"Favourite\"></div>\n"; }
				if($row['member_rank'] > 1 && $groupdata['ownerid'] !== $my_id){ echo "<div class=\"admin-group\" title=\"Admin\"></div>\n"; }
				if($groupdata['ownerid'] == $my_id && $row['member_rank'] > 1){ echo "<div class=\"owned-group\" title=\"Owner\"></div>\n"; }

				echo "\n<a href=\"group_profile.php?id=".$group_id."\">".stripslashes($groupdata['name'])."</a>\n</li>";
			}

		echo "\n</ul>";
		} elseif($mode == 3){
		echo "<ul id=\"quickmenu-rooms\">\n";
			while ($row = mysql_fetch_assoc($get_em)){
			$oddeven++;
			if(IsEven($oddeven)){ $even = "odd"; } else { $even = "even"; }
			printf("        <li class=\"%s\"><a href=\"client.php?forwardId=2&amp;roomId=%s\" onclick=\"roomForward(this, '%s', 'private'); return false;\" target=\"client\" id=\"room-navigation-link_%s\">%s</a></li>\n",$even,$row['id'],$row['id'],$row['id'],$row['name']);
			}
		echo "\n</ul>";
		} else {
		echo "Invalid mode";
		}
	} else {
		echo "<ul id=\"quickmenu-" . $str . "\">\n	<li class=\"odd\">You don't have any " . $str . " yet</li>\n</ul>";
	}

	if($mode == "3"){
	echo "<p class=\"create-room\"><a href=\"client.php?shortcut=roomomatic\" onclick=\"HabboClient.openShortcut(this, 'roomomatic'); return false;\" target=\"client\">Create a new room</a></p>";
	} elseif($mode == "2"){
	echo "<p class=\"create-group\"><a href=\"#\" onclick=\"GroupPurchase.open(); return false;\">Create a group</a></p>";
	}

?>