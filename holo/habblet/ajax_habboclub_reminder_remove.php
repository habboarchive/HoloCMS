<?php
include('../core.php');

mysql_query("UPDATE users SET hc_before='0' WHERE id='".$my_id."' LIMIT 1");

?>