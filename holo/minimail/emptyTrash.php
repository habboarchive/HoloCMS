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

mysql_query("DELETE FROM cms_minimail WHERE deleted = '1' AND to_id = '".$my_id."'");
$bypass = "true";
$page = "trash";
$message = "The trash has been emptied. Good Job!";
include('loadMessage.php');

?>