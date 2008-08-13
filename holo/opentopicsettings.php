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

include('./core.php');

if(getContent('forum-enabled') !== "1"){ header("Location: index.php"); exit; }
if(!session_is_registered(username)){ exit; }

$topicid = $_POST['topicId'];

if(is_numeric($topicid)){
$groupidget = mysql_query("SELECT forumid FROM cms_forum_threads WHERE id='".$topicid."'");
$group = mysql_fetch_assoc($groupidget);
$groupid = $group['forumid'];
$hid = mysql_query("SELECT groupid FROM groups_memberships WHERE userid='".$my_id."' AND groupid='".$groupid."' AND member_rank='2' LIMIT 1");
	if($user_rank > 5 OR mysql_num_rows($hid) > 0){
		$check = mysql_query("SELECT title, type FROM cms_forum_threads WHERE id = '".$topicid."' LIMIT 1");
		$exists = mysql_num_rows($check);
		if($exists > 0){
			$row = mysql_fetch_assoc($check);
		} else {
			exit;
		}
	} else {
		exit;
	}
} else {
	exit;
}

switch($row['type']){
	case 1: $topic_open = "true"; $topic_closed = "false"; $topic_normal = "true"; $topic_sticky = "false"; break;
	case 2: $topic_open = "false"; $topic_closed = "true"; $topic_normal = "true"; $topic_sticky = "false"; break;
	case 3: $topic_open = "true"; $topic_closed = "false"; $topic_normal = "false"; $topic_sticky = "true"; break;
	case 4: $topic_open = "false"; $topic_closed = "true"; $topic_normal = "false"; $topic_sticky = "true"; break;
}

if(!isset($topic_open)){
	exit;
}

?>

<form action="#" method="post" id="topic-settings-form">
	<div id="topic-name-area">
	    	<div class="topic-name-input">
	    		<span class="topic-name-text" id="topic_name_text">Topic: (max 30 characters)</span>
	    	</div>
	    	<div class="topic-name-input">
	    		<input type="text" size="40" maxlength="30" name="topic_name" id="topic_name" onKeyUp="GroupUtils.validateGroupElements('topic_name', 32, 'myhabbo.topic.name.max.length.exceeded');" value="<?php echo $row['title']; ?>"/>
			</div>
            <div id="topic-name-error"></div>
            <div id="topic_name_message_error" class="error"></div>
    </div>
	<div id="topic-type-area">
		<div class="topic-type-label">
			<span class="topic-type-label">Type:</span>
		</div>
	    <div class="topic-type-input">
	    	<input type="radio" name="topic_type" id="topic_open" value="0" <?php if($topic_open == "true"){ echo "checked=\"".$topic_open."\""; } ?> /> Open<br /><input type="radio" name="topic_sticky" id="topic_normal" value="0" <?php if($topic_normal == "true"){ echo "checked=\"".$topic_normal."\""; } ?>
              /> Normal
	    </div>
	    <div class="topic-type-input">
	    	 <input type="radio" name="topic_type" id="topic_closed" value="1" <?php if($topic_closed == "true"){ echo "checked=\"".$topic_closed."\""; } ?> /> Closed<br /><input type="radio" name="topic_sticky" id="topic_sticky" value="1" <?php if($topic_sticky == "true"){ echo "checked=\"".$topic_sticky."\""; } ?>
             /> Sticky topic
	    </div>
	</div>
	<br clear="all"/>
	<br clear="all"/>
	<div id="topic-button-area" class="topic-button-area">
		<a href="#" class="new-button cancel-topic-settings" id="cancel-topic-settings"><b>Cancel</b><i></i></a>
		<a href="#" class="new-button delete-topic" id="delete-topic"><b>Delete</b><i></i></a>
		<a href="#" class="new-button save-topic-settings" style="float:left; margin:0px;" id="save-topic-settings"><b>Save</b><i></i></a>
	</div>
</form>
<div class="clear"></div>