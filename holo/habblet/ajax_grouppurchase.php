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
if(function_exists(SendMUSData) !== true){ include('../includes/mus.php'); }

if(!session_is_registered(username)){ echo "<p>\nPlease log in first.\n</p>\n\n<p>\n<a href=\"#\" class=\"new-button\" onclick=\"GroupPurchase.close(); return false;\"><b>Done</b><i></i></a>\n</p>"; exit; }

$do = $_GET['do'];

	// Make sure the user meets the requirements to buy a group. If not, this part
	// should cut off the script.
	
	if(getContent('allow-group-purchase') !== "1"){

			echo "<p id=\"purchase-result-error\">Purchasing the group failed. Please try again later.</p>\n<div id=\"purchase-group-errors\">\n<p>\nPurchashing groups has been disabled by the Hotel Managment. Please try again later.<br />\n</p>\n</div>\n<p>\n<a href=\"#\" class=\"new-button\" onclick=\"GroupPurchase.close(); return false;\"><b>Done</b><i></i></a>\n</p>\n<div class=\"clear\"></div>"; exit;

	} elseif($myrow['credits'] < 20){

			echo "<p id=\"purchase-result-error\">Purchasing the group failed. Please try again later.</p>\n<div id=\"purchase-group-errors\">\n<p>\nYou don't have enough Credits. <a href=\"credits.php\">Get more here!</a><br />\n</p>\n</div>\n<p>\n<a href=\"#\" class=\"new-button\" onclick=\"GroupPurchase.close(); return false;\"><b>Done</b><i></i></a>\n</p>\n<div class=\"clear\"></div>"; exit;

	} else {

			$groups_owned = mysql_evaluate("SELECT COUNT(*) FROM groups_details WHERE ownerid = '".$my_id."' LIMIT 10");

			if($groups_owned > 10){
				echo "<p id=\"purchase-result-error\">Purchasing the group failed. Please try again later.</p>\n<div id=\"purchase-group-errors\">\n<p>\nYou have reached the maximum amount of <i>owned</i> groups per user (3).<br />\n</p>\n</div>\n<p>\n<a href=\"#\" class=\"new-button\" onclick=\"GroupPurchase.close(); return false;\"><b>Done</b><i></i></a>\n</p>\n<div class=\"clear\"></div>"; exit;
			}

	}


	// The buy part. If the script has not been cut off yet, we should be ready to go.

	if(empty($do) || $do !== "purchase_confirmation"){

		echo "<p>\n<img src='./habbo-imaging/badge-fill/b0503Xs09114s05013s05015.gif' border='0' align='left'>Fill in the form below to create your own group. Creating a group will cost you <b>20</b> credits.\n</p>\n\n<p>\n<b>Group Name</b><br /><input type='text' name='name' id='group_name' value='' length='10' maxlength='25'>\n</p>\n\n<p>\n<b>Group Description</b><br />\n<textarea name='description' id='group_description' maxlength='200'></textarea>\n</p>\n\n<p>\nYou can modify these settings at any time after creating the group.\n</p>\n\n<p>\n<a href=\"#\" class=\"new-button\" onclick=\"GroupPurchase.confirm(); return false;\"><b>Purchase</b><i></i></a>\n<a href=\"#\" class=\"new-button\" onclick=\"GroupPurchase.close(); return false;\"><b>Cancel</b><i></i></a>\n</p>"; exit;

	} elseif($do == "purchase_confirmation"){

		$group_name = trim($_POST['name']);
		$group_desc = trim($_POST['description']);

		if(empty($group_name) || empty($group_desc)){

			echo "<p>\nPlease fill in all fields!\n</p>\n\n<p>\n<a href=\"#\" class=\"new-button\" onclick=\"GroupPurchase.close(); GroupPurchase.open(); return false;\"><b>Back</b><i></i></a>\n</p>"; exit;

		} else {

			if(strlen($group_name > 25) && !is_numeric($group_name)){

				echo "<p>\nThe group name you have selected is too long.\n</p>\n\n<p>\n<a href=\"#\" class=\"new-button\" onclick=\"GroupPurchase.close(); GroupPurchase.open(); return false;\"><b>Back</b><i></i></a>\n</p>"; exit;

			} elseif(strlen($group_desc > 200) && !is_numeric($group_desc)){

				echo "<p>\nThe group description you have entered is too long.\n</p>\n\n<p>\n<a href=\"#\" class=\"new-button\" onclick=\"GroupPurchase.close(); GroupPurchase.open(); return false;\"><b>Back</b><i></i></a>\n</p>"; exit;

			} else {

				$check = mysql_query("SELECT id FROM groups_details WHERE name = '".$group_name."' LIMIT 1") or die(mysql_error());
				$already_exists = mysql_num_rows($check);

				if($already_exists > 0){

					echo "<p>\nAn group with this name already exists.\n</p>\n\n<p>\n<a href=\"#\" class=\"new-button\" onclick=\"GroupPurchase.close(); GroupPurchase.open(); return false;\"><b>Back</b><i></i></a>\n</p>";

				} else {

					$orname = $group_name;
					$group_name = FilterText($orname);
					$group_desc = FilterText($group_desc);

					mysql_query("INSERT INTO groups_details (name,description,ownerid,created,badge,type) VALUES ('".$group_name."','".$group_desc."','".$my_id."','".$date_full."','b0503Xs09114s05013s05015','0')") or die(mysql_error());

					$check = mysql_query("SELECT id FROM groups_details WHERE ownerid = '".$my_id."' ORDER BY id DESC LIMIT 1") or die(mysql_error());
					$row = mysql_fetch_assoc($check);
					$group_id = $row['id'];

					mysql_query("INSERT INTO groups_memberships (userid,groupid,member_rank,is_current) VALUES ('".$my_id."','".$group_id."','2','0')") or die(mysql_error());
					mysql_query("UPDATE users SET credits = credits - 20 WHERE id = '".$my_id."' LIMIT 1") or die(mysql_error());
					mysql_query("INSERT INTO cms_transactions (userid,descr,date,amount) VALUES ('".$my_id."','Group purchase','".$date_full."','-20')") or die(mysql_error());
					
					@SendMUSData('UPRC' . $my_id);

					echo "<p>\n<b>Group Purchased</b><br /><br /><img src='./habbo-imaging/badge-fill/b0503Xs09114s05013s05015.gif' border='0' align='left'>Congratulations! You are now the proud owner of <b>".HoloText($orname)."</b>.<br /><br />Click <a href='group_profile.php?id=".$group_id."'>here</a> to go to your group home right away, or use the button below to close this window.\n</p>\n\n<p>\n<a href=\"#\" class=\"new-button\" onclick=\"GroupPurchase.close(); return false;\"><b>Done</b><i></i></a>\n</p>";

				}

			}

		}

	} else {

		echo "<p>\nAn unknown error occured. Please try again in a couple of minutes.\n</p>\n\n<p>\n<a href=\"#\" class=\"new-button\" onclick=\"GroupPurchase.close(); return false;\"><b>OK</b><i></i></a>\n</p>";

	}

?>



