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

$id = $_POST['messageId'];
$start = $_POST['start'];
$label = $_POST['label'];

$sql = mysql_query("SELECT NOW()");
$date = mysql_result($sql, 0);

$sql = mysql_query("SELECT * FROM cms_minimail WHERE id = '".$id."' LIMIT 1");
$row = mysql_fetch_assoc($sql);

mysql_query("INSERT INTO cms_help (username,ip,message,date,picked_up,subject,roomid) VALUES ('".$name."', '".$remote_ip."','Minimail Message: ".$row['message']."','".$date."','0','Reported Minimail Message: ".$row['subject']."','0')");
mysql_query("DELETE FROM messenger_friendships WHERE userid = '".$my_id."' AND friendid = '".$row['senderid']."' LIMIT 1");
mysql_query("DELETE FROM messenger_friendships WHERE userid = '".$row['senderid']."' AND friendid = '".$my_id."' LIMIT 1");
mysql_query("DELETE FROM cms_minimail WHERE id = '".$id."' LIMIT 1");

$bypass = "true";
$page = $label;
$startpage = $start;
$message = "Message reported sucessfully, and friend removed.";
include('loadMessage.php');
?>