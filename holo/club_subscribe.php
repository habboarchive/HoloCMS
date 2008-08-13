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

require_once('./core.php');
if(function_exists(SendMUSData) !== true){ include('includes/mus.php'); }

if(!session_is_registered(username)){ echo "<p>\nPlease log in first.\n</p>\n\n<p>\n<a href=\"#\" class=\"new-button\" onclick=\"habboclub.closeSubscriptionWindow(); return false;\"><b>Done</b><i></i></a>\n</p>"; exit; }

$months = $_POST['optionNumber'];

switch($months){
	case 1: $price = 20; $valid = 1; break;
	case 3: $price = 50; $valid = 1; break;
	case 6: $price = 80; $valid = 1; break;
	default: $valid = 0; break;
}

if($valid !== 1){ $price = 20; $months = 1; $valid = 1; }

if($myrow['credits'] < $price){
	$msg = "You don't have enough credits to complete the subscription purchase.";
} else {
	$check = mysql_query("SELECT months_left FROM users_club WHERE userid = '".$my_id."' LIMIT 1") or die(mysql_error());
	$results = mysql_num_rows($check);

	if($results > 0){
		$row = mysql_fetch_assoc($check);
		$months_left = $row['months_left'];
	} else {
		$months_left = 0;
	}

	$months_left = $months_left + $months - 1;

	if(getContent('hc-maxmonths') == "0" || $months_left < getContent('hc-maxmonths')){
		mysql_query("UPDATE users SET credits = credits - ".$price." WHERE id = '".$my_id."' LIMIT 1") or die(mysql_error());
		giveHC($my_id, $months);
		mysql_query("INSERT INTO cms_transactions (userid,amount,date,descr) VALUES ('".$my_id."','-".$price."','".$date_full."','Club subscription (".$months." month(s))')") or die(mysql_error());
		@SendMUSData('UPRC' . $my_id);
		$msg = "Congratulations! You have successfully subscribed to ".$shortname." Club for ".$months." month(s).";
	} else {
		$msg = "You can only subscribe for a maxiamum of ".getContent('hc-maxmonths')." months. If this subscription was completed, you would have exceeded the limit.";
	}
}

?>

<div id="hc_confirm_box">

    <img src="./web-gallery/album1/piccolo_happy.gif" alt="" align="left" style="margin:10px;" />
<p><b>Subscribe</b></p>
<p><?php echo $msg; ?></p>

<p>
<a href="#" class="new-button" onclick="habboclub.closeSubscriptionWindow(); return false;">
<b>Done</b><i></i></a>
</p>

</div>

<div class="clear"></div>



