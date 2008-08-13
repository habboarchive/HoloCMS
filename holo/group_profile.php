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

// Search function
if(isset($_POST['searchString'])){
	$searchString = addslashes($_POST['searchString']);
	$check = mysql_query("SELECT id FROM groups_details WHERE name LIKE '".$searchString."' LIMIT 1") or die(mysql_error());
	$found = mysql_num_rows($check);
	if($found > 0){
		$tmp = mysql_fetch_assoc($check);
		header("Location: group_profile.php?id=" . $tmp['id']);
		exit;
	}
}

if(isset($_GET['id']) && is_numeric($_GET['id'])){

	$check = mysql_query("SELECT * FROM groups_details WHERE id = '".$_GET['id']."' LIMIT 1");
	$exists = mysql_num_rows($check);

	if($exists > 0){

		$groupid = $_GET['id'];

		$error = false;
		$groupdata = mysql_fetch_assoc($check);

		$pagename = $groupdata['name'];
		$ownerid = $groupdata['ownerid'];

		$members = mysql_evaluate("SELECT COUNT(*) FROM groups_memberships WHERE groupid = '".$groupid."' AND is_pending = '0'");

		$check = mysql_query("SELECT * FROM groups_memberships WHERE userid = '".$my_id."' AND groupid = '".$groupid."' AND is_pending = '0' LIMIT 1");
		$is_member = mysql_num_rows($check);

		if($is_member > 0 && $logged_in){

			$is_member = true;
			$my_membership = mysql_fetch_assoc($check);
			$member_rank = $my_membership['member_rank'];

		} else {

			$is_member = false;

		}

	} else {

		$error = true;

	}

} else {

	$error = true;

}


if(isset($_GET['do']) && $_GET['do'] == "edit" && $logged_in){

	if($is_member && $member_rank > 1){

		$edit_mode = true;

		$check = mysql_query("SELECT * FROM cms_homes_group_linker WHERE userid = '".$my_id."' LIMIT 1") or die(mysql_error());
		$linkers = mysql_num_rows($check);

		if($linkers > 0){

			mysql_query("UPDATE cms_homes_group_linker SET active = '1', groupid = '".$groupid."' WHERE userid = '".$my_id."' LIMIT 1") or die(mysql_error());

		} else {

			mysql_query("INSERT INTO cms_homes_group_linker (userid,groupid,active) VALUES ('".$my_id."','".$groupid."','1')") or die(mysql_error());

		}

	} else {

		header("location:group_profile.php?do=bounce&id=".$groupid."");
		$edit_mode = false;

	}

} else {

	$edit_mode = false;

}

if(!$error){

	$body_id = "viewmode";

	if($edit_mode){

		$body_id = "editmode";

	}

} else {

	$body_id = "home";

}

$pageid = "profile";

if($groupdata['type'] !== "1" && $is_member !== true){
	// If the group type is NOT exclusive/moderated, we have to delete any pending requests
	// this user has, simply because there's no longer need to put the user in the waiting list.
	$remove_pending = mysql_query("DELETE FROM groups_memberships WHERE is_pending = '1' AND userid = '".$my_id."' AND groupid = '".$groupid."' LIMIT 1") or die(mysql_error());
}

$viewtools = "	<div class=\"myhabbo-view-tools\">\n";

if($logged_in && !$is_member && $groupdata['type'] !== "2" && $my_membership['is_pending'] !== "1"){ $viewtools = $viewtools . "<a href=\"joingroup.php?groupId=".$groupid."\" id=\"join-group-button\">"; if($groupdata['type'] == "0" || $groupdata['type'] == "3"){ $viewtools = $viewtools . "Join"; } else { $viewtools = $viewtools . "Request membership"; } $viewtools = $viewtools . "</a>"; }
if($logged_in && $my_membership['is_current'] !== "1" && $is_member){ $viewtools = $viewtools . "<a href=\"#\" id=\"select-favorite-button\">Make favourite</a>\n"; }
if($logged_in && $my_membership['is_current'] == "1" && $is_member){ $viewtools = $viewtools . "<a href=\"#\" id=\"deselect-favorite-button\">Remove favourite</a>"; }
if($logged_in && $is_member && $my_id !== $ownerid){ $viewtools = $viewtools . "<a href=\"leavegroup.php?groupId=".$groupid."\" id=\"leave-group-button\">Leave group</a>\n"; }

