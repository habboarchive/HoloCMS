<?php
/*===================================================+
|| # HoloCMS - Website and Content Management System
|+===================================================+
|| # Copyright © 2008 Meth0d. All rights reserved.
|| # http://www.meth0d.org
|+===================================================+
|| # HoloCMS is provided "as is" and comes without
|| # warrenty of any kind. HoloCMS is free software!
|+===================================================+
|| # Friends Management by Yifan Lu
|| # www.obbahhotel.com
|+===================================================*/

include('../core.php');
$pagesize = $_POST['pageSize'];


if(isset($_POST['friendList'])){
	$friends = $_POST['friendList'];
    foreach($friends as $val)
    {
        $sql = mysql_query("DELETE FROM messenger_friendships WHERE userid = '".$my_id."' AND friendid = '".$val."'");
        $sql = mysql_query("DELETE FROM messenger_friendships WHERE friendid = '".$my_id."' AND userid = '".$val."'");
    }
} elseif(isset($_POST['friendId'])){
	$friendid = $_POST['friendId'];
	$sql = mysql_query("DELETE FROM messenger_friendships WHERE userid = '".$my_id."' AND friendid = '".$friendid."'");
	$sql = mysql_query("DELETE FROM messenger_friendships WHERE friendid = '".$my_id."' AND userid = '".$friendid."'");
} else{
	echo "Unknown error!";
}

header("location:ajax_friendmanagement.php?pageNumber=1&pageSize=".$pagesize); exit;
?>
