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

$refer = $_SERVER['HTTP_REFERER']; $pos = strrpos($refer, "group_profile.php"); if ($pos === false) { exit; }

$groupid = $_POST['groupId'];
$page = $_POST['pageNumber'];
$searchString = $_POST['searchString'];
$pending = $_POST['pending'];

if($pending == "true"){ $pending = true; } else { $pending = false; }
if(!is_numeric($groupid)){ exit; }

$check = mysql_query("SELECT member_rank FROM groups_memberships WHERE userid = '".$my_id."' AND groupid = '".$groupid."' AND member_rank > 1 AND is_pending = '0' LIMIT 1") or die(mysql_error());
$is_member = mysql_num_rows($check);

if($is_member > 0){
	$my_membership = mysql_fetch_assoc($check);
	$member_rank = $my_membership['member_rank'];
	if($member_rank < 2){ exit; }
} else {
	exit;
}

$check = mysql_query("SELECT * FROM groups_details WHERE id = '".$groupid."' LIMIT 1") or die(mysql_error());
$valid = mysql_num_rows($check);

if($valid > 0){ $groupdata = mysql_fetch_assoc($check); } else {exit; }

$members = mysql_evaluate("SELECT COUNT(*) FROM groups_memberships WHERE groupid = '".$groupid."' AND is_pending = '0'") or die(); // There have to be members; die if not
$members_pending = mysql_evaluate("SELECT COUNT(*) FROM groups_memberships WHERE groupid = '".$groupid."' AND is_pending = '1'");

$pages = ceil($members / 12);
$pages_pending = ceil($members_pending / 12);

$page = $_POST['pageNumber'];

if($pending == true){
        $totalPagesMemberList = $pages_pending;
        $totalMembers = $members_pending;
        if($page < 1 || empty($page) || $page > $pages_pending){ $page = 1; }
} else {
        $totalPagesMemberList = $pages;
        $totalMembers = $members;
        if($page < 1 || empty($page) || $page > $pages){ $page = 1; }
}

$queryLimitMin = ($page * 12) - 12;
$queryLimit = $queryLimitMin . ",12";

header("X-JSON: {\"pending\":\"Pending Members (" . $members_pending . ")\",\"members\":\"Members (" . $members . ")\"}");

echo "<div id=\"group-memberlist-members-list\">

