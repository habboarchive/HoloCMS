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

$sql = mysql_query("SELECT * FROM cms_minimail WHERE id = '".$id."' LIMIT 1");
$row = mysql_fetch_assoc($sql);

if($row['senderid'] == $my_id){
	$error = 1;
	$message = "You can't report your own messages.";
}

if($error == 1){
?>
<ul class="error">
	<li><?php echo $message; ?></li>
</ul>

<p>
<a href="#" class="new-button cancel-report"><b>Cancel</b><i></i></a>
</p>
<?php
} else {
$sql = mysql_query("SELECT * FROM users WHERE id = '".$row['senderid']."' LIMIT 1");
$senderrow = mysql_fetch_assoc($sql);
?>
<p>
Are you sure you want to report the message <b><?php echo $row['subject']; ?></b> to the moderators and remove <b><?php echo $senderrow['name']; ?></b> from your friend list? You cant undo this.
</p>

<p>
<a href="#" class="new-button cancel-report"><b>Cancel</b><i></i></a>
<a href="#" class="new-button send-report"><b>Send report</b><i></i></a>
</p>
<?php } ?>