$viewtools = $viewtools . "	</div>\n";


$bg_fetch = mysql_query("SELECT data FROM cms_homes_stickers WHERE type = '4' AND groupid = '".$groupid."' LIMIT 1");
$bg_exists = mysql_num_rows($bg_fetch);

	if($bg_exists < 1){ // if there's no background override for this user set it to the standard
		$bg = "b_bg_pattern_abstract2";
	} else {
		$bg = mysql_fetch_array($bg_fetch);
		$bg = "b_" . $bg[0];
	}

if(!$error){
include('templates/community/hsubheader.php');
include('templates/community/header.php');
mysql_query("UPDATE groups_details SET views = views+'1' WHERE id='".$groupid."' LIMIT 1");
?>

<div id="container">
	<div id="content" style="position: relative" class="clearfix">
    <div id="mypage-wrapper" class="cbb blue">
<div class="box-tabs-container box-tabs-left clearfix">
	<?php if($member_rank > 1 && !$edit_mode){ ?><a href="#" id="myhabbo-group-tools-button" class="new-button dark-button edit-icon" style="float:left"><b><span></span>Edit</b><i></i></a><?php } ?>
	<?php if(!$edit_mode){ echo $viewtools; } ?>
    <h2 class="page-owner">
<?php echo stripslashes($groupdata['name']); ?>&nbsp;
<?php if($groupdata['type'] == "2"){ ?><img src='./web-gallery/images/status_closed_big.gif' alt='Closed Group' title='Closed Group'><?php } ?>
<?php if($groupdata['type'] == "1"){ ?><img src='./web-gallery/images/status_exclusive_big.gif' alt='Moderated Group' title='Moderated Group'><?php } ?></h2>
</h2>
    <ul class="box-tabs">
        <li class="selected"><a href="group_profile.php?id=<?php echo $_GET['id']; ?>">Front Page</a><span class="tab-spacer"></span></li>
        <li><a href="group_forum.php?id=<?php echo $_GET['id']; ?>">Discussion Forum <?php if($groupdata['pane'] > 0) { ?><img src="http://images.habbohotel.nl/habboweb/23_deebb3529e0d9d4e847a31e5f6fb4c5b/9/web-gallery/images/grouptabs/privatekey.png"><?php } ?></a><span class="tab-spacer"></span></li>
    </ul>
</div>
	<div id="mypage-content">
<?php 	if($edit_mode == true){ ?>
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
<?php 	} ?>
		<div id="mypage-bg" class="<?php echo $bg; ?>">
			<div id="playground-outer">
				<div id="playground">

<?php
$get_em = mysql_query("SELECT id,type,x,y,z,data,skin,subtype FROM cms_homes_stickers WHERE groupid = '".$groupid."' and type < 4 LIMIT 200") or die(mysql_error());

while ($row = mysql_fetch_array($get_em, MYSQL_NUM)) {

	switch($row[1]){
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
</div>",$row[6],$row[2],$row[3],$row[4],$row[0],$row[6],$edit,bbcode_format(nl2br(stripslashes($row[5]))));
	} elseif($type == "sticker"){
	printf("<div class=\"movable sticker s_%s\" style=\"left: %spx; top: %spx; z-index: %s\" id=\"sticker-%s\">\n%s\n</div>", $row[5], $row[2], $row[3], $row[4], $row[0], $edit);
	} elseif($type == "widget"){

		switch($row[7]){
		case "1": $subtype = "Profilewidget"; break;
		case "3": $subtype = "MemberWidget"; break;
		}

		if($subtype == "Profilewidget"){

		$found_profile = true;

		echo "<div class=\"movable widget GroupInfoWidget\" id=\"widget-".$row[0]."\" style=\" left: ".$row[2]."px; top: ".$row[3]."px; z-index: ".$row[4].";\">
<div class=\"w_skin_".$row[6]."\">
	<div class=\"widget-corner\" id=\"widget-".$row[0]."-handle\">
		<div class=\"widget-headline\"><h3><span class=\"header-left\">&nbsp;</span><span class=\"header-middle\">Group information</span><span class=\"header-right\">".$edit."</span></h3>
		</div>
	</div>
	<div class=\"widget-body\">
		<div class=\"widget-content\">";
			?>

<div class=\"group-info-icon\"><img src='./habbo-imaging/<?php if(!isset($_GET['x'])) { echo "badge-fill/".$groupdata['badge'].".gif"; }else{ echo "badge.php?badge=".$groupdata['badge'].""; } ?>' /></div>
<?php echo "
<h4>".stripslashes($groupdata['name'])."</h4>

<p>
Created: <strong>".$groupdata['created']."</strong>
</p>

<p>
<strong>".$members."</strong> members
</p>";
if($groupdata['roomid'] != 0 OR $groupdata['roomid'] != "" OR $groupdata['roomid'] != " ") {
$sql = mysql_query("SELECT name FROM rooms WHERE id='".$groupdata['roomid']."' LIMIT 1");
$roominfo = mysql_fetch_assoc($sql); ?>
<?php if($groupdata['roomid'] <> 0){ ?><p><a href="client.php?forwardId=2&amp;roomId=<?php echo $groupdata['roomid']; ?>" onclick="HabboClient.roomForward(this, '<?php echo $groupdata['roomid']; ?>', 'private'); return false;" target="client" class="group-info-room"><?php echo htmlspecialchars(stripslashes($roominfo['name'])); ?></a></p><?php } ?>
<?php
}

echo "\n<div class=\"group-info-description\">".stripslashes($groupdata['description'])."</div>

<script type=\"text/javascript\">
    document.observe(\"dom:loaded\", function() {
        new GroupInfoWidget('".$groupid."', '".$row[0]."');
    });
</script>

		<div class=\"clear\"></div>
		</div>
	</div>
</div>
</div>";
		} elseif($subtype == "MemberWidget"){
			echo "<div class=\"movable widget MemberWidget\" id=\"widget-".$row[0]."\" style=\" left: ".$row[2]."px; top: ".$row[3]."px; z-index: ".$row[4].";\">
<div class=\"w_skin_".$row[6]."\">
	<div class=\"widget-corner\" id=\"widget-".$row[0]."-handle\">
		<div class=\"widget-headline\"><h3><span class=\"header-left\">&nbsp;</span><span class=\"header-middle\">Members of this group (<span id=\"avatar-list-size\">".$members."</span>)</span><span class=\"header-right\">".$edit."</span></h3>
		</div>
	</div>
	<div class=\"widget-body\">
		<div class=\"widget-content\">\n";

//<div id=\"avatar-list-search\">
//<input type=\"text\" style=\"float:left;\" id=\"avatarlist-search-string\"/>
//<a class=\"new-button\" style=\"float:left;\" id=\"avatarlist-search-button\"><b>Search</b><i></i></a>
//</div>
echo "<br clear=\"all\"/>

<div id=\"avatarlist-content\">

<div class=\"avatar-widget-list-container\">
<ul id=\"avatar-list-list\" class=\"avatar-widget-list\">";

$get_admins = mysql_query("SELECT userid,is_current,member_rank FROM groups_memberships WHERE groupid = '".$groupid."' AND is_pending = '0' AND member_rank = '2'") or die(mysql_error());

while($membership = mysql_fetch_assoc($get_admins)){

	$userrow = mysql_query("SELECT id,name,figure,hbirth FROM users WHERE id = '".$membership['userid']."' LIMIT 1") or die(mysql_error());
	$found = mysql_num_rows($userrow);

	if($found > 0){
		$userrow = mysql_fetch_assoc($userrow);

		echo "<li id=\"avatar-list-".$groupid."-".$userrow['id']."\" title=\"".$userrow['name']."\">
<div class=\"avatar-list-open\">
	<a href=\"#\" id=\"avatar-list-open-link-".$groupid."-".$userrow['id']."\" class=\"avatar-list-open-link\"></a>
</div>
<div class=\"avatar-list-avatar\">
	<img src=\"http://www.habbo.co.uk/habbo-imaging/avatarimage?figure=".$userrow['figure']."&size=s&direction=2&head_direction=2&gesture=sml\" alt=\"\" />
</div>
<h4>
	<a href=\"user_profile.php?name=".$userrow['name']."\">".$userrow['name']."</a>
</h4>
<p class=\"avatar-list-birthday\">
	".$userrow['hbirth']."
</p>
<p>";
if($userrow['id'] == $ownerid){
echo "<img src=\"./web-gallery/images/groups/owner_icon.gif\" alt=\"\" class=\"avatar-list-groupstatus\" />";
} else {
echo "<img src=\"./web-gallery/images/groups/administrator_icon.gif\" alt=\"\" class=\"avatar-list-groupstatus\" />";
}
if($membership['is_current'] == "1"){
echo "<img src=\"./web-gallery/images/groups/favourite_group_icon.gif\" alt=\"Favorite\" class=\"avatar-list-groupstatus\" />";
}
echo "</p>
</li>";
	}
}

$get_users = mysql_query("SELECT userid,is_current,member_rank FROM groups_memberships WHERE groupid = '".$groupid."' AND is_pending = '0' AND member_rank = '1'") or die(mysql_error());

while($membership = mysql_fetch_assoc($get_users)){

	$userrow = mysql_query("SELECT id,name,figure,hbirth FROM users WHERE id = '".$membership['userid']."' LIMIT 1") or die(mysql_error());
	$found = mysql_num_rows($userrow);

	if($found > 0){
		$userrow = mysql_fetch_assoc($userrow);

		echo "<li id=\"avatar-list-".$groupid."-".$userrow['id']."\" title=\"".$userrow['name']."\">
<div class=\"avatar-list-open\">
	<a href=\"#\" id=\"avatar-list-open-link-".$groupid."-".$userrow['id']."\" class=\"avatar-list-open-link\"></a>
</div>
<div class=\"avatar-list-avatar\">
	<img src=\"http://www.habbo.co.uk/habbo-imaging/avatarimage?figure=".$userrow['figure']."&size=s&direction=2&head_direction=2&gesture=sml\" alt=\"\" />
</div>
<h4>
	<a href=\"user_profile.php?name=".$userrow['name']."\">".$userrow['name']."</a>
</h4>
<p class=\"avatar-list-birthday\">
	".$userrow['hbirth']."
</p>
<p>";
if($membership['is_current'] == "1"){
echo "<img src=\"./web-gallery/images/groups/favourite_group_icon.gif\" alt=\"Favorite\" class=\"avatar-list-groupstatus\" />";
}
echo "</p>
</li>";
	}
}

echo "</ul>

<div id=\"avatar-list-info\" class=\"avatar-list-info\">
<div class=\"avatar-list-info-close-container\"><a href=\"#\" class=\"avatar-list-info-close\"></a></div>
<div class=\"avatar-list-info-container\"></div>
</div>

</div>

<div id=\"avatar-list-paging\">
<input type=\"hidden\" id=\"pageNumber\" value=\"1\"/>
<input type=\"hidden\" id=\"totalPages\" value=\"1\"/>
</div>

<script type=\"text/javascript\">
document.observe(\"dom:loaded\", function() {
	window.widget".$row[0]." = new MemberWidget('".$groupid."', '".$row[0]."');
});
</script>

</div>
		<div class=\"clear\"></div>
		</div>
	</div>
</div>
</div>";
		}

	}

}

