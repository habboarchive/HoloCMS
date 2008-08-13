<?php
include('../core.php');

mysql_query("UPDATE users SET noob='0' WHERE id='".$my_id."' LIMIT 1") or die(mysql_error());
?>