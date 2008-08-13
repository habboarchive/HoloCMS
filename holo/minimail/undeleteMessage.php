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

mysql_query("UPDATE cms_minimail SET deleted = '0' WHERE id = '".$id."'");

$bypass = "true";
$page = "inbox";
$message = "Message undeleted.";
include('loadMessage.php');
?>