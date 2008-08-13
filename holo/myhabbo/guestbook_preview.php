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
$message = $_POST['message']; 

$row = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE id = '".$my_id."' LIMIT 1"));
?>

<ul class="guestbook-entries">
	<li id="guestbook-entry--1" class="guestbook-entry">
		<div class="guestbook-author">
			<img src="http://www.habbo.co.uk/habbo-imaging/avatarimage?figure=<?php echo $row['figure']; ?>&direction=2&head_direction=2&gesture=sml&size=s" alt="<?php echo $row['name'] ?>" title="<?php echo $row['name'] ?>"/>
		</div>
		<div class="guestbook-message">
			<div class="online">
				<a href="user_profile.php?id=<?php echo $my_id; ?>"><?php echo $row['name'] ?></a>
			</div>
			<p><?php echo bbcode_format(trim(nl2br(stripslashes($message)))); ?></p>
		</div>
		<div class="guestbook-cleaner">&nbsp;</div>
		<div class="guestbook-entry-footer metadata"></div>
	</li>
</ul>

<div class="guestbook-toolbar clearfix">
<a href="#" class="new-button" id="guestbook-form-continue"><b>Continue editing</b><i></i></a>
<a href="#" class="new-button" id="guestbook-form-post"><b>Add entry</b><i></i></a>	
</div>