<?php
include('../core.php');

$entryid = $_POST['entryId'];
$widgetid = $_POST['widgetId'];

mysql_query("DELETE FROM cms_guestbook WHERE id = '".$entryid."' LIMIT 1");

?>