<form method=\"post\" action=\"#\" onsubmit=\"return false;\">
<ul class=\"habblet-list two-cols clearfix\">\n";

	$counter = 0;

	if($pending == true){
		if($members_pending < 1){
			echo "There are no pending membership requests at this time. Please check back later.";
		} else {
			$get_memberships = mysql_query("SELECT * FROM groups_memberships WHERE groupid = '".$groupid."' AND is_pending = '1' ORDER BY member_rank DESC LIMIT ".$queryLimit."") or die(mysql_error());
			while($membership = mysql_fetch_assoc($get_memberships)){
				if(!is_numeric($membership['userid'])){ exit; }
				$get_userdata = mysql_query("SELECT * FROM users WHERE id = '".$membership['userid']."' LIMIT 1") or die(mysql_error());
				$valid_user = mysql_num_rows($get_userdata);
				if($valid_user > 0){
					$counter++;
					$userdata = mysql_fetch_assoc($get_userdata);
					if(IsEven($counter)){ $pos = "right"; $rights++; } else { $pos = "left"; $lefts++; }
					if(IsEven($lefts)){ $oddeven = "odd"; } else { $oddeven = "even"; }
					echo "<li class=\"".$oddeven." online ".$pos."\">
    	<div class=\"item\" style=\"padding-left: 5px; padding-bottom: 4px;\">
    		<div style=\"float: right; width: 16px; height: 16px; margin-top: 1px\">\n";
				if($membership['userid'] == $groupdata['ownerid']){ echo "<img src=\"./web-gallery/images/groups/owner_icon.gif\" width=\"15\" height=\"15\" alt=\"Owner\" title=\"Owner\" />\n"; }
				elseif($membership['member_rank'] > 1){ echo "<img src=\"./web-gallery/images/groups/administrator_icon.gif\" width=\"15\" height=\"15\" alt=\"Administrator\" title=\"Administrator\" />"; }
			echo "</div>
				<input id=\"group-memberlist-m-".$userdata['id']."\" type=\"checkbox\""; if($membership['userid'] == $groupdata['ownerid'] || $membership['userid'] == $my_id){ echo " disabled=\"disabled\""; } echo " style=\"margin: 0; padding: 0; vertical-align: middle\"/>
    	    <a class=\"home-page-link\" href=\"user_profile.php?name=".$userdata['name']."\"><span>".$userdata['name']."</span></a>
        </div>
    </li>";
				}
			}
		}
	} else {
		if($members < 1){
			echo "There are no pending membership requests at this time. Please check back later.";
		} else {
			$get_memberships = mysql_query("SELECT * FROM groups_memberships WHERE groupid = '".$groupid."' AND is_pending = '0' ORDER BY member_rank DESC LIMIT ".$queryLimit."") or die(mysql_error());
			while($membership = mysql_fetch_assoc($get_memberships)){
                                $tinyrank = "m";
				if(!is_numeric($membership['userid'])){ exit; }
				$get_userdata = mysql_query("SELECT * FROM users WHERE id = '".$membership['userid']."' LIMIT 1") or die(mysql_error());
				$valid_user = mysql_num_rows($get_userdata);
				if($valid_user > 0){
					$counter++;
					$userdata = mysql_fetch_assoc($get_userdata);
					if(IsEven($counter)){ $pos = "right"; $rights++; } else { $pos = "left"; $lefts++; }
					if(IsEven($lefts)){ $oddeven = "odd"; } else { $oddeven = "even"; }
					echo "<li class=\"".$oddeven." online ".$pos."\">
    	<div class=\"item\" style=\"padding-left: 5px; padding-bottom: 4px;\">
    		<div style=\"float: right; width: 16px; height: 16px; margin-top: 1px\">\n";
				if($membership['userid'] == $groupdata['ownerid']){ $tinyrank = "a"; echo "<img src=\"./web-gallery/images/groups/owner_icon.gif\" width=\"15\" height=\"15\" alt=\"Owner\" title=\"Owner\" />\n"; }
				elseif($membership['member_rank'] > 1){ $tinyrank = "a"; echo "<img src=\"./web-gallery/images/groups/administrator_icon.gif\" width=\"15\" height=\"15\" alt=\"Administrator\" title=\"Administrator\" />"; }
			echo "</div>
				<input id=\"group-memberlist-".$tinyrank."-".$userdata['id']."\" type=\"checkbox\""; if($membership['userid'] == $groupdata['ownerid']){ echo " disabled=\"disabled\""; } echo " style=\"margin: 0; padding: 0; vertical-align: middle\"/>
    	    <a class=\"home-page-link\" href=\"user_profile.php?name=".$userdata['name']."\"><span>".$userdata['name']."</span></a>
        </div>
    </li>";
				}
			}
		}
	}
        
        $results = @mysql_num_rows($get_memberships);
		
echo "</ul>

</form>



</div>
<div id=\"member-list-pagenumbers\">
".($queryLimitMin + 1)." - ".($results + $queryLimitMin)." / ".$totalMembers."
</div>
<div id=\"member-list-paging\" >";
if($page > 1){ echo "<a href=\"#\" class=\"avatar-list-paging-link\" id=\"memberlist-search-first\" >First</a>"; } else { echo "First"; }
echo " | ";
if($page > 1){ echo "<a href=\"#\" class=\"avatar-list-paging-link\" id=\"memberlist-search-previous\" >&lt;&lt;</a>"; } else { echo "&lt;&lt;"; }
echo " | ";
if($page < $totalPagesMemberList){ echo "<a href=\"#\" class=\"avatar-list-paging-link\" id=\"memberlist-search-next\" >&gt;&gt;</a>"; } else { echo "&gt;&gt;"; }
echo " | ";
if($page < $totalPagesMemberList){ echo "<a href=\"#\" class=\"avatar-list-paging-link\" id=\"memberlist-search-last\" >Last</a>"; } else { echo "Last"; }
echo "<input type=\"hidden\" id=\"pageNumberMemberList\" value=\"".$page."\"/>
<input type=\"hidden\" id=\"totalPagesMemberList\" value=\"".$totalPagesMemberList."\"/>
</div>";


