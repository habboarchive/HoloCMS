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

include('core.php');
include('./includes/session.php');

?>

   <div id="groups-habblet-list-container" class="habblet-list-container groups-list">

<?php
$get_em = mysql_query("SELECT * FROM groups_details ORDER BY rand() LIMIT 12") or die(mysql_error());
$groups = mysql_num_rows($get_em);

echo "\n    <ul class=\"habblet-list two-cols clearfix\">";

$num = 0;

while($row = mysql_fetch_assoc($get_em)){
$num++;

if(IsEven($num)){
	$pos = "right";
	$rights++;
} else {
	$pos = "left";
	$lefts++;
}

if(IsEven($lefts)){
	$oddeven = "odd";
} else {
	$oddeven = "even";
}

$group_id = $row['id'];
$groupdata = $row;

echo "            <li class=\"".$oddeven." ".$pos."\" style=\"background-image: url(./habbo-imaging/badge.php?badge=".$groupdata['badge'].")\">\n		<a class=\"item\" href=\"group_profile.php?id=".$group_id."\">".stripslashes($groupdata['name'])."</a>\n            </li>\n\n";
}

$rights_should_be = $lefts;
if($rights !== $rights_should_be){
	echo "<li class=\"".$oddeven." right\"><div class=\"item\">&nbsp;</div></li>";
}

echo "\n    </ul>";
?>

		<div class="habblet-button-row clearfix"><a class="new-button" id="purchase-group-button" href="#"><b>Create/buy a Group</b><i></i></a></div>
    </div>

    <div id="groups-habblet-group-purchase-button" class="habblet-list-container"></div>

<script type="text/javascript">
    $("purchase-group-button").observe("click", function(e) { Event.stop(e); GroupPurchase.open(); });
</script>





    </div>