if($found_profile !== true){

	mysql_query("INSERT INTO cms_homes_stickers (userid,groupid,type,subtype,x,y,z,skin) VALUES ('-1','".$groupid."','2','1','25','25','5','defaultskin')") or die(mysql_error());

		echo "<div class=\"movable widget GroupInfoWidget\" id=\"widget-".$row[0]."\" style=\" left: 25px; top: 25px; z-index: 5;\">
<div class=\"w_skin_defaultskin\">
	<div class=\"widget-corner\" id=\"widget-1994412-handle\">
		<div class=\"widget-headline\"><h3><span class=\"header-left\">&nbsp;</span><span class=\"header-middle\">Group information</span><span class=\"header-right\">&nbsp;</span></h3>
		</div>
	</div>
	<div class=\"widget-body\">
		<div class=\"widget-content\">

<div class=\"group-info-icon\"><img src='./habbo-imaging/badge-fill/".$groupdata['badge'].".gif' /></div>

<h4>".stripslashes($groupdata['name'])."</h4>

<p>
Created: <strong>".$groupdata['created']."</strong>
</p>

<p>
<strong>".$members."</strong> members
</p>\n";

// <p><a href=\"http://www.habbo.nl/client?forwardId=2&amp;roomId=13303122\" onclick=\"roomForward(this, '13303122', 'private'); return false;\" target=\"client\" class=\"group-info-room\">The church of bobbaz</a></p>

echo "\n<div class=\"group-info-description\">".stripslashes($groupdata['description'])."</div>

<script type=\"text/javascript\">
    document.observe(\"dom:loaded\", function() {
        new GroupInfoWidget('55918', '1478728');
    });
