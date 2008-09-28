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

require_once('core.php');
require_once('includes/session.php');

if(isset($_GET['tag']) || isset($_GET['name']) || isset($_POST['name'])){
	if(isset($_GET['tag'])){
	$searchname = addslashes($_GET['tag']);
	} else if(isset($_GET['name'])){
	$searchname = addslashes($_GET['name']);
	} else if(isset($_POST['name'])){
	$searchname = addslashes($_POST['name']);
	} else {
	$error = true;
	}

	$user_sql = mysql_query("SELECT * FROM users WHERE name = '".$searchname."' LIMIT 1") or die(mysql_error());
	$user_exists = mysql_num_rows($user_sql);

	if($user_exists == "1"){
	$error = false;
	$user_row = mysql_fetch_assoc($user_sql);
	$pagename = "User Profile - ".$user_row['name']."";
		if($user_row['rank'] == "6"){
		$drank = "Administrator";
		} else if($user_row['rank'] == "5"){
		$drank = "Moderator";
		} else if($user_row['rank'] == "4"){
		$drank = "Staff Member";
		} else if($user_row['rank'] < 4){
		$drank = "Member";
		}
	} else {
	$error = true;
	}

} else if(isset($_GET['tagid']) || isset($_GET['id']) || isset($_POST['id'])){
	if(isset($_GET['tagid'])){
	$searchid = addslashes($_GET['tagid']);
	} else if(isset($_GET['id'])){
	$searchid = addslashes($_GET['id']);
	} else if(isset($_POST['id'])){
	$searchid = addslashes($_POST['id']);
	} else {
	$error = true;
	}

	$user_sql = mysql_query("SELECT * FROM users WHERE id = '".$searchid."' LIMIT 1") or die(mysql_error());
	$user_exists = mysql_num_rows($user_sql);

	if($user_exists == "1"){
	$error = false;
	$user_row = mysql_fetch_assoc($user_sql);
	$pagename = "User Profile - ".$user_row['name']."";
		if($user_row['rank'] == "6"){
		$drank = "Administrator";
		} else if($user_row['rank'] == "5"){
		$drank = "Moderator";
		} else if($user_row['rank'] == "4"){
		$drank = "Staff Member";
		} else if($user_row['rank'] < 4){
		$drank = "Member";
		}
	} else {
	$error = true;
	}

} else {
$error = true;
}

if(isset($_GET['do']) && $_GET['do'] == "edit" && $logged_in){
	if($user_row['name'] == $name){
	$edit_mode = true;
	mysql_query("UPDATE cms_homes_group_linker SET active = '0' WHERE userid = '".$my_id."' LIMIT 1") or die(mysql_error());
	} else {
	header("location:user_profile.php?do=bounce&name=".$user_row['name'].""); exit;
	$edit_mode = false;
	}
} else {
$edit_mode = false;
}

if(!$error && !IsUserBanned($user_row['id'])){
$body_id = "viewmode";
	if($edit_mode){
	$body_id = "editmode";
	}
} else {
$body_id = "home";
}

if($searchname == $rawname && $logged_in){
$pageid = "myprofile";
} else {
$pageid = "profile";
}

$bg_fetch = mysql_query("SELECT data FROM cms_homes_stickers WHERE type = '4' AND userid = '".$user_row['id']."' AND groupid = '-1' LIMIT 1");
$bg_exists = mysql_num_rows($bg_fetch);

	if($bg_exists < 1){ // if there's no background override for this user set it to the standard
		$bg = "b_bg_pattern_abstract2";
	} else {
		$bg = mysql_fetch_array($bg_fetch);
		$bg = "b_" . $bg[0];
	}

