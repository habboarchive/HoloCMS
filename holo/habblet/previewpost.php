<?php
/*===================================================+
|| # HoloCMS - Website and Content Management System
|+===================================================+
|| # Copyright © 2008 Meth0d. All rights reserved.
|| # http://www.meth0d.org
|+===================================================+
|| # HoloCMS is provided \"as is\" and comes without
|| # warrenty of any kind. HoloCMS is free software!
|+===================================================*/

include('../core.php');
$x = $_GET['x'];

if(getContent('forum-enabled') !== "1"){ header("Location: index.php"); exit; }
if(!session_is_registered(username)){ exit; }
if($x !== "topic" && $x !== "post"){ exit; }

$message = htmlspecialchars($_POST['message']);
$topicName = $_POST['topicName'];

if(empty($topicName)){ $topicName = "Post Preview"; }

echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"group-postlist-list\" id=\"group-postlist-list\">
<tr class=\"post-list-index-preview\">
	<td class=\"post-list-row-container\">
		<a href=\"user_profile.php?name=".$myrow['name']."\" class=\"post-list-creator-link post-list-creator-info\">".$myrow['name']."</a><br />
            &nbsp;&nbsp;<img alt=\"offline\" src=\"./web-gallery/images/myhabbo/habbo_online_anim.gif\" />
		<div class=\"post-list-posts post-list-creator-info\">Messages: ".$myrow['postcount']."</div>
		<div class=\"clearfix\">
            <div class=\"post-list-creator-avatar\"><img src=\"http://www.habbo.co.uk/habbo-imaging/avatarimage?figure=".$myrow['figure']."&size=b&direction=2&head_direction=2&gesture=sml\" alt=\"".$userdata['name']."\" /></div>
            <div class=\"post-list-group-badge\">";
                	if(GetUserGroup($my_id) !== false){       
                	echo "<a href=\"group_profile.php?id=".GetUserGroup($my_id)."\"><img src=\"./habbo-imaging/badge.php?badge=".GetUserGroupBadge($my_id)."\" /></a>";
		}
echo "            </div>
            <div class=\"post-list-avatar-badge\">";
		if(GetUserBadge($my_id) !== false){
			echo "<img src=\"http://images.habbohotel.co.uk/c_images/album1584/".GetUserBadge($my_id).".gif\" />";
		}
echo "</div>
        </div>
        <div class=\"post-list-motto post-list-creator-info\">".$userdata['mission']."</div>
	</td>
	<td class=\"post-list-message\" valign=\"top\" colspan=\"2\">
            <a href=\"#\" id=\"edit-post-message\" class=\"resume-edit-link\">&laquo; Modify</a>
        <span class=\"post-list-message-header\"> ".$topicName."</span><br />
        <span class=\"post-list-message-time\">".$date_full."</span>
        <div class=\"post-list-report-element\">
        </div>
        <div class=\"post-list-content-element\">
            ".bbcode_format(trim(nl2br(stripslashes($message))))."
        </div>
	<div>&nbsp;</div><div>&nbsp;</div>

        <div>\n";
			if($x == "topic"){
		        echo "<a id=\"topic-form-cancel-preview\" class=\"new-button red-button cancel-icon\" href=\"#\"><b><span></span>Cancel</b><i></i></a>
		        <a id=\"topic-form-save-preview\" class=\"new-button green-button save-icon\" href=\"#\"><b><span></span>Save</b><i></i></a>            ";
			} else {
			  echo "<a id=\"post-form-cancel\" class=\"new-button red-button cancel-icon\" href=\"#\"><b><span></span>Cancel</b><i></i></a>
		        <a id=\"post-form-save\" class=\"new-button green-button save-icon\" href=\"#\"><b><span></span>Save</b><i></i></a>";
			}
echo "\n	</div>
	</td>
</tr>
</table>";

?>