</script>

		<div class=\"clear\"></div>
		</div>
	</div>
</div>
</div>";

}
?>
				</div>
			</div>
			<div id="mypage-ad">
    <div class="habblet ">
<div class="ad-container">
&nbsp;
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

<?php if($edit_mode){ ?>
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
<?php if(IsHCMember($my_id)){ ?>
			<option value="8" id="edit-menu-skins-select-hc_pillowskin">HC Bling</option>
			<option value="7" id="edit-menu-skins-select-hc_machineskin">HC Scifi</option>
<?php } ?>
<?php if($user_rank > 5){ ?>
			<option value="9" id="edit-menu-skins-select-nakedskin">Staff - Naked Skin</option>
<?php } ?>
			<option value="3" id="edit-menu-skins-select-metalskin">Metal</option>
			<option value="5" id="edit-menu-skins-select-notepadskin">Notepad</option>
			<option value="2" id="edit-menu-skins-select-speechbubbleskin">Speech Bubble</option>
			<option value="4" id="edit-menu-skins-select-noteitskin">Stickie Note</option>
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
						<option value="private">Members only</option>
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
<?php } else { ?>
<div class="cbb topdialog" id="guestbook-form-dialog">
	<h2 class="title dialog-handle">Edit guestbook entry</h2>

	<a class="topdialog-exit" href="#" id="guestbook-form-dialog-exit">X</a>
	<div class="topdialog-body" id="guestbook-form-dialog-body">
<div id="guestbook-form-tab">
<form method="post" id="guestbook-form">
    <p>
        Note: messages cannot be more than 200 characters
        <input type="hidden" name="ownerId" value="441794" />
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
            "gray" : ["#828282", "Grey"],
            "black" : ["#000000", "Black"]
        };
        bbcodeToolbar.addColorSelect("Colours", colors, true);
    </script>
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
<div id="group-tools" class="bottom-bubble">
	<div class="bottom-bubble-t"><div></div></div>
	<div class="bottom-bubble-c">
