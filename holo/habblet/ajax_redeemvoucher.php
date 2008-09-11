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

$check = mysql_query("SELECT type, credits FROM vouchers WHERE voucher = '" . $voucher . "' LIMIT 1") or die(mysql_error()); 
$valid = mysql_num_rows($check); 

if($valid > 0){ 
    $tmp = mysql_fetch_assoc($check); 
    $amount = $tmp[credits]; 
    $resultcode = "green"; 
    if($tmp['type'] == credits) { 
    $credits = $credits + $amount; 
    mysql_query("UPDATE users SET credits = '".$credits."' WHERE name = '" . addslashes($name) . "' LIMIT 1") or die(mysql_error()); 
    mysql_query("DELETE FROM vouchers WHERE voucher = '" . $voucher . "' LIMIT 1") or die(mysql_error()); 
    mysql_query("INSERT INTO `cms_transactions` (`date`, `amount`, `descr`, `userid`) VALUES ('".$date_full."', '".$amount."', 'Credit voucher redeem', '".$my_id."');") or die(mysql_error()); // Appearently ' is not good enough, has to be fancy ` =/ 
    $result = "You have redeemed " . $amount . " credits successfully."; 
    @SendMUSData('UPRC' . $my_id); 
} else { 
    $item = mysql_query("SELECT tid FROM catalogue_items WHERE name_cct = '" . $amount . "' LIMIT 1") or die(mysql_error()); 
    $itemvalid = mysql_num_rows($item); 
    if($itemvalid > 0){ 
    $itemtmp = mysql_fetch_assoc($item); 
    $itemid = $itemtmp[tid]; 
    mysql_query("INSERT INTO furniture (tid, ownerid) VALUES ('".$itemid."', '".$my_id."');") or die(mysql_error()); 
    mysql_query("DELETE FROM vouchers WHERE voucher = '" . $voucher . "' LIMIT 1") or die(mysql_error()); 
    mysql_query("INSERT INTO `cms_transactions` (`date`, `amount`, `descr`, `userid`) VALUES ('".$date_full."', '".$amount."', 'Item voucher redeem', '".$my_id."');") or die(mysql_error()); 
    $result = "You have redeemed this item of furniture successfully."; 
    @SendMUSData('UPRH' . $my_id); 
    } else { 
    $resultcode = "red"; 
    $result = "Item not valid, please contact an admin for assistance."; 
    } 
    } 
} else { 
    $resultcode = "red"; 
    $result = "Your redeem code could not be found. Please try again."; 
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