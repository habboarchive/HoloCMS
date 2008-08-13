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

$postid = $_POST['postId'];

if(is_numeric($postid)){
	$check = mysql_query("SELECT * FROM cms_forum_posts WHERE id = '".$postid."' LIMIT 1") or die(mysql_error());
	$exists = mysql_num_rows($check);
	if($exists > 0){
		$row = mysql_fetch_assoc($check);
		if($user_rank > 5 || $row['author'] == $name){
			mysql_query("DELETE FROM cms_forum_posts WHERE id = '".$row['id']."' LIMIT 1") or die(mysql_error());
			// recount the posts and update in DB
			$posts_left = mysql_evaluate("SELECT COUNT(*) FROM cms_forum_posts WHERE threadid = '".$row['threadid']."'");
			// get the real last post data
			if($posts_left > 0){
				$lastpost = mysql_query("SELECT author,date FROM cms_forum_posts WHERE threadid = '".$row['threadid']."' ORDER BY id DESC LIMIT 1") or die(mysql_error());
				$lastpost = mysql_fetch_assoc($lastpost);
			} else {
				$lastpost['author'] = "Noone";
				$lastpost['date'] = "Never";
			}
			$posts_count = $posts_left - 1;
			mysql_query("UPDATE cms_forum_threads SET posts = '".$posts_count."', lastpost_date = '".$lastpost['date']."', lastpost_author = '".$lastpost['author']."' WHERE id = '".$row['threadid']."' LIMIT 1") or die(mysql_error());
			if($posts_left > 0){
				echo "TOPIC_DELETED";
			} else { // if we found that there are no posts left [during recount], delete the thread as well
				mysql_query("DELETE FROM cms_forum_threads WHERE id = '".$row['threadid']."' LIMIT 1");
				echo "TOPIC_DELETED";
			}
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