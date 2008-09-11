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

if(getContent('forum-enabled') !== "1"){ header("Location: index.php"); exit; }

$message = addslashes(htmlspecialchars($_POST['message']));
$topicTitle = addslashes(htmlspecialchars($_POST['topicName']));

if(empty($topicTitle)){ echo "Topic title may not be blank"; exit; }

mysql_query("INSERT INTO cms_forum_threads (forumid,type,title,author,date,lastpost_author,lastpost_date,views,posts,unix) VALUES ('".$_POST['groupId']."','1','".$topicTitle."','".$name."','".$date_full."','".$name."','".$date_full."','0','0','".strtotime('now')."')") or die(mysql_error());
mysql_query("UPDATE users SET postcount = postcount + 1 WHERE id = '".$my_id."' LIMIT 1");

$check = mysql_query("SELECT id FROM cms_forum_threads WHERE forumid='".$_POST['groupId']."' ORDER BY id DESC LIMIT 1") or die(mysql_error());
$row = mysql_fetch_assoc($check);

$threadid = $row['id'];

mysql_query("INSERT INTO cms_forum_posts (forumid,threadid,message,author,date) VALUES ('".$_POST['groupId']."','".$threadid."','".$message."','".$name."','".$date_full."')");

echo "viewthread.php?thread=".$threadid."&page=1";

?>