<h3>Edit group</h3>

<ul>
	<li><a href="group_profile.php?id=<?php echo $groupid; ?>&do=edit" id="group-tools-style">Modify page</a></li>
	<?php if($ownerid == $my_id){ ?><li><a href="#" id="group-tools-settings">Settings</a></li><?php } ?>
	<li><a href="#" id="group-tools-badge">Badge</a></li>
	<li><a href="#" id="group-tools-members">Members</a></li>
</ul>

	</div>
	<div class="bottom-bubble-b"><div></div></div>
</div>

<div class="cbb topdialog black" id="dialog-group-settings">

	<div class="box-tabs-container">
<ul class="box-tabs">
	<li class="selected" id="group-settings-link-group"><a href="#">Group settings</a><span class="tab-spacer"></span></li>
	<li id="group-settings-link-forum"><a href="#">Forum Settings</a><span class="tab-spacer"></span></li>
	<li id="group-settings-link-room"><a href="#">Room Settings</a><span class="tab-spacer"></span></li>
</ul>
</div>

	<a class="topdialog-exit" href="#" id="dialog-group-settings-exit">X</a>
	<div class="topdialog-body" id="dialog-group-settings-body">
<p style="text-align:center"><img src="./web-gallery/images/progress_bubbles.gif" alt="" width="29" height="6" /></p>
	</div>
