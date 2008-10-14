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
if($hkzone !== true){ header("Location: index.php?throwBack=true"); exit; }
if(!session_is_registered(acp)){ header("Location: index.php?p=login"); exit; }
if(function_exists(SendMUSData) !== true){ include('../includes/mus.php'); }

$pagename = "Give Credits";

if(isset($_POST['query'])){

	$query = FilterText($_POST['query']);
	$amount = FilterText($_POST['amount']);

	$check = mysql_query("SELECT id,name FROM users WHERE name = '".$query."' LIMIT 1") or die(mysql_error());
	$results = mysql_num_rows($check);

	if($results > 0 && is_numeric($amount)){

		$row = mysql_fetch_assoc($check);
		$query = $row['id'];
		$name = $row['name'];

		mysql_query("UPDATE users SET credits = credits + ".$amount." WHERE id = '".$query."' LIMIT 1") or die(mysql_error());
		mysql_query("INSERT INTO cms_transactions (userid,date,amount,descr) VALUES ('".$query."','".$date_full."','".$amount."','Housekeeping transfer/refund')") or die(mysql_error());
		$msg = "Gave user " . $name . " " . $amount . " credits.";
                
                @SendMusData('UPRC' . $query);

		mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','Gave user ".$amount." credits','givecredits.php','".$my_id."','".$query."','".$date_full."')") or die(mysql_error());

	} else {

		$msg = "Amount is not valid or this user does not exist!";

	}

}

@include('subheader.php');
@include('header.php');
?>
<table cellpadding='0' cellspacing='8' width='100%' id='tablewrap'>
<tr> <td width='22%' valign='top' id='leftblock'>
 <div>
 <!-- LEFT CONTEXT SENSITIVE MENU -->
<?php @include('usermenu.php'); ?>
 <!-- / LEFT CONTEXT SENSITIVE MENU -->
 </div>
 </td>
 <td width='78%' valign='top' id='rightblock'>
 <div><!-- RIGHT CONTENT BLOCK -->
 
<?php if(isset($msg)){ ?><p><strong><?php echo $msg; ?></p></strong><?php } ?>
 
<form action='index.php?p=givecredits&do=give' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>Give Credits / Refunds</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><strong>Username</strong><div class='graytext'>The name or ID of the user you want to give the credits to.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='query' value="" size='30' class='textinput'></td>
</tr>

<tr>
<td class='tablerow1'  width='40%'  valign='middle'><strong>Amount</strong><div class='graytext'>The amount of credits you want to give.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='amount' value="" size='30' class='textinput'></td>
</tr>

<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='Give Credits' class='realbutton' accesskey='s'></td></tr>
</form></table></div><br />

	 </div><!-- / RIGHT CONTENT BLOCK -->
	 </td></tr>
</table>
</div><!-- / OUTERDIV -->
<div align='center'><br />
<?php
$mtime = explode(' ', microtime());
$totaltime = $mtime[0] + $mtime[1] - $starttime;
printf('Time: %.3f', $totaltime);
?>
</div>