if(!$error && !IsUserBanned($user_row['id'])){
include('templates/community/hsubheader.php');
include('templates/community/header.php');
?>

<div id="container">
	<div id="content" style="position: relative" class="clearfix">
    <div id="mypage-wrapper" class="cbb blue">
<div class="box-tabs-container box-tabs-left clearfix">
	<?php if($user_row['name'] == $name && $edit_mode !== true){ ?><a href="user_profile.php?do=edit&name=<?php echo addslashes($name); ?>" id="edit-button" class="new-button dark-button edit-icon" style="float:left"><b><span></span>Modify</b><i></i></a><?php } ?>
    <h2 class="page-owner"><?php echo $user_row['name']; ?></h2>
    <ul class="box-tabs"></ul>
</div>
	<div id="mypage-content">
<?php if($edit_mode == true){ ?>
<div id="top-toolbar" class="clearfix">
	<ul>
		<li><a href="#" id="inventory-button">Inventory</a></li>
		<li><a href="#" id="webstore-button">Webstore</a></li>
	</ul>

	<form action="#" method="get" style="width: 50%;">
		<a id="cancel-button" class="new-button red-button cancel-icon" href="#"><b><span></span>Cancel</b><i></i></a>
		<a id="save-button" class="new-button green-button save-icon" href="#"><b><span></span>Save</b><i></i></a>
	</form>
</div>
<?php } ?>
		<div id="mypage-bg" class="<?php echo $bg; ?>">
			<div id="playground-outer">
				<div id="playground">

<?php
$get_em = mysql_query("SELECT id,type,x,y,z,data,skin,subtype,var FROM cms_homes_stickers WHERE userid = '".$user_row['id']."' AND groupid = '-1' AND type < 4 LIMIT 200") or die(mysql_error());

while ($row = mysql_fetch_array($get_em, MYSQL_NUM)) {

	switch($row[1]){
	default: $type = "sticker"; break;
	case 1: $type = "sticker"; break;
	case 2: $type = "widget"; break;
	case 3: $type = "stickie"; break;
	case 4: $type = "ignore"; break;
	}

	if($edit_mode == true){
	$edit = "\n<img src=\"./web-gallery/images/myhabbo/icon_edit.gif\" width=\"19\" height=\"18\" class=\"edit-button\" id=\"" . $type . "-" . $row[0] . "-edit\" />
<script language=\"JavaScript\" type=\"text/javascript\">
Event.observe(\"".$type."-".$row[0]."-edit\", \"click\", function(e) { openEditMenu(e, ".$row[0].", \"".$type."\", \"".$type."-".$row[0]."-edit\"); }, false);
</script>\n";
	} else {
	$edit = " ";
	}

	// old check
	if($user_row['rank'] > 5){
		$content = bbcode_format(nl2br(stripslashes($row[5])));
	} else {
		$content = bbcode_format(nl2br(stripslashes($row[5])));
	}

	if($type == "stickie"){
	printf("<div class=\"movable stickie n_skin_%s-c\" style=\" left: %spx; top: %spx; z-index: %s;\" id=\"stickie-%s\">
	<div class=\"n_skin_%s\" >
		<div class=\"stickie-header\">
			<h3>%s</h3>
			<div class=\"clear\"></div>
		</div>
		<div class=\"stickie-body\">
			<div class=\"stickie-content\">
				<div class=\"stickie-markup\">%s</div>
				<div class=\"stickie-footer\">
				</div>
			</div>
		</div>
	</div>
</div>",$row[6],$row[2],$row[3],$row[4],$row[0],$row[6],$edit,$content);
	} elseif($type == "sticker"){
	printf("<div class=\"movable sticker s_%s\" style=\"left: %spx; top: %spx; z-index: %s\" id=\"sticker-%s\">\n%s\n</div>", $row[5], $row[2], $row[3], $row[4], $row[0], $edit);
	} elseif($type == "widget"){

		switch($row[7]){
		case 1: $subtype = "Profilewidget"; break;
		case 2: $subtype = "GroupsWidget"; break;
		case 3: $subtype = "RoomsWidget"; break;
		case 4: $subtype = "GuestbookWidget"; break;
		case 5: $subtype = "FriendsWidget"; break;
		case 6: $subtype = "TraxPlayerWidget"; break;
		case 7: $subtype = "HighScoresWidget"; break;
		case 8: $subtype = "BadgesWidget";
		}

		if($subtype == "GroupsWidget"){
		$groups = mysql_evaluate("SELECT COUNT(*) FROM groups_memberships WHERE userid = '".$user_row['id']."' AND is_pending = '0' LIMIT 1");

			echo "<div class=\"movable widget GroupsWidget\" id=\"widget-".$row[0]."\" style=\" left: ".$row[2]."px; top: ".$row[3]."px; z-index: ".$row[4].";\">
<div class=\"w_skin_".$row[6]."\">
	<div class=\"widget-corner\" id=\"widget-".$row[0]."-handle\">
		<div class=\"widget-headline\"><h3><span class=\"header-left\">&nbsp;</span><span class=\"header-middle\">MY GROUPS (<span id=\"groups-list-size\">".$groups."</span>)</span><span class=\"header-right\">".$edit."</span></h3>
		</div>
	</div>
	<div class=\"widget-body\">
		<div class=\"widget-content\">

<div class=\"groups-list-container\">
<ul class=\"groups-list\">";

$get_groups = mysql_query("SELECT * FROM groups_memberships WHERE userid = '".$user_row['id']."' AND is_pending = '0'") or die(mysql_error());
while($membership_row = mysql_fetch_assoc($get_groups)){
	$get_groupdata = mysql_query("SELECT * FROM groups_details WHERE id = '".$membership_row['groupid']."' LIMIT 1") or die(mysql_error());
	$grouprow = mysql_fetch_assoc($get_groupdata);

	echo "	<li title=\"".$grouprow['name']."\" id=\"groups-list-".$row[0]."-".$grouprow['id']."\">
		<div class=\"groups-list-icon\"><a href=\"group_profile.php?id=".$grouprow['id']."\"><img src='./habbo-imaging/badge-fill/".$grouprow['badge'].".gif'/></a></div>
		<div class=\"groups-list-open\"></div>
		<h4>
		<a href=\"group_profile.php?id=".$membership_row['groupid']."\">".$grouprow['name']."</a>
		</h4>
		<p>
		Group created:<br />";
		if($membership_row['is_current'] == 1){ echo "<div class=\"favourite-group\" title=\"Favourite\"></div>\n"; }
		if($membership_row['member_rank'] > 1 && $grouprow['ownerid'] !== $user_row['id']){ echo "<div class=\"admin-group\" title=\"Admin\"></div>\n"; }
		if($grouprow['ownerid'] == $user_row['id'] && $membership_row['member_rank'] > 1){ echo "<div class=\"owned-group\" title=\"Owner\"></div>\n"; }
		echo "<b>".$grouprow['created']."</b>
		</p>
		<div class=\"clear\"></div>
	</li>";

}

echo "</ul></div>

<div class=\"groups-list-loading\"><div><a href=\"#\" class=\"groups-loading-close\"></a></div><div class=\"clear\"></div><p style=\"text-align:center\"><img src=\"./web-gallery/images/progress_bubbles.gif\" alt=\"\" width=\"29\" height=\"6\" /></p></div>
<div class=\"groups-list-info\"></div>

		<div class=\"clear\"></div>
		</div>
	</div>
</div>
</div>

<script type=\"text/javascript\">
document.observe(\"dom:loaded\", function() {
	new GroupsWidget('".$user_row['id']."', '".$row[0]."');
});
</script>";
		} elseif($subtype == "Profilewidget"){

		$found_profile = true;

		$info = mysql_query("SELECT * FROM users WHERE name = '".$searchname."' LIMIT 1") or die(mysql_error());
		$userdata = mysql_fetch_assoc($info);
		$valid = mysql_num_rows($info);

			if($valid > 0){
			echo "<div class=\"movable widget ProfileWidget\" id=\"widget-".$row[0]."\" style=\" left: ".$row[2]."px; top: ".$row[3]."px; z-index: ".$row[4].";\">
<div class=\"w_skin_".$row[6]."\">
	<div class=\"widget-corner\" id=\"widget-".$row[0]."-handle\">
		<div class=\"widget-headline\"><h3>" . $edit . "
<span class=\"header-left\">&nbsp;</span><span class=\"header-middle\">MY ".strtoupper($shortname)."</span><span class=\"header-right\">&nbsp;</span></h3>
		</div>
	</div>
	<div class=\"widget-body\">
		<div class=\"widget-content\">
	<div class=\"profile-info\">

		<div class=\"name\" style=\"float: left\">
			<span class=\"name-text\">".$userdata['name']."</span>
		</div>

		<br class=\"clear\" />";

			if(IsUserOnline($userdata['id'])){ echo "<img alt=\"online\" src=\"./web-gallery/images/myhabbo/habbo_online_anim_big.gif\" />"; } else { echo "<img alt=\"offline\" src=\"./web-gallery/images/myhabbo/habbo_offline_big.gif\" />"; }

		echo "<div class=\"birthday text\">
			Created on:
		</div>
		<div class=\"birthday date\">
			".$userdata['hbirth']."
		</div>
		<div>";
		$groupbadge = GetUserGroupBadge($userdata['id']);
		$badge = GetUserBadge($userdata['name']);

		if($groupbadge !== false){
		echo "<a href='group_profile.php?id=".GetUserGroup($userdata['id'])."'><img src='./habbo-imaging/badge-fill/".$groupbadge.".gif'></a>";
		}

		if($badge !== false){
        	echo "<img src=\"".$cimagesurl.$badgesurl.$badge.".gif\" /></a>";
		}
echo "
        </div>
	</div>
	<div class=\"profile-figure\">
			<img alt=\"".$userdata['name']."\" src=\"http://www.habbo.co.uk/habbo-imaging/avatarimage?figure=".$userdata['figure']."&size=b&direction=4&head_direction=4&gesture=sml\" />
	</div>";
	if($userdata['mission'] != null){
		echo "<div class=\"profile-motto\">
			".stripslashes($userdata['mission'])."
			<div class=\"clear\"></div>
		</div>";
	}
	if($userdata['id'] != $my_id && $logged_in == true){ ?>
		<div class="profile-friend-request clearfix">
			<a href="./myhabbo/friends_add.php?id=<?php echo $userdata['id']; ?>" class="new-button" id="add-friend" style="float: left"><b>Add as friend</b><i></i></a>
		</div>
	<?php } 
	echo "<br clear=\"all\" style=\"display: block; height: 1px\"/>
    <div id=\"profile-tags-panel\">
    <div id=\"profile-tag-list\">
<div id=\"profile-tags-container\">\n";

$get_tags = mysql_query("SELECT * FROM cms_tags WHERE ownerid = '".$userdata['id']."' ORDER BY id LIMIT 20") or die(mysql_error());
$rows = mysql_num_rows($get_tags);

	$num = mysql_num_rows($get_tags);
	if($num > 0){

		if($userdata['id'] == $my_id && $logged_in){
			while ($row1 = mysql_fetch_assoc($get_tags)){


				printf("    <span class=\"tag-search-rowholder\">
        <a href=\"tags.php?tag=%s\" class=\"tag-search-link tag-search-link-%s\"
        >%s</a><img border=\"0\" class=\"tag-delete-link tag-delete-link-%s\" onMouseOver=\"this.src='./web-gallery/images/buttons/tags/tag_button_delete_hi.gif'\" onMouseOut=\"this.src='./web-gallery/images/buttons/tags/tag_button_delete.gif'\" src=\"./web-gallery/images/buttons/tags/tag_button_delete.gif\"
        /></span>", $row1['tag'], $row1['tag'], $row1['tag'], $row1['tag']);
			}
		} elseif($logged_in){
			while ($row1 = mysql_fetch_assoc($get_tags)){


				printf("    <span class=\"tag-search-rowholder\">
        <a href=\"tags.php?tag=%s\" class=\"tag-search-link tag-search-link-%s\"
        >%s</a><img border=\"0\" class=\"tag-add-link tag-add-link-%s\" onMouseOver=\"this.src='./web-gallery/images/buttons/tags/tag_button_add_hi.gif'\" onMouseOut=\"this.src='./web-gallery/images/buttons/tags/tag_button_add.gif'\" src=\"./web-gallery/images/buttons/tags/tag_button_add.gif\"
        /></span>", $row1['tag'], $row1['tag'], $row1['tag'], $row1['tag']);
			}
		} else {
			while ($row1 = mysql_fetch_assoc($get_tags)){


				printf("    <span class=\"tag-search-rowholder\">
        <a href=\"tags.php?tag=%s\" class=\"tag-search-link tag-search-link-%s\"
        >%s</a></span>", $row1['tag'], $row1['tag'], $row1['tag'], $row1['tag']);
			}
		}

	} else {
		echo "No tags";
	}

echo "\n    <img id=\"tag-img-added\" border=\"0\" src=\"./web-gallery/images/buttons/tags/tag_button_added.gif\" style=\"display:none\"/>
</div>

<script type=\"text/javascript\">
    document.observe(\"dom:loaded\", function() {
        TagHelper.setTexts({
            buttonText: \"OK\",
            tagLimitText: \"You\'ve reached the tag limit - delete one of your tags if you want to add a new one.\"
        });
    });
</script>
    </div>
<div id=\"profile-tags-status-field\">
 <div style=\"display: block;\">
  <div class=\"content-red\">
   <div class=\"content-red-body\">
    <span id=\"tag-limit-message\"><img src=\"./web-gallery/images/register/icon_error.gif\"/> You've reached the tag limit - delete one of your tags if you want to add a new one.</span>
    <span id=\"tag-invalid-message\"><img src=\"./web-gallery/images/register/icon_error.gif\"/> Invalid tag</span>
   </div>
  </div>
 <div class=\"content-red-bottom\">
  <div class=\"content-red-bottom-body\"></div>
 </div>
 </div>
</div>";


if($userdata['id'] == $my_id){
        echo "<div class=\"profile-add-tag\">
            <input type=\"text\" id=\"profile-add-tag-input\" maxlength=\"30\"/><br clear=\"all\"/>
            <a href=\"#\" class=\"new-button\" style=\"float:left;margin:5px 0 0 0;\" id=\"profile-add-tag\"><b>Add tag</b><i></i></a>
        </div>";
}
    echo "</div>
    <script type=\"text/javascript\">
		document.observe(\"dom:loaded\", function() {
			new ProfileWidget('".$userdata['id']."', '".$userdata['id']."', {
				headerText: \"Are you sure?\",
				messageText: \"Are you sure you want to ask <strong\>".$userdata['name']."</strong\> to be your friend?\",
				buttonText: \"OK\",
				cancelButtonText: \"Cancel\"
			});
		});
	</script>
		<div class=\"clear\"></div>
		</div>
	</div>
</div></div>";
	}
		} elseif($subtype == "RoomsWidget"){
		 ?>
		<div class="movable widget RoomsWidget" id="widget-<?php echo $row['0']; ?>" style=" left: <?php echo $row['2']; ?>px; top: <?php echo $row['3']; ?>px; z-index: <?php echo $row['4']; ?>;">
<div class="w_skin_<?php echo $row['6']; ?>">
	<div class="widget-corner" id="widget-<?php echo $row['0']; ?>-handle">
		<div class="widget-headline"><h3>
<?php echo $edit; ?>
</script>
		<span class="header-left">&nbsp;</span><span class="header-middle">MY ROOMS</span><span class="header-right">&nbsp;</span></h3>
		</div>	
	</div>
	<div class="widget-body">
		<div class="widget-content">
		<?php 			
		$roomsql = mysql_query("SELECT * FROM rooms WHERE owner = '".$user_row['name']."'");
		$count = mysql_num_rows($roomsql);
		if($count <> 0){ 
		?>

<div id="room_wrapper">
<table border="0" cellpadding="0" cellspacing="0">

			<?php 
			$i = 0;
			while ($roomrow = mysql_fetch_assoc($roomsql)) {
				$i++;
				if($count == $i){
					$asdf = " ";
				} else {
					$asdf = "class=\"dotted-line\"";
				}
				if($roomrow['state'] == 2){
					$qwer = "password";
					$zxcv = "password protected";
				} elseif($roomrow['state'] == 0){
					$qwer = "open";
					$zxcv = "enter room";
				} elseif($roomrow['state'] == 1){
					$qwer = "locked";
					$zxcv = "locked";
				}
				printf("<tr>
<td valign=\"top\" $asdf>
		<div class=\"room_image\">
				<img src=\"./web-gallery/images/myhabbo/rooms/room_icon_%s.gif\" alt=\"\" align=\"middle\"/>
		</div>
</td>
<td $asdf>
        	<div class=\"room_info\">
        		<div class=\"room_name\">
        			%s
        		</div>
					<img id=\"room-%s-report\" class=\"report-button report-r\"
						alt=\"report\"
						src=\"./web-gallery/images/myhabbo/buttons/report_button.gif\"
						style=\"display: none;\" />
				<div class=\"clear\"></div>
        		<div>%s
        		</div>
					<a href=\"/client?forwardId=2&amp;roomId=%s\"
					   target=\"\"
					   id=\"room-navigation-link_%s\"
					   onclick=\"HabboClient.roomForward(this, '%s', 'private', true); return false;\">
					 %s
					 </a>
        	</div>
		<br class=\"clear\" />

</td>
</tr>",$qwer, $roomrow['name'], $roomrow['id'], $roomrow['description'], $roomrow['id'], $roomrow['id'], $roomrow['id'], $zxcv);
			} ?>
		<br class="clear" />

</td>
</tr>
</table>
</div> <?php
		} else {
			echo "You do not have any rooms";
		} ?>
		<div class="clear"></div>
		</div>
	</div>
</div>
</div>
<?php 
	} elseif($subtype == "GuestbookWidget"){
	$sql = mysql_query("SELECT * FROM cms_guestbook WHERE widget_id = '".$row['0']."' ORDER BY id DESC");
	$count = mysql_num_rows($sql);
	if($row['10'] == "0"){;
		$status = "public";
	}else{
		$status = "private";
	}
	?>
	<div class="movable widget GuestbookWidget" id="widget-<?php echo $row['0']; ?>" style=" left: <?php echo $row['2']; ?>px; top: <?php echo $row['3']; ?>px; z-index: <?php echo $row['4']; ?>;">
<div class="w_skin_<?php echo $row['6']; ?>">
	<div class="widget-corner" id="widget-<?php echo $row['0']; ?>-handle">
		<div class="widget-headline"><h3>
		<?php echo $edit; ?>
		<span class="header-left">&nbsp;</span><span class="header-middle">My Guestbook(<span id="guestbook-size"><?php echo $count; ?></span>) <span id="guestbook-type" class="<?php echo $status; ?>"><img src="./web-gallery/images/groups/status_exclusive.gif" title="Friends only" alt="Friends only"/></span></span><span class="header-right">&nbsp;</span></h3>
		</div>	
	</div>
	<div class="widget-body">
		<div class="widget-content">
<div id="guestbook-wrapper" class="gb-public">
<ul class="guestbook-entries" id="guestbook-entry-container">
	<?php if($count == 0){ ?>
	<div id="guestbook-empty-notes">This guestbook has no entries.</div>
	<?php } else { ?>
			<?php 
			$i = 0;
			while ($row1 = mysql_fetch_assoc($sql)) {
				$i++;
				$userrow = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE id = '".$row1['userid']."' LIMIT 1"));
				if($my_id == $row1['userid']){
					$owneronly = "<img src=\"./web-gallery/images/myhabbo/buttons/delete_entry_button.gif\" id=\"gbentry-delete-".$row1['id']."\" class=\"gbentry-delete\" style=\"cursor:pointer\" alt=\"\"/><br/>";
				} elseif($user_row['id'] == $my_id) {
					$owneronly = "<img src=\"./web-gallery/images/myhabbo/buttons/delete_entry_button.gif\" id=\"gbentry-delete-".$row1['id']."\" class=\"gbentry-delete\" style=\"cursor:pointer\" alt=\"\"/><br/>";
				} else {
					$owneronly = "";
				}
				if(IsUserOnline($row1['userid'])){ $useronline = "online"; } else { $useronline = "offline"; }
				printf("	<li id=\"guestbook-entry-%s\" class=\"guestbook-entry\">
		<div class=\"guestbook-author\">
			<img src=\"http://www.habbo.co.uk/habbo-imaging/avatarimage?figure=%s&direction=2&head_direction=2&gesture=sml&size=s\" alt=\"%s\" title=\"%s\"/>
		</div>
			<div class=\"guestbook-actions\">
					$owneronly
			</div>
		<div class=\"guestbook-message\">
			<div class=\"%s\">
				<a href=\"./user_profile.php?id=%s\">%s</a>
			</div>
			<p>%s</p>
		</div>
		<div class=\"guestbook-cleaner\">&nbsp;</div>
		<div class=\"guestbook-entry-footer metadata\">%s</div>
	</li>",$row1['id'], $userrow['figure'], $userrow['name'], $userrow['name'], $useronline, $userrow['id'], $userrow['name'], bbcode_format(trim(nl2br(stripslashes($row1['message'])))), $userrow['time']);
			}
	} ?>
</ul></div>
<?php if($edit_mode == false){ ?>
	<div class="guestbook-toolbar clearfix">
	<a href="#" class="new-button envelope-icon" id="guestbook-open-dialog">
	<b><span></span>Post new message</b><i></i>
	</a>
	</div>
<?php } ?>
<script type="text/javascript">	
	document.observe("dom:loaded", function() {
		var gb<?php echo $row['0']; ?> = new GuestbookWidget('17570', '<?php echo $row['0']; ?>', 500);
		var editMenuSection = $('guestbook-privacy-options');
		if (editMenuSection) {
			gb<?php echo $row['0']; ?>.updateOptionsList('public');
		}
	});
</script>
		<div class="clear"></div>
		</div>
	</div>
</div>
</div>
<?php
	} elseif($subtype == "HighScoresWidget"){
	?>
<div class="movable widget HighScoresWidget" id="widget-<?php echo $row['0']; ?>" style=" left: <?php echo $row['2']; ?>px; top: <?php echo $row['3']; ?>px; z-index: <?php echo $row['4']; ?>;">
<div class="w_skin_<?php echo $row['6']; ?>">
	<div class="widget-corner" id="widget-<?php echo $row['0']; ?>-handle">
		<div class="widget-headline"><h3><?php echo $edit; ?><span class="header-left">&nbsp;</span><span class="header-middle">HIGH SCORES</span><span class="header-right">&nbsp;</span></h3>
		</div>	
	</div>
	<div class="widget-body">
		<div class="widget-content">
	<?php
	$bbsql = mysql_query("SELECT * FROM users WHERE id = '".$user_row['id']."' LIMIT 1");
	$bbrow = mysql_fetch_assoc($bbsql);
	if($bbrow['bb_playedgames'] == "0"){
		echo "You have not played any games yet.";
	}else{ ?>
	<table>
			<tr colspan="2">
				<th>Battle Ball</a></th>
			</tr>
			<tr>
				<td>Games played</td>
				<td><?php echo $bbrow['bb_playedgames']; ?></td>
			</tr>

			<tr>
				<td>Total score</td>
				<td><?php echo $bbrow['bb_totalpoints']; ?></td>
			</tr>
	</table>
	<?php } ?>
		<div class="clear"></div>
		</div>
	</div>
</div>
</div>
	<?php
	} elseif($subtype == "FriendsWidget"){ 
	$sql = mysql_query("SELECT * FROM messenger_friendships WHERE userid = '".$user_row['id']."' OR friendid = '".$user_row['id']."'");
	$count = mysql_num_rows($sql);
	?>
<div class="movable widget FriendsWidget" id="widget-<?php echo $row['0']; ?>" style=" left: <?php echo $row['2']; ?>px; top: <?php echo $row['3']; ?>px; z-index: <?php echo $row['4']; ?>;">
<div class="w_skin_<?php echo $row['6']; ?>">
	<div class="widget-corner" id="widget-<?php echo $row['0']; ?>-handle">
		<div class="widget-headline"><h3><?php echo $edit; ?><span class="header-left">&nbsp;</span><span class="header-middle">My Friends (<?php echo $count; ?>)</span><span class="header-right">&nbsp;</span></h3>
		</div>	
	</div>
	<div class="widget-body">
		<div class="widget-content">

<div id="avatar-list-search">
<input type="text" style="float:left;" id="avatarlist-search-string"/>
<a class="new-button" style="float:left;" id="avatarlist-search-button"><b>Search</b><i></i></a>
</div>
<br clear="all"/>

<div id="avatarlist-content">

<?php
$bypass = true;
$widgetid = $row['0'];
include('./myhabbo/avatarlist_friendsearchpaging.php');
?>

<script type="text/javascript">
document.observe("dom:loaded", function() {
	window.widget<?php echo $row['0']; ?> = new FriendsWidget('<?php echo $user_row['id']; ?>', '<?php echo $row['0']; ?>');
});
</script>

</div>
		<div class="clear"></div>
		</div>
	</div>
</div>
</div>
<?php
	} elseif($subtype == "TraxPlayerWidget"){ ?>
		<div class="movable widget TraxPlayerWidget" id="widget-<?php echo $row['0']; ?>" style=" left: <?php echo $row['2']; ?>px; top: <?php echo $row['3']; ?>px; z-index: <?php echo $row['4']; ?>;">
<div class="w_skin_<?php echo $row['6']; ?>">
	<div class="widget-corner" id="widget-<?php echo $row['0']; ?>-handle">
		<div class="widget-headline"><h3><?php echo $edit; ?><span class="header-left">&nbsp;</span><span class="header-middle">TRAXPLAYER</span><span class="header-right">&nbsp;</span></h3>
		</div>	
	</div>
	<div class="widget-body">
		<div class="widget-content">
<?php 
if($row['8'] == ""){ $songselected = false; }else{ $songselected = true; }
if($edit_mode == true){ ?>
<div id="traxplayer-content" style="text-align: center;">
	<img src="./web-gallery/images/traxplayer/player.png"/>
</div>

<div id="edit-menu-trax-select-temp" style="display:none">
    <select id="trax-select-options-temp">
    <option value="">- Choose song -</option>
	<?php
	$mysql = mysql_query("SELECT * FROM furniture WHERE ownerid = '".$user_row['id']."'");
	$i = 0;
	while($machinerow = mysql_fetch_assoc($mysql)){
		$i++;
		$sql = mysql_query("SELECT * FROM soundmachine_songs WHERE machineid = '".$machinerow['id']."'");
		$n = 0;
		while($songrow = mysql_fetch_assoc($sql)){
			$n++;
			if($songrow['id'] <> ""){ echo "		<option value=\"".$songrow['id']."\">".trim(nl2br(stripslashes($songrow['title'])))."</option>\n"; }
		}
	} ?>
    </select>

</div>
<?php }elseif($songselected == false){ ?>
You do not have a selected Trax song.
<?php }else{
$sql1 = mysql_query("SELECT * FROM soundmachine_songs WHERE id = '".$row['8']."' LIMIT 1");
$songrow1 = mysql_fetch_assoc($sql); ?>
<div id="traxplayer-content" style="text-align:center;"></div>
<embed type="application/x-shockwave-flash"
src="<?php echo $path; ?>web-gallery/flash/traxplayer/traxplayer.swf" name="traxplayer" quality="high"
base="<?php echo $path; ?>web-gallery/flash/traxplayer/" allowscriptaccess="always" menu="false"
wmode="transparent" flashvars="songUrl=<?php echo $path; ?>myhabbo/trax_song.php?songId=<?php echo $row['8']; ?>&amp;sampleUrl=http://images.habbohotel.com/dcr/hof_furni//mp3/" height="66" width="210" />
<?php } ?>

		<div class="clear"></div>
		</div>
	</div>
</div>
</div>
<?php
		} elseif($subtype == "BadgesWidget"){
	$sql = mysql_query("SELECT * FROM users_badges WHERE userid = '".$user_row['id']."' ORDER BY badgeid ASC");
	$count = mysql_num_rows($sql);
	?>
<div class="movable widget BadgesWidget" id="widget-<?php echo $row['0']; ?>" style=" left: <?php echo $row['2']; ?>px; top: <?php echo $row['3']; ?>px; z-index: <?php echo $row['4']; ?>;">
<div class="w_skin_<?php echo $row['6']; ?>">
	<div class="widget-corner" id="widget-<?php echo $row['0']; ?>-handle">
		<div class="widget-headline"><h3><?php echo $edit; ?><span class="header-left">&nbsp;</span><span class="header-middle">Badges</span><span class="header-right">&nbsp;</span></h3>
		</div>	
	</div>
	<div class="widget-body">
		<div class="widget-content">
    <div id="badgelist-content">
	<?php
	if($count == 0){
		echo "You don't have any badges.";
	}else{
	?>
    <?php
	$bypass1 = true;
	include('./myhabbo/badgelist_badgepaging.php');
	?>
	<?php } ?>

    </div>
		<div class="clear"></div>
		</div>
	</div>

</div>
</div>
	<?php
	}
}
}

if($found_profile !== true){

$info = mysql_query("SELECT * FROM users WHERE name = '".$searchname."' LIMIT 1") or die(mysql_error());
$userdata = mysql_fetch_assoc($info);
$valid = mysql_num_rows($info);

	if($valid > 0){

	mysql_query("INSERT INTO cms_homes_stickers (userid,type,subtype,x,y,z,skin) VALUES ('".$userdata['id']."','2','1','25','25','5','defaultskin')") or die(mysql_error());
?>
	<div class="movable widget FriendsWidget" id="widget-<?php echo $row['0']; ?>" style=" left: <?php echo $row['2']; ?>px; top: <?php echo $row['3']; ?>px; z-index: <?php echo $row['4']; ?>;">
<?php echo "<div class=\"w_skin_defaultskin\">
	<div class=\"widget-corner\" id=\"widget-".$row['id']."-handle\">
		<div class=\"widget-headline\"><h3>" . $edit . "
<span class=\"header-left\">&nbsp;</span><span class=\"header-middle\">MY ".strtoupper($shortname)."</span><span class=\"header-right\">&nbsp;</span></h3>
		</div>
	</div>
	<div class=\"widget-body\">
		<div class=\"widget-content\">
	<div class=\"profile-info\">

		<div class=\"name\" style=\"float: left\">
			<span class=\"name-text\">".$userdata['name']."</span>
		</div>

		<br class=\"clear\" />";

			if(IsUserOnline($userdata['id'])){ echo "<img alt=\"online\" src=\"./web-gallery/images/myhabbo/habbo_online_anim.gif\" />"; } else { echo "<img alt=\"offline\" src=\"./web-gallery/images/myhabbo/habbo_offline.gif\" />"; }

		echo "<div class=\"birthday text\">
			Created on:
		</div>
		<div class=\"birthday date\">
			".$userdata['hbirth']."
		</div>
		<div>";
		$groupbadge = GetUserGroupBadge($userdata['id']);
		$badge = GetUserBadge($userdata['name']);

		if($groupbadge !== false){
		echo "<a href='group_profile.php?id=".GetUserGroup($userdata['id'])."'><img src='./habbo-imaging/badge-fill/".$groupbadge.".gif'></a>";
		}

		if($badge !== false){
        	echo "<img src=\"http://images.habbohotel.co.uk/c_images/album1584/".$badge.".gif\" /></a>";
		}
echo "
        </div>
	</div>
	<div class=\"profile-figure\">
			<img alt=\"".$userdata['name']."\" src=\"http://www.habbo.co.uk/habbo-imaging/avatarimage?figure=".$userdata['figure']."&size=b&direction=4&head_direction=4&gesture=sml\" />
	</div>
	<div class=\"profile-motto\">
		".stripslashes($userdata['mission'])."
		<div class=\"clear\"></div>
	</div>
	<br clear=\"all\" style=\"display: block; height: 1px\"/>
    <div id=\"profile-tags-panel\">
    <div id=\"profile-tag-list\">
<div id=\"profile-tags-container\">\n";

$get_tags = mysql_query("SELECT * FROM cms_tags WHERE ownerid = '".$userdata['id']."' ORDER BY id LIMIT 20") or die(mysql_error());
$rows = mysql_num_rows($get_tags);

	$num = mysql_num_rows($get_tags);
	if($num > 0){

		if($userdata['id'] == $my_id && $logged_in){
			while ($row = mysql_fetch_assoc($get_tags)){


				printf("    <span class=\"tag-search-rowholder\">
        <a href=\"tags.php?tag=%s\" class=\"tag-search-link tag-search-link-%s\"
        >%s</a><img border=\"0\" class=\"tag-delete-link tag-delete-link-%s\" onMouseOver=\"this.src='./web-gallery/images/buttons/tags/tag_button_delete_hi.gif'\" onMouseOut=\"this.src='./web-gallery/images/buttons/tags/tag_button_delete.gif'\" src=\"./web-gallery/images/buttons/tags/tag_button_delete.gif\"
        /></span>", $row['tag'], $row['tag'], $row['tag'], $row['tag']);
			}
		} elseif($logged_in){
			while ($row = mysql_fetch_assoc($get_tags)){


				printf("    <span class=\"tag-search-rowholder\">
        <a href=\"tags.php?tag=%s\" class=\"tag-search-link tag-search-link-%s\"
        >%s</a><img border=\"0\" class=\"tag-add-link tag-add-link-%s\" onMouseOver=\"this.src='./web-gallery/images/buttons/tags/tag_button_add_hi.gif'\" onMouseOut=\"this.src='./web-gallery/images/buttons/tags/tag_button_add.gif'\" src=\"./web-gallery/images/buttons/tags/tag_button_add.gif\"
        /></span>", $row['tag'], $row['tag'], $row['tag'], $row['tag']);
			}
		} else {
			while ($row = mysql_fetch_assoc($get_tags)){


				printf("    <span class=\"tag-search-rowholder\">
        <a href=\"tags.php?tag=%s\" class=\"tag-search-link tag-search-link-%s\"
        >%s</a></span>", $row['tag'], $row['tag'], $row['tag'], $row['tag']);
			}
		}

	} else {
		echo "No tags";
	}

echo "\n    <img id=\"tag-img-added\" border=\"0\" src=\"./web-gallery/images/buttons/tags/tag_button_added.gif\" style=\"display:none\"/>
</div>

<script type=\"text/javascript\">
    document.observe(\"dom:loaded\", function() {
        TagHelper.setTexts({
            buttonText: \"OK\",
            tagLimitText: \"You\'ve reached the tag limit - delete one of your tags if you want to add a new one.\"
        });
    });
</script>
    </div>
<div id=\"profile-tags-status-field\">
 <div style=\"display: block;\">
  <div class=\"content-red\">
   <div class=\"content-red-body\">
    <span id=\"tag-limit-message\"><img src=\"./web-gallery/images/register/icon_error.gif\"/> You've reached the tag limit - delete one of your tags if you want to add a new one.</span>
    <span id=\"tag-invalid-message\"><img src=\"./web-gallery/images/register/icon_error.gif\"/> Invalid tag</span>
   </div>
  </div>
 <div class=\"content-red-bottom\">
  <div class=\"content-red-bottom-body\"></div>
 </div>
 </div>
</div>";


if($userdata['id'] == $my_id){
        echo "<div class=\"profile-add-tag\">
            <input type=\"text\" id=\"profile-add-tag-input\" maxlength=\"30\"/><br clear=\"all\"/>
            <a href=\"#\" class=\"new-button\" style=\"float:left;margin:5px 0 0 0;\" id=\"profile-add-tag\"><b>Add tag</b><i></i></a>
        </div>";
}
    echo "</div>
    <script type=\"text/javascript\">
		document.observe(\"dom:loaded\", function() {
			new ProfileWidget('21063711', '21063711', {
				headerText: \"Are you sure?\",
				messageText: \"Are you sure you want to ask <strong\>".$userdata['name']."</strong\> to be your friend?\",
				buttonText: \"OK\",
				cancelButtonText: \"Cancel\"
			});
		});
	</script>
		<div class=\"clear\"></div>
		</div>
	</div>
</div></div>";
	}
}
?>
				</div>
			</div>
			<div id="mypage-ad">
    <div class="habblet ">
<div class="ad-container">
<?php if(IsHCMember($user_row['id'])){ ?>
&nbsp;
<?php } else { ?>
<a href="club.php"><img src="./web-gallery/album1/hc_habbohome_banner_holo.png" width="160" height="600" id="galleryImage" border="0" alt="hc habbohome banner holo"></a>
<?php } ?>
</div>
    </div>
			</div>

		</div>
	</div>
</div>

<script language="JavaScript" type="text/javascript">
initEditToolbar();
initMovableItems();
document.observe("dom:loaded", initDraggableDialogs);
</script>


<div id="edit-save" style="display:none;"></div>
    </div>
<div id="footer">
	<p><a href="index.php" target="_self">Homepage</a> | <a href="./disclaimer.php" target="_self">Terms of Service</a> | <a href="./privacy.php" target="_self">Privacy Policy</a></p>
	<?php /*@@* DO NOT EDIT OR REMOVE THE LINE BELOW WHATSOEVER! *@@*/ ?>
	<p>Powered by HoloCMS &copy 2008 Meth0d.<br />HABBO is a registered trademark of Sulake Corporation. All rights reserved to their respective owner(s).</p>
	<?php /*@@* DO NOT EDIT OR REMOVE THE LINE ABOVE WHATSOEVER! *@@*/ ?>
</div></div>

</div>

<div id="edit-menu" class="menu">
	<div class="menu-header">
		<div class="menu-exit" id="edit-menu-exit"><img src="./web-gallery/images/dialogs/menu-exit.gif" alt="" width="11" height="11" /></div>
		<h3>Edit</h3>
	</div>
	<div class="menu-body">
		<div class="menu-content">
			<form action="#" onsubmit="return false;">
				<div id="edit-menu-skins">
	<select id="edit-menu-skins-select">
			<option value="1" id="edit-menu-skins-select-defaultskin">Default</option>
			<option value="6" id="edit-menu-skins-select-goldenskin">Golden</option>
			<option value="3" id="edit-menu-skins-select-metalskin">Metal</option>
			<option value="5" id="edit-menu-skins-select-notepadskin">Notepad</option>
			<option value="2" id="edit-menu-skins-select-speechbubbleskin">Speech Bubble</option>
			<option value="4" id="edit-menu-skins-select-noteitskin">Stickie Note</option>
<?php if(IsHCMember($my_id)){ ?>
			<option value="8" id="edit-menu-skins-select-hc_pillowskin">HC Bling</option>
			<option value="7" id="edit-menu-skins-select-hc_machineskin">HC Scifi</option>
<?php } ?>
<?php if($user_rank > 5){ ?>
			<option value="9" id="edit-menu-skins-select-nakedskin">Staff - Naked Skin</option>
<?php } ?>
	</select>
				</div>
				<div id="edit-menu-stickie">
					<p>Warning! If you click 'Remove', the note will be permanently deleted.</p>
				</div>
				<div id="rating-edit-menu">
					<input type="button" id="ratings-reset-link"
						value="Reset rating" />
				</div>
				<div id="highscorelist-edit-menu" style="display:none">
					<select id="highscorelist-game">
						<option value="">Select game</option>
						<option value="1">Battle Ball: Rebound!</option>
						<option value="2">SnowStorm</option>
						<option value="0">Wobble Squabble</option>
					</select>
				</div>
				<div id="edit-menu-remove-group-warning">
					<p>This item belongs to another user. If you remove it, it will return to their inventory.</p>
				</div>
				<div id="edit-menu-gb-availability">
					<select id="guestbook-privacy-options">
						<option value="private">Private</option>
						<option value="public">Public</option>
					</select>
				</div>
				<div id="edit-menu-trax-select">
					<select id="trax-select-options"></select>
				</div>
				<div id="edit-menu-remove">
					<input type="button" id="edit-menu-remove-button" value="Remove" />
				</div>
			</form>
			<div class="clear"></div>
		</div>
	</div>
	<div class="menu-bottom"></div>
</div>

<script language="JavaScript" type="text/javascript">
Event.observe(window, "resize", function() { if (editMenuOpen) closeEditMenu(); }, false);
Event.observe(document, "click", function() { if (editMenuOpen) closeEditMenu(); }, false);
Event.observe("edit-menu", "click", Event.stop, false);
Event.observe("edit-menu-exit", "click", function() { closeEditMenu(); }, false);
Event.observe("edit-menu-remove-button", "click", handleEditRemove, false);
Event.observe("edit-menu-skins-select", "click", Event.stop, false);
Event.observe("edit-menu-skins-select", "change", handleEditSkinChange, false);
Event.observe("guestbook-privacy-options", "click", Event.stop, false);
Event.observe("guestbook-privacy-options", "change", handleGuestbookPrivacySettings, false);
Event.observe("trax-select-options", "click", Event.stop, false);
Event.observe("trax-select-options", "change", handleTraxplayerTrackChange, false);
</script>

<div class="cbb topdialog" id="guestbook-form-dialog">
	<h2 class="title dialog-handle">Edit Guestbook entry</h2>
	
	<a class="topdialog-exit" href="#" id="guestbook-form-dialog-exit">X</a>
	<div class="topdialog-body" id="guestbook-form-dialog-body">
<div id="guestbook-form-tab">
<form method="post" id="guestbook-form">
    <p>
        Note: the message length must not exceed 200 characters
        <input type="hidden" name="ownerId" value="<?php echo $user_row['id']; ?>" />
	</p>
	<div>
	    <textarea cols="15" rows="5" name="message" id="guestbook-message"></textarea>
    <script type="text/javascript">
        bbcodeToolbar = new Control.TextArea.ToolBar.BBCode("guestbook-message");
        bbcodeToolbar.toolbar.toolbar.id = "bbcode_toolbar";
        var colors = { "red" : ["#d80000", "Red"],
            "orange" : ["#fe6301", "Orange"],
            "yellow" : ["#ffce00", "Yellow"],
            "green" : ["#6cc800", "Green"],
            "cyan" : ["#00c6c4", "Cyan"],
            "blue" : ["#0070d7", "Blue"],
            "gray" : ["#828282", "Gray"],
            "black" : ["#000000", "Black"]
        };
        bbcodeToolbar.addColorSelect("Color", colors, true);
    </script>
<div id="linktool">
    <div id="linktool-scope">
        <label for="linktool-query-input">Create link:</label>
        <input type="radio" name="scope" class="linktool-scope" value="1" checked="checked"/>Habbos
        <input type="radio" name="scope" class="linktool-scope" value="2"/>Rooms
        <input type="radio" name="scope" class="linktool-scope" value="3"/>Groups
    </div>
    <input id="linktool-query" type="text" name="query" value=""/>
    <a href="#" class="new-button" id="linktool-find"><b>Find</b><i></i></a>
    <div class="clear" style="height: 0;"><!-- --></div>
    <div id="linktool-results" style="display: none">
    </div>
    <script type="text/javascript">
        linkTool = new LinkTool(bbcodeToolbar.textarea);
    </script>
</div>
    </div>

	<div class="guestbook-toolbar clearfix">
		<a href="#" class="new-button" id="guestbook-form-cancel"><b>Cancel</b><i></i></a>
		<a href="#" class="new-button" id="guestbook-form-preview"><b>Preview</b><i></i></a>	
	</div>
</form>
</div>
<div id="guestbook-preview-tab">&nbsp;</div>
	</div>
</div>	
<div class="cbb topdialog" id="guestbook-delete-dialog">
	<h2 class="title dialog-handle">Delete entry</h2>
	
	<a class="topdialog-exit" href="#" id="guestbook-delete-dialog-exit">X</a>
	<div class="topdialog-body" id="guestbook-delete-dialog-body">
<form method="post" id="guestbook-delete-form">
	<input type="hidden" name="entryId" id="guestbook-delete-id" value="" />
	<p>Are you sure you want to delete this entry?</p>
	<p>
		<a href="#" id="guestbook-delete-cancel" class="new-button"><b>Cancel</b><i></i></a>
		<a href="#" id="guestbook-delete" class="new-button"><b>Delete</b><i></i></a>
	</p>
</form>
	</div>
</div>	
					
<script type="text/javascript">
HabboView.run();
</script>

<?php echo $analytics; ?>
</body>
</html>

<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
} elseif($error){
$cored = true;
include('./error.php');
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
} else {
$pagename = "User is Banned";
include('templates/community/subheader.php');
include('templates/community/header.php');
?>

<div id="container">
	<div id="content" style="position: relative" class="clearfix">
    <div id="column1" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix red ">

							<h2 class="title">User is Banned
							</h2>
						<div id="notfound-content" class="box-content">
    <p class="error-text">Sorry, but the page you were looking for is currently unavailable, because this user has been <b>banned</b>. Please try again later.</p> <img id="error-image" src="./web-gallery/v2/images/error.gif" />
    <p class="error-text">Please use the 'Back' button to get back to where you started.</p>
</div>


					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

</div>
<div id="column2" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix green ">

							<h2 class="title">Were you looking for...
							</h2>
						<div id="notfound-looking-for" class="box-content">
    <p><b>A friend's group or personal page?</b><br/>
    See if it is listed on the <a href="community.php">Community</a> page.</p>

    <p><b>Rooms that rock?</b><br/>
    Browse the <a href="community.php">Recommended Rooms</a> list.</p>

    <p><b>What other users are in to?</b><br/>
    Check out the <a href="tags.php">Top Tags</a> list.</p>

     <p><b>How to get Credits?</b><br/>
    Have a look at the <a href="credits.php">Credits</a> page.</p>
</div>


					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

</div>

<?php
include('templates/community/footer.php');
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>