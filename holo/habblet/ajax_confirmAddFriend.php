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
$sql = mysql_query("SELECT name FROM users WHERE id = '".$id."' LIMIT 1");
$row = mysql_fetch_assoc($sql);
$name = $row['name']; ?>
<p>
Are you sure you want to add <?php echo $name; ?> to your friend list?
</p>

<p>
<a href="#" class="new-button done"><b>Cancel</b><i></i></a>
<a href="#" class="new-button add-continue"><b>Continue</b><i></i></a></p>