</div>

<script language="JavaScript" type="text/javascript">
Event.observe("dialog-group-settings-exit", "click", function(e) {
    Event.stop(e);
    closeGroupSettings();
}, false);
</script><div class="cbb topdialog black" id="group-memberlist">

	<div class="box-tabs-container">
<ul class="box-tabs">
	<li class="selected" id="group-memberlist-link-members"><a href="#">Members</a><span class="tab-spacer"></span></li>
	<li id="group-memberlist-link-pending"><a href="#">Pending members</a><span class="tab-spacer"></span></li>
</ul>
</div>

	<a class="topdialog-exit" href="#" id="group-memberlist-exit">X</a>
	<div class="topdialog-body" id="group-memberlist-body">
<div id="group-memberlist-members-search" class="clearfix" style="display:none">

    <a id="group-memberlist-members-search-button" href="#" class="new-button"><b>Search</b><i></i></a>
    <input type="text" id="group-memberlist-members-search-string"/>
</div>
<div id="group-memberlist-members" style="clear: both"></div>
<div id="group-memberlist-members-buttons" class="clearfix">
	<a href="#" class="new-button group-memberlist-button-disabled" id="group-memberlist-button-give-rights"><b>Give rights</b><i></i></a>
	<a href="#" class="new-button group-memberlist-button-disabled" id="group-memberlist-button-revoke-rights"><b>Revoke rights</b><i></i></a>
	<a href="#" class="new-button group-memberlist-button-disabled" id="group-memberlist-button-remove"><b>Remove</b><i></i></a>
	<a href="#" class="new-button group-memberlist-button" id="group-memberlist-button-close"><b>Close</b><i></i></a>
</div>
<div id="group-memberlist-pending" style="clear: both"></div>
<div id="group-memberlist-pending-buttons" class="clearfix">
	<a href="#" class="new-button group-memberlist-button-disabled" id="group-memberlist-button-accept"><b>Accept</b><i></i></a>
	<a href="#" class="new-button group-memberlist-button-disabled" id="group-memberlist-button-decline"><b>Reject</b><i></i></a>
	<a href="#" class="new-button group-memberlist-button" id="group-memberlist-button-close2"><b>Close</b><i></i></a>
</div>
	</div>
</div>
<?php } ?>

<script type="text/javascript">
HabboView.run();
</script>

</body>
</html>

<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
} else {
$pagename = "Page not found";
include('templates/community/subheader.php');
include('templates/community/header.php');
?>

<div id="container">
	<div id="content" style="position: relative" class="clearfix">
    <div id="column1" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix red ">

							<h2 class="title">Page not found!
							</h2>
						<div id="notfound-content" class="box-content">
    <p class="error-text">Sorry, but the page you were looking for was not found.</p> <img id="error-image" src="./web-gallery/v2/images/error.gif" />
    <p class="error-text">Please use the 'Back' button to get back to where you started.</p>
    <p class="error-text"><b>Search for group</b></p>
    <?php if(isset($searchString)){ echo "<p class=\"error-text\">Sorry, but no results were found for <strong>'".$searchString."'.</strong></p>"; } ?>
    <p class="error-text">
	<form method='post'>
		Group Name:<br />
		<input type='text' name='searchString' maxlength='25' size='25' value='<?php echo $_POST['searchString']; ?>'>
		<input type='submit' class='submit' value='Submit'>
	</form>
    </p>
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