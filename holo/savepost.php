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

include('core.php');
include('./includes/session.php');

if(getContent('forum-enabled') !== "1"){ header("Location: index.php"); exit; }

$message = addslashes(htmlspecialchars($_POST['message']));
$topicId = $_POST['topicId'];

if(!empty($topicId) && is_numeric($topicId)){
	$check = mysql_query("SELECT * FROM cms_forum_threads WHERE id = '".$topicId."' LIMIT 1") or die(mysql_error());
	$exists = mysql_num_rows($check);
	if($exists > 0){
		$thread = mysql_fetch_assoc($check);
		if($thread['type'] !== "2" && $thread['type'] !== "4"){
			$valid_thread = true;
		} else {
			$valid_thread = false;
		}
	} else {
		header("Location: forum.php");
		exit;
	}
} else {
	header("Location: forum.php");
	exit;
}

if($valid_thread == true){
	mysql_query("INSERT INTO cms_forum_posts (threadid,message,author,date) VALUES ('".$topicId."','".$message."','".$name."','".$date_full."')");
	mysql_query("UPDATE users SET postcount = postcount + 1 WHERE id = '".$my_id."' LIMIT 1") or die(mysql_error());
	mysql_query("UPDATE cms_forum_threads SET lastpost_author = '".$name."', lastpost_date = '".$date_full."', unix = '".strtotime('now')."', posts = posts + 1 WHERE id = '".$topicId."' LIMIT 1") or die(mysql_error());
}

if(empty($topicId) || !is_numeric($topicId)){ "&nbsp;"; exit; }

$posts = mysql_evaluate("SELECT COUNT(*) FROM cms_forum_posts WHERE threadid = '".$topicId."'");
$pages = ceil(($posts + 0) / 10);
$page = $pages;

if($page > $pages || $page < 1){
	$page = 1;
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

?>
<div id="group-postlist-container">

    <div class="postlist-header clearfix">
                <?php if($topic_open){ ?><a href="#" id="create-post-message" class="create-post-link verify-email">Post reply</a><?php } ?>
                <input type="hidden" id="email-verfication-ok" value="1"/>
                <?php if($user_rank > 5){ ?><a href="#" id="edit-topic-settings" class="edit-topic-settings-link">Moderation Tools &raquo;</a>
                <input type="hidden" id="settings_dialog_header" value="Moderation Tools"/><?php } ?>
        <div class="page-num-list">
	<input type="hidden" id="current-page" value="<?php echo $page; ?>"/>
    View page:
<?php
	for ($i = 1; $i <= $pages; $i++){
		if($page == $i){
			echo $i . "\n";
		} else {
			echo "<a href=\"viewthread.php?thread=".$topicId."&page=" . $i . "\" class=\"topiclist-page-link\">" . $i . "</a>\n";
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

$get_em = mysql_query("SELECT * FROM cms_forum_posts WHERE threadid = '".$topicId."' ORDER BY id ASC LIMIT ".$query_min.", 10") or die(mysql_error());
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
                	echo "<a href=\"group_profile.php?id=".GetUserGroup($userid)."\"><img src=\"./habbo-imaging/badge.php?badge=".GetUserGroupBadge($userid)."\" /></a>";
		}
            echo "</div>
		<div class=\"post-list-avatar-badge\">";
		if(GetUserBadge($userid) !== false){
			echo "<img src=\"http://images.habbohotel.co.uk/c_images/album1584/".GetUserBadge($userid).".gif\" />";
		}
		echo "</div>
        </div>
        <div class=\"post-list-motto post-list-creator-info\">".stripslashes($userdata['mission'])."</div>
	</td>
	<td class=\"post-list-message\" valign=\"top\" colspan=\"2\">
                <a href=\"#\" class=\"quote-post-link verify-email\" id=\"quote-post-".$row['id']."-message\">Quote</a>";
			if($user_rank > 5 || $my_id == $userdata['id']){
	                echo "<a href=\"#\" class=\"edit-post-link verify-email\" id=\"edit-post-".$row['id']."-message\">Modify</a>";
			}
        echo "<span class=\"post-list-message-header\">"; 
	if($dynamic_id !== 1 || $page > 1){
		echo "RE: ";
 	}
	echo stripslashes($thread['title'])."</span><br />
        <span class=\"post-list-message-time\">".$row['date']."</span>
        <div class=\"post-list-report-element\">";
			if($user_rank > 5 || $my_id == $userdata['id']){
                		echo "<a href=\"#\" id=\"delete-post-".$row['id']."\" class=\"delete-button delete-post\"></a>";
			}
			if($my_id !== $userdata['id']){
				echo "        <div class=\"post-list-report-element\">\n                <a href=\"./iot/go.php?do=report&post=".$row['id']."&page=".$page."\" class=\"create-report-button\" title=\"Report this post\" target=\"habbohelp\" onclick=\"openOrFocusHelp(this); return false\"></a>\n        </div>";
			}
echo "        </div>";

		if(!empty($row['edit_date']) && !empty($row['edit_author'])){
		echo "\n<br /><br /><font size='1'><strong>Last modified ".$row['edit_date']." by ".$row['edit_author']."</strong></font>";
		}

echo "        <div class=\"post-list-content-element\">";

            echo bbcode_format(trim(nl2br(stripslashes($row['message']))))."
                <input type=\"hidden\" id=\"".$row['id']."-message\" value=\"".stripslashes($row['message'])."\" />
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
		<?php if($topic_open){ ?><a href="#" id="create-post-message-lower" class="create-post-link verify-email">Post reply</a><?php } ?>
    </a><div class="page-num-list">
    View page:
<?php
	for ($i = 1; $i <= $pages; $i++){
		if($page == $i){
			echo $i . "\n";
		} else {
			echo "<a href=\"viewthread.php?thread=".$topicId."&page=" . $i . "\" class=\"topiclist-page-link\">" . $i . "</a>\n";
		}
	} 
?>
    </div>
</div>
</div>

<a id='page-bottom'>

<script type="text/javascript" language="JavaScript">
L10N.put("myhabbo.discussion.error.topic_name_empty", "Topic title may not be empty");
Discussions.initialize("DiscussionBoard", "forum.php", "<?php echo $topicId; ?>");
</script>
