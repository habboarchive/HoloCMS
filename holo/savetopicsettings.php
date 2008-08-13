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
$topicname = trim(addslashes(htmlspecialchars($_POST['topicName'])));
$topic_closed = $_POST['topicClosed'];
$topic_sticky = $_POST['topicSticky'];

if($topic_closed == 0 && $topic_sticky == 0){
$type = 1;
} elseif($topic_closed == 1 && $topic_sticky == 0){
$type = 2;
} elseif($topic_closed == 0 && $topic_sticky == 1){
$type = 3;
} elseif($topic_closed == 1 && $topic_sticky == 1){
$type = 4;
} else {
exit;
}

if(is_numeric($topicid)){
	if($user_rank > 5){
		$check = mysql_query("SELECT title, type FROM cms_forum_threads WHERE id = '".$topicid."' LIMIT 1");
		$exists = mysql_num_rows($check);
		if($exists > 0){
			mysql_query("UPDATE cms_forum_threads SET type = '".$type."', title = '".$topicname."' WHERE id = '".$topicid."' LIMIT 1") or die(mysql_error());
			echo "<center><br /><br /><b>The thread has been updated successfully.</b><br /><a href='viewthread.php?thread=".$topicid."&sp=JumpToLast'>Proceed</a><br /><br /><br /></center>";
		} else {
			exit;
		}
	} else {
		exit;
	}
} else {
	exit;
}

?>