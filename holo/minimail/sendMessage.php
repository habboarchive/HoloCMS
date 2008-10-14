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

if($bypass1 != "true"){
include('../core.php');
include('../includes/session.php');

$messageid = FilterText($_POST['messageId']);
$recipientids = $_POST['recipientIds'];
$subject = FilterText($_POST['subject']);
$body = HoloText(FilterText($_POST["body"]));
}
$body = HoloText(FilterText($body));

$ids = explode(",", $recipientids);
$numofids = count($ids);

$sql = mysql_query("SELECT NOW()");
$date = mysql_result($sql, 0);

if(isset($_POST['messageId'])){
	$sql = mysql_query("SELECT * FROM cms_minimail WHERE id = '".$messageid."'");
	$row = mysql_fetch_assoc($sql);
	if($row['conversationid'] == "0"){
		$sql = mysql_query("SELECT MAX(conversationid) FROM cms_minimail");
		$conid = mysql_result($sql, 0);
		$conid = $conid + 1;
		mysql_query("UPDATE cms_minimail SET conversationid = '".$conid."' WHERE id = '".$row['id']."'");
	} else {
		$conid = $row['conversationid'];
	}
	$subject = "Re: ".$row['subject'];
	$ids[0] = $row['senderid'];
} else {
	$conid = "0";
}

$elements = count($ids);
$elements = $elements - 1;
$i = -1;
while ($elements <> $i){
	$i++;
    mysql_query("INSERT INTO cms_minimail (senderid,to_id,subject,date,message,conversationid) VALUES ('".$my_id."','".$ids[$i]."','".$subject."','".$date."','".$body."','".$conid."')");
}
$bypass = "true";
$page = "inbox";
$message = "Message sent sucessfully.";
if($bypass1 != "true"){ include('loadMessage.php'); }
?>