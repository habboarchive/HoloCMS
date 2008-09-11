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

if(getContent('forum-enabled') !== "1"){ header("Location: index.php"); exit; }
if(!session_is_registered(username)){ exit; }

$topicid = $_POST['topicId'];

if(is_numeric($topicid)){
	if($user_rank > 5){
		$check = mysql_query("SELECT id FROM cms_forum_threads WHERE id = '".$topicid."' LIMIT 1");
		$exists = mysql_num_rows($check);
		if($exists > 0){
			mysql_query("DELETE FROM cms_forum_threads WHERE id = '".$topicid."' LIMIT 1");
			mysql_query("DELETE FROM cms_forum_posts WHERE threadid = '".$topicid."'");
			echo "SUCCESS";
			exit;
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