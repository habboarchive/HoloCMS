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
|| # Parts by Yifan Lu
|| # www.obbahhotel.com
|+===================================================*/

include('../core.php');

$id = $_POST['accountId'];
$sql = mysql_query("SELECT * FROM messenger_friendships WHERE userid = '".$id."' AND friendid = '".$my_id."'");
$rows = mysql_num_rows($sql);
if($rows <> 0){
$error = 1;
$message = "This person is already your friend.";
}

$sql = mysql_query("SELECT * FROM messenger_friendships WHERE userid = '".$my_id."' AND friendid = '".$id."'");
$rows = mysql_num_rows($sql);
if($rows <> 0){
$error = 1;
$message = "This person is already your friend.";
}

$sql = mysql_query("SELECT * FROM messenger_friendrequests WHERE userid_from = '".$my_id."' AND userid_to = '".$id."'");
$rows = mysql_num_rows($sql);
if($rows <> 0){
$error = 1;
$message = "You already requested a friendship from this person.";
}

$sql = mysql_query("SELECT * FROM messenger_friendrequests WHERE userid_to = '".$my_id."' AND userid_from = '".$id."'");
$rows = mysql_num_rows($sql);
if($rows <> 0){
$error = 1;
$message = "This person already requested you to be their friend.";
}

if($id == $my_id){
$error = 1;
$message = "You cannot friend request yourself.";
}

if($error <> 1){
$sql = mysql_query("SELECT MAX(requestid) FROM messenger_friendrequests WHERE userid_to = '".$id."'");
$requestid = mysql_result($sql, 0);
$requestid = $requestid + 1;

mysql_query("INSERT INTO messenger_friendrequests (userid_from,userid_to,requestid) VALUES ('".$my_id."','".$id."','".$requestid."')");

$message = "Friend request has been sent successfully.";
} ?>

<div id="avatar-habblet-dialog-body" class="topdialog-body"><ul>
	<li><?php echo $message; ?></li>
</ul>


<p>
<a href="#" class="new-button done"><b>Done</b><i></i></a>
</p></div>