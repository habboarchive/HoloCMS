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

$ownerid = $_POST['ownerId'];
$message = stripslashes(mysql_real_escape_string(htmlspecialchars($_POST['message'])));
$sql = mysql_query("SELECT NOW()");
$date = mysql_result($sql, 0);

mysql_query("INSERT INTO cms_guestbook (userid,message,time,type,type_id) VALUES ('".$my_id."','".$message."','".$date."','home','".$ownerid."')");

$row = mysql_fetch_assoc(mysql_query("SELECT * FROM cms_guestbook WHERE userid = '".$my_id."' ORDER BY id DESC LIMIT 1"));
$userrow = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE id = '".$row['userid']."'"));

?>

	<li id="guestbook-entry-<?php echo $row['id']; ?>" class="guestbook-entry">
		<div class="guestbook-author">
			<img src="http://www.habbo.co.uk/habbo-imaging/avatarimage?figure=<?php echo $userrow['figure']; ?>&direction=2&head_direction=2&gesture=sml&size=s" alt="<?php echo $userrow['name'] ?>" title="<?php echo $userrow['name'] ?>"/>
		</div>
			<div class="guestbook-actions">
					<img src="./web-gallery/images/myhabbo/buttons/delete_entry_button.gif" id="gbentry-delete-<?php echo $row['id']; ?>" class="gbentry-delete" style="cursor:pointer" alt=""/>
					<br/>
			</div>
		<div class="guestbook-message">
			<div class="online">
				<a href="./user_profile.php?id=<?php echo $userrow['id']; ?>"><?php echo $userrow['name']; ?></a>
			</div>
			<p><?php echo bbcode_format(trim(nl2br(stripslashes($row['message'])))); ?></p>
		</div>
		<div class="guestbook-cleaner">&nbsp;</div>
		<div class="guestbook-entry-footer metadata"><?php echo $row['date']; ?></div>
	</li>