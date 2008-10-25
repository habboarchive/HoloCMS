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

if(HoloText(getContent('forum-enabled'), true) !== "1"){ header("Location: index.php"); exit; }

$threadid = $_GET['thread'];

if(!empty($threadid) && is_numeric($threadid)){
	$check = mysql_query("SELECT * FROM cms_forum_threads WHERE id = '".$threadid."' LIMIT 1") or die(mysql_error());
	$exists = mysql_num_rows($check);
	if($exists > 0){
		$thread = mysql_fetch_assoc($check);
		$valid_thread = true;
		mysql_query("UPDATE cms_forum_threads SET views = views + 1 WHERE id = '".$threadid."' LIMIT 1") or die(mysql_error());
	} else {
		header("Location: forum.php");
		exit;
	}
} else {
	header("Location: forum.php");
	exit;
}

$pagename = "Discussion - " . $thread['title'];
$pageid = "forum";
$body_id = "viewmode";
$page = $_GET['page'];

$posts = mysql_evaluate("SELECT COUNT(*) FROM cms_forum_posts WHERE threadid = '".$threadid."'");
$pages = ceil(($posts + 0) / 10);

if($page > $pages || $page < 1){
	$page = 1;
}


$group = mysql_query("SELECT forumid FROM cms_forum_posts WHERE threadid='".$_GET['thread']."'");
$info = mysql_fetch_assoc($group);
if(isset($info['forumid']) && is_numeric($info['forumid'])){

	$check = mysql_query("SELECT * FROM groups_details WHERE id = '".$info['forumid']."' LIMIT 1");
	$exists = mysql_num_rows($check);

	if($exists > 0){

		$groupid = $info['forumid'];

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


//if($posts < 1){
//	echo "<b>Internal Error</b><br />No posts found for this thread. Please double-check / repair your cms_forum_posts database table.";
//	exit;
//}

$key = 0;

if($_GET['sp'] == "JumpToLast"){
header("Location: viewthread.php?thread=".$threadid."&page=".$pages."#page-bottom");
exit;
}

switch($thread['type']){
	case 1: $topic_open = true; break;
	case 2: $topic_open = false; break;
	case 3: $topic_open = true; break;
	case 4: $topic_open = false; break;
}

if(!isset($topic_open)){
	exit;
}

include('templates/community/subheader.php');
include('templates/community/header.php');

?>

<div id="container">
	<div id="content" style="position: relative" class="clearfix">
    <div id="mypage-wrapper" class="cbb blue">
<div class="box-tabs-container box-tabs-left clearfix">
	<?php
	$sql = mysql_query("SELECT forumid FROM cms_forum_posts WHERE threadid='".$_GET['thread']."'");
	$row = mysql_fetch_assoc($sql);
	if($row['forumid'] != 0) {
	 if($member_rank > 1 && !$edit_mode){ ?><a href="group_profile.php?id=<?php echo $groupid; ?>&do=edit" class="new-button dark-button edit-icon" style="float:left"><b><span></span>Edit</b><i></i></a><?php } ?>
	<?php if(!$edit_mode){ echo $viewtools; }
	} ?>
	<div class="myhabbo-view-tools">
	</div>
    <h2 class="page-owner">
	<?php
	$sql = mysql_query("SELECT forumid FROM cms_forum_posts WHERE threadid='".$_GET['thread']."'");
	$row = mysql_fetch_assoc($sql);
	if($row['forumid'] != 0) {
 echo HoloText($groupdata['name']); ?>&nbsp;<?php
 if($groupdata['type'] == "2"){ ?><img src='./web-gallery/images/status_closed_big.gif' alt='Closed Group' title='Closed Group'><?php } ?>
<?php if($groupdata['type'] == "1"){ ?><img src='./web-gallery/images/status_exclusive_big.gif' alt='Moderated Group' title='Moderated Group'><?php } ?></h2>
<?php }else{ ?>
    	Discussion Board
	<?php } ?>
    </h2>
    <ul class="box-tabs">
		<?php if($row['forumid'] != 0) { ?>
		<li><a href="group_profile.php?id=<?php echo $groupid; ?>">Front Page</a><span class="tab-spacer"></span></li>
<?php	} ?>
        <li class="selected"><a href="<?php if($row['forumid'] != 0) { echo "group_"; } ?>forum.php<?php if($row['forumid'] != 0) { ?>?id=<?php echo $groupid; } ?>">Discussion Board</a><span class="tab-spacer"></span></li>
    </ul>
</div>
	<div id="mypage-content">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="content-1col">
            <tr>
                <td valign="top" style="width: 750px;" class="habboPage-col rightmost">
                    <div id="discussionbox">
<div id="group-postlist-container">

    <div class="postlist-header clearfix">
				<?php
				$sql = mysql_query("SELECT * FROM groups_details WHERE id='".$row['forumid']."' LIMIT 1");
				$row = mysql_fetch_assoc($sql);
				$asdf = "";
				$zxcv = "";
				if($row['topics'] == 0) {
					$asdf = "<a href=\"#\" id=\"create-post-message\" class=\"create-post-link verify-email\">Post reply</a>";
					$zxcv = "<a href=\"#\" class=\"quote-post-link verify-email\" id=\"quote-post-".$row['id']."-message\">Quote</a>";
				}elseif($row['topics'] == 1) {
					$check = mysql_query("SELECT * FROM groups_memberships WHERE userid='".$my_id."' AND groupid='".$_GET['id']."' AND is_pending <> '1' LIMIT 1");
					if(mysql_num_rows($check) > 0) { 
						$asdf = "<a href=\"#\" id=\"create-post-message\" class=\"create-post-link verify-email\">Post reply</a>";
						$zxcv = "<a href=\"#\" class=\"quote-post-link verify-email\" id=\"quote-post-".$row['id']."-message\">Quote</a>";
					}
				}elseif($row['topics'] == 2) {
					$check = mysql_query("SELECT * FROM groups_memberships WHERE userid='".$my_id."' AND groupid='".$_GET['id']."' AND member_rank='2' AND is_pending <> '1' LIMIT 1");
					if(mysql_num_rows($check) > 0) { 
						$asdf = "<a href=\"#\" id=\"create-post-message\" class=\"create-post-link verify-email\">Post reply</a>";
						$zxcv = "<a href=\"#\" class=\"quote-post-link verify-email\" id=\"quote-post-".$row['id']."-message\">Quote</a>";
					}
				}
				?>
                <?php if($topic_open && $logged_in){ ?><?php echo $asdf ?><?php } elseif($logged_in) { ?><span class="topic-closed"><img src="./web-gallery/images/groups/status_closed.gif" title="Closed Thread"> Closed Thread</span><?php } ?>
                <input type="hidden" id="email-verfication-ok" value="1"/>
				<?php
				$hid = mysql_query("SELECT * FROM groups_memberships WHERE userid='".$my_id."' AND groupid='".$groupid."' AND member_rank='2' LIMIT 1");
				if(mysql_num_rows($hid) > 0) { ?><a href="#" id="edit-topic-settings" class="edit-topic-settings-link">Moderation Tools &raquo;</a>
                <input type="hidden" id="settings_dialog_header" value="Moderation Tools"/>
				<?php
				}elseif($user_rank > 5){ ?><a href="#" id="edit-topic-settings" class="edit-topic-settings-link">Moderation Tools &raquo;</a>
                <input type="hidden" id="settings_dialog_header" value="Moderation Tools"/><?php } ?>
        <div class="page-num-list">
	<input type="hidden" id="current-page" value="<?php echo $page; ?>"/>
    View page:
<?php
	for ($i = 1; $i <= $pages; $i++){
		if($page == $i){
			echo $i . "\n";
		} else {
			echo "<a href=\"viewthread.php?thread=".$threadid."&page=" . $i . "\" class=\"topiclist-page-link\">" . $i . "</a>\n";
		}
	} 
?>
    </div>
</div>

<table border="0" cellpadding="0" cellspacing="0" width="100%" class="group-postlist-list" id="group-postlist-list">

<?php
// Post view handler & echoer

$query_min = ($page * 10) - 10;

if($query_min < 0){ // Page 1
$query_min = 0;
}

$get_em = mysql_query("SELECT * FROM cms_forum_posts WHERE threadid = '".$threadid."' ORDER BY id ASC LIMIT ".$query_min.", 10") or die(mysql_error());
$dynamic_id = 0;

while($row = mysql_fetch_assoc($get_em)){

	$dynamic_id++;

	if(IsEven($dynamic_id)){
		$oddeven = "odd";
	} else {
		$oddeven = "even";
	}

	$userquery = mysql_query("SELECT * FROM users WHERE name = '".$row['author']."' LIMIT 1");
	$userdata = mysql_fetch_assoc($userquery);

	$userid = $userdata['id'];

	echo "<tr class=\"post-list-index-".$oddeven."\">
	<a id='post-".$row['id']."'>
	<td class=\"post-list-row-container\">
		<a href=\"user_profile.php?name=".$userdata['name']."\" class=\"post-list-creator-link post-list-creator-info\">".$userdata['name']."</a><br />&nbsp;\n";
            if(IsUserOnline($userid)){ echo "<img alt=\"Online\" src=\"./web-gallery/images/myhabbo/habbo_online_anim.gif\" />"; } else { echo "<img alt=\"Offline\" src=\"./web-gallery/images/myhabbo/habbo_offline.gif\" />"; }
		echo "<div class=\"post-list-posts post-list-creator-info\">Messages: ".$userdata['postcount']."</div>
		<div class=\"clearfix\">
            <div class=\"post-list-creator-avatar\"><img src=\"http://www.habbo.co.uk/habbo-imaging/avatarimage?figure=".$userdata['figure']."&size=b&direction=2&head_direction=2&gesture=sml\" alt=\"".$userdata['name']."\" /></div><div class=\"post-list-group-badge\">";
		if(GetUserGroup($userid) !== false){       
                	echo "<a href=\"group_profile.php?id=".GetUserGroup($userid)."\"><img src='./habbo-imaging/badge-fill/".GetUserGroupBadge($userid).".gif' /></a>";
		}
            echo "</div>
		<div class=\"post-list-avatar-badge\">";
		if(GetUserBadge($userid) !== false){
			echo "<img src=\"".$cimagesurl.$badgesurl.GetUserBadge($userid).".gif\" />";
		}
		echo "</div>
        </div>
        <div class=\"post-list-motto post-list-creator-info\">".trim(HoloText($userdata['mission']))."</div>
	</td>
	<td class=\"post-list-message\" valign=\"top\" colspan=\"2\">";
			if($topic_open == true && $logged_in){
echo "                ".$zxcv;
			}
			if($user_rank > 5 || $my_id == $userdata['id'] && $logged_in){
	                echo "<a href=\"#\" class=\"edit-post-link verify-email\" id=\"edit-post-".$row['id']."-message\">Modify</a>";
			}
        echo "<span class=\"post-list-message-header\">"; 
	if($dynamic_id !== 1 || $page > 1){
		echo "RE: ";
 	}
	echo HoloText($thread['title'])."</span><br />
        <span class=\"post-list-message-time\">".$row['date']."</span>
        <div class=\"post-list-report-element\">";
			if($user_rank > 5 || $my_id == $userdata['id'] && $logged_in){
                		echo "<a href=\"#\" id=\"delete-post-".$row['id']."\" class=\"delete-button delete-post\"></a>";
			}
			if($my_id !== $userdata['id'] && $logged_in){
				echo "        <div class=\"post-list-report-element\">\n                <a href=\"./iot/go.php?do=report&post=".$row['id']."&page=".$page."\" class=\"create-report-button\" title=\"Report this post\" target=\"habbohelp\" onclick=\"openOrFocusHelp(this); return false\"></a>\n        </div>";
			}
echo "        </div>";

		if(!empty($row['edit_date']) && !empty($row['edit_author'])){
		echo "\n<br /><br /><font size='1'><strong>Last modified ".$row['edit_date']." by ".$row['edit_author']."</strong></font>";
		}

echo "        <div class=\"post-list-content-element\">";

            echo bbcode_format(trim(nl2br(HoloText($row['message']))))."
                <input type=\"hidden\" id=\"".$row['id']."-message\" value=\"".HoloText($row['message'])."\" />
        </div>
        <div>
        </div>
	</td>
</tr>";
}

?>




<tr id="new-post-entry-message" style="display:none;">
	<td class="new-post-entry-label"><div class="new-post-entry-label" id="new-post-entry-label">Message:</div></td>
	<td colspan="2">
		<table border="0" cellpadding="0" cellspacing="0" style="margin: 5px; width: 98%;">
		<tr>
		<td>
		<input type="hidden" id="edit-type"/>
		<input type="hidden" id="post-id"/>
        <a href="#" class="preview-post-link" id="post-form-preview">Preview &raquo;</a>
        <input type="hidden" id="spam-message" value="Spam-alarm!"/>
		<textarea id="post-message" class="new-post-entry-message" rows="5" name="message" ></textarea>
    <script type="text/javascript">
        bbcodeToolbar = new Control.TextArea.ToolBar.BBCode("post-message");
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
        bbcodeToolbar.addColorSelect("Color", colors, false);
    </script>
	    <br /><br />
        <a id="post-form-cancel" class="new-button red-button cancel-icon" href="#"><b><span></span>Cancel</b><i></i></a>
        <a id="post-form-save" class="new-button green-button save-icon" href="#"><b><span></span>Save</b><i></i></a>
        </td>
        </tr>
        </table>
	</td>
</tr></table>
<div id="new-post-preview" style="display:none;">
</div>

    <div class="postlist-footer clearfix">
		<?php if($topic_open && $logged_in){ ?><?php echo $asdf ?></a><?php } elseif($logged_in){ ?><span class="topic-closed"><img src="./web-gallery/images/groups/status_closed.gif" title="Closed Thread"> Closed Thread</span><?php } else { echo "</a>You must be logged in to reply or post new threads."; } ?>
    </a><div class="page-num-list">
    View page:
<?php
	for ($i = 1; $i <= $pages; $i++){
		if($page == $i){
			echo $i . "\n";
		} else {
			echo "<a href=\"viewthread.php?thread=".$threadid."&page=" . $i . "\" class=\"topiclist-page-link\">" . $i . "</a>\n";
		}
	} 
?>
    </div>
</div>
</div>

<a id='page-bottom'>

<script type="text/javascript" language="JavaScript">
L10N.put("myhabbo.discussion.error.topic_name_empty", "Topic title may not be empty");
Discussions.initialize("DiscussionBoard", "forum.php", "<?php echo $threadid; ?>");
</script>
                    </div>
					
                </td>
                <td style="width: 4px;"></td>
                <td valign="top" style="width: 164px;">
    <div class="habblet ">
    
    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

<script type="text/javascript">
	Event.observe(window, "load", observeAnim);
	document.observe("dom:loaded", initDraggableDialogs);
</script>
    </div>
<div id="footer">
	<p><a href="index.php" target="_self">Homepage</a> | <a href="./disclaimer.php" target="_self">Terms of Service</a> | <a href="./privacy.php" target="_self">Privacy Policy</a></p>
	<?php /*@@* DO NOT EDIT OR REMOVE THE LINE BELOW WHATSOEVER! *@@*/ ?>
	<p>Powered by HoloCMS &copy 2008 Meth0d.<br />HABBO is a registered trademark of Sulake Corporation. All rights reserved to their respective owner(s).</p>
	<?php /*@@* DO NOT EDIT OR REMOVE THE LINE ABOVE WHATSOEVER! *@@*/ ?>
</div></div>

</div>

	 
<div id="group-tools" class="bottom-bubble">
	<div class="bottom-bubble-t"><div></div></div>
	<div class="bottom-bubble-c">
<h3>Wijzig Groep</h3>

<ul>
	<li><a href="/groups/actions/startEditingSession/55918" id="group-tools-style">roflcopter</a></li>
	<li><a href="#" id="group-tools-settings">is</a></li>
	<li><a href="#" id="group-tools-badge">off</a></li>
	<li><a href="#" id="group-tools-members">limits</a></li>
</ul>

	</div>
	<div class="bottom-bubble-b"><div></div></div>
</div>

<div class="cbb topdialog black" id="dialog-group-settings">
	
	<div class="box-tabs-container">
<ul class="box-tabs">
	<li class="selected" id="group-settings-link-group"><a href="#">Groepsinstellingen</a><span class="tab-spacer"></span></li>
	<li id="group-settings-link-forum"><a href="#">Foruminstellingen</a><span class="tab-spacer"></span></li>
	<li id="group-settings-link-room"><a href="#">Kamersettings</a><span class="tab-spacer"></span></li>
</ul>
</div>

	<a class="topdialog-exit" href="#" id="dialog-group-settings-exit">X</a>
	<div class="topdialog-body" id="dialog-group-settings-body">
<p style="text-align:center"><img src="http://images.habbohotel.nl/habboweb/21_5527e6590eba8f3fb66348bdf271b5a2/17/web-gallery/images/progress_bubbles.gif" alt="" width="29" height="6" /></p>
	</div>
</div>	

<script language="JavaScript" type="text/javascript">
Event.observe("dialog-group-settings-exit", "click", function(e) {
    Event.stop(e);
    closeGroupSettings();
}, false);
</script><div class="cbb topdialog" id="postentry-verifyemail-dialog">
	<h2 class="title dialog-handle">Verifciation</h2>
	
	<a class="topdialog-exit" href="#" id="postentry-verifyemail-dialog-exit">X</a>
	<div class="topdialog-body" id="postentry-verifyemail-dialog-body">
	<p>Please verify yourself before posting.</p>
	<p class="clearfix">
		<a href="#" id="postentry-verifyemail-ok" class="new-button"><b>Cancel</b><i></i></a>
	</p>
	</div>
</div>	
<div class="cbb topdialog" id="postentry-delete-dialog">
	<h2 class="title dialog-handle">Delete Post</h2>
	
	<a class="topdialog-exit" href="#" id="postentry-delete-dialog-exit">X</a>
	<div class="topdialog-body" id="postentry-delete-dialog-body">
<form method="post" id="postentry-delete-form">
	<input type="hidden" name="entryId" id="postentry-delete-id" value="" />
	<p>Are you sure you want to delete this post? This cannot be reverted.</p>
	<p class="clearfix">
		<a href="#" id="postentry-delete-cancel" class="new-button"><b>Cancel</b><i></i></a>
		<a href="#" id="postentry-delete" class="new-button"><b>Proceed</b><i></i></a>
	</p>
</form>
	</div>
</div>	
					
<script type="text/javascript">
HabboView.run();
</script>

</body>
</html>