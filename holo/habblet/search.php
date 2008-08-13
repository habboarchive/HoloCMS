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

$query = $_GET['query'];
$scope = $_GET['scope'];
?>
	<ul>
		<li>Click on link below to insert it into the document</li>
<?php

if($scope == 1){
$i = 0;
$getem = mysql_query("SELECT * FROM users WHERE name LIKE '%".$query."%' LIMIT 10");

while ($row = mysql_fetch_assoc($getem)) {
	$i++;
	printf("<li><a href=\"#\" class=\"linktool-result\" type=\"habbo\"
	    	value=\"%s\" title=\"%s\">%s</a></li>", $row['id'], $row['name'], $row['name']);
}
} elseif($scope == 2){
$i = 0;
$getem = mysql_query("SELECT * FROM rooms WHERE name LIKE '%".$query."%' LIMIT 10");

while ($row = mysql_fetch_assoc($getem)) {
	$i++;
	printf("<li><a href=\"#\" class=\"linktool-result\" type=\"room\"
	    	value=\"%s\" title=\"%s\">%s</a></li>", $row['id'], $row['name'], $row['name']);
}
} elseif($scope == 3){
$i = 0;
$getem = mysql_query("SELECT * FROM groups_details WHERE name LIKE '%".$query."%' LIMIT 10");

while ($row = mysql_fetch_assoc($getem)) {
	$i++;
	printf("<li><a href=\"#\" class=\"linktool-result\" type=\"group\"
	    	value=\"%s\" title=\"%s\">%s</a></li>", $row['id'], $row['name'], $row['name']);
}
}
?>
</ul>