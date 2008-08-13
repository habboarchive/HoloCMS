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

require_once('../core.php');
require_once('../includes/session.php');
if(function_exists(SendMUSData) !== true){ include('../includes/mus.php'); }

$credits = $myrow['credits'];
$voucher = addslashes($_POST['voucherCode']);

$check = mysql_query("SELECT credits FROM vouchers WHERE voucher = '" . $voucher . "' LIMIT 1") or die(mysql_error());
$valid = mysql_num_rows($check);

if($valid > 0){
	$tmp = mysql_fetch_assoc($check);
	$amount = $tmp['credits'];
	$credits = $credits + $amount;
	mysql_query("UPDATE users SET credits = '".$credits."' WHERE name = '" . addslashes($name) . "' LIMIT 1") or die(mysql_error());
	mysql_query("DELETE FROM vouchers WHERE voucher = '" . $voucher . "' LIMIT 1") or die(mysql_error());
	mysql_query("INSERT INTO `cms_transactions` (`date`, `amount`, `descr`, `userid`) VALUES ('".$date_full."', '".$amount."', 'Credit voucher redeem', '".$my_id."');") or die(mysql_error()); // Appearently ' is not good enough, has to be fancy ` =/
	$resultcode = "green";
	$result = "You have redeemed " . $amount . " credits successfully.";
	@SendMUSData('UPRC' . $my_id);
} else {
	$resultcode = "red";
	$result = "Your redeem code could not be found. Please try again.";
}

if(empty($voucher)){
$resultcode = "red";
$result = "Please enter a valid amount.";
} else {
	if(ctype_digit($voucher)){
		if($voucher < 10001){
			if($myrow['credits'] +  $voucher < 100001){
				$amount = $voucher;
				$credits = $credits + $amount;
				mysql_query("UPDATE users SET credits = '".$credits."' WHERE name = '" . addslashes($name) . "' LIMIT 1") or die(mysql_error());
				mysql_query("INSERT INTO `cms_transactions` (`date`, `amount`, `descr`, `userid`) VALUES ('".$date_full."', '".$amount."', 'Credit redeem from site', '".$my_id."');") or die(mysql_error()); // Appearently ' is not good enough, has to be fancy ` =/
				$resultcode = "green";
				$result = "You have redeemed " . $amount . " credits successfully.";
				@SendMUSData('UPRC' . $my_id);
			} else {
				$resultcode = "red";
				$result = "Don't be greedy, too much credits can crash the system.";
			}
		} else {
			$resultcode = "red";
			$result = "Don't be greedy, too much credits can crash the system.";
		}
	} else {
		$resultcode = "red";
		$result = "Please enter numbers only.";
	}
}


echo "<ul>
    <li class=\"even icon-purse\">
        <div>You Currently Have:</div>
        <span class=\"purse-balance-amount\">" . $credits . " Credits</span>
        <div class=\"purse-tx\"><a href=\"transactions.php\">Account transactions</a></div>
    </li>
</ul>
<div id=\"purse-redeem-result\">
		<div class=\"redeem-error\">
		    <div class=\"rounded rounded-" . $resultcode . "\">
		    	" . $result . "
		    </div>
		</div>
</div>";

?>
