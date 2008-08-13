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

$slot = $_POST['slot'];
$figure = addslashes($_POST['figure']);
$gender = $_POST['gender'];

if(!empty($figure) && !empty($gender) && !empty($slot)){

	if($gender !== "M" && $gender !== "F"){
		$gender = "M";
	}

	if($slot !== "1" && $slot !== "2" && $slot !== "3" && $slot !== "4" && $slot !== "5"){
		$slot = "1";
	}

	$check = mysql_query("SELECT gender FROM cms_wardrobe WHERE userid = '".$my_id."' AND slotid = '".$slot."' LIMIT 1") or die(mysql_error());
	$exists = mysql_num_rows($check);

	if($exists > 0){
		mysql_query("UPDATE cms_wardrobe SET figure = '".$figure."', gender = '".$gender."' WHERE userid = '".$my_id."' AND slotid = '".$slot."' LIMIT 1") or die(mysql_error());
	} else {
		mysql_query("INSERT INTO cms_wardrobe (userid,slotid,figure,gender) VALUES ('".$my_id."','".$slot."','".$figure."','".$gender."')") or die(mysql_error());
	}

	// Now we need to attach this weird header so it updates the figure.
	// Took me a while to figure out and I still don't understand but meh.
	header("X-JSON: {\"u\":\"http://www.habbo.co.uk/habbo-imaging/avatarimage?figure=" . $figure . "&size=s&direction=4&head_direction=4&gesture=sml\",\"f\":\"" . $figure . "\",\"g\":77}");

}

?>

