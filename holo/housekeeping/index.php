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

$is_maintenance = true; // Tell the core to buzz off with it's maintenance

@include('../config.php');
@include('./config.php');

require_once('../core.php');

session_start();

$hkzone = true;
$p = $_GET['p'];
$do = $_GET['do'];
$key = $_GET['key'];

$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];

	if(session_is_registered(acp)){

		$session = $_SESSION['acp'];
		$admin_username = $_SESSION['hkusername'];
		$admin_password = $_SESSION['hkpassword'];

		$check = mysql_query("SELECT id,rank FROM users WHERE name = '" . $admin_username . "' AND password = '" . $admin_password . "' AND rank > 5 LIMIT 1") or die(mysql_error());
		$valid = mysql_num_rows($check);

		if($valid > 0){

			$tmp = mysql_fetch_assoc($check);
			$user_rank = $tmp['rank'];

			if($user_rank < 6){ exit; } // something went horribly wrong here

			if($p == "content" || $p == "server" || $p == "badgetool" || $p == "recycler" || $p == "wordfilter" || $p == "welcomemsg" || $p == "maintenance" || $p == "loader" || $p == "news_manage" || $p == "news_compose" || $p == "ranktool" || $p == "site" || $p == "logs" || $p == "edituser"){
				if($user_rank < 7){
					$p = "access_denied";
				}
			}

			if($p == "logout"){
				session_destroy();
				$notify_logout = true;
				include('login.php');
			} elseif($p == "recommendedm"){
				$tab = 1;
				include('recommendedm.php');
			} elseif($p == "recommendede"){
				$tab = 1;
				include('recommendede.php');
			} elseif($p == "dashboard"){
				$tab = 1;
				include('dashboard.php');
			} elseif($p == "server"){
				$tab = 2;
				include('server.php');
			} elseif($p == "massa_stuff"){
				$tab = 5;
				include('massa.php');
			} elseif($p == "recycler"){
				$tab = 2;
				include('recycler.php');
			} elseif($p == "wordfilter"){
				$tab = 2;
				include('wordfilter.php');
			} elseif($p == "welcomemsg"){
				$tab = 2;
				include('welcomemsg.php');
			} elseif($p == "holocms"){
				$tab = 4;
				include('holocms.php');
			} elseif($p == "help"){
				$tab = 6;
				include('help_main.php');
			} elseif($p == "help_bugs"){
				$tab = 6;
				include('help_bugs.php');
			} elseif($p == "help_svn"){
				$tab = 6;
				include('help_svn.php');
			} elseif($p == "site"){
				$tab = 3;
				include('cms_config.php');
			} elseif($p == "maintenance"){
				$tab = 3;				
				include('maintenance.php');
			} elseif($p == "loader"){
				$tab = 3;
				include('loader.php');
			} elseif($p == "editor_guestroom"){
				$tab = 5;
				include('guestrooms.php');
			} elseif($p == "editguestroom"){
				$tab = 5;
				include('edit_guestroom.php');
			} elseif($p == "editor_publicrooms"){
				$tab = 5;
				include('publicrooms.php');
			} elseif($p == "editpublicroom"){
				$tab = 5;
				include('edit_publicroom.php');
			} elseif($p == "application_edit"){
				$tab = 5;
				include('appforms.php');
			} elseif($p == "news_compose"){
				$tab = 3;
				include('news_compose.php');
			} elseif($p == "collectables_edit"){
				$tab = 3;
				include('edit_collectables.php');
			} elseif($p == "news_manage"){
				$tab = 3;
				include('news_manage.php');
			} elseif($p == "users"){
				$tab = 5;
				include('users.php');
			} elseif($p == "ip"){
				$tab = 5;
				include('ip.php');
			} elseif($p == "clonechecker"){
				$tab = 5;
				include('cloner.php');
			} elseif($p == "transactions"){
				$tab = 5;
				include('transactions.php');
			} elseif($p == "collectables"){
				$tab = 3;
				include('collectables.php');
			} elseif($p == "faq"){
				$tab = 3;
				include('faq.php');
			} elseif($p == "newsletter"){
				$tab = 3;
				include('newsletter.php');
			} elseif($p == "vouchers"){
				$tab = 5;
				include('vouchers.php');
			} elseif($p == "givecredits"){
				$tab = 5;
				include('givecredits.php');
			} elseif($p == "helper"){
				$tab = 5;
				include('helper.php');
			} elseif($p == "ranktool"){
				$tab = 5;
				include('ranktool.php');
			} elseif($p == "badgetool"){
				$tab = 5;
				include('badgetool.php');
			} elseif($p == "logs"){
				$tab = 5;
				include('logs.php');
			} elseif($p == "credits"){
				$tab = 4;
				include('credits.php');
			} elseif($p == "banners"){
				$tab = 4;
				include('banners.php');
			} elseif($p == "ban"){
				$tab = 5;
				include('bantool.php');
			} elseif($p == "uid"){
				$tab = 5;
				include('uid.php');	
			} elseif($p == "access_denied"){
				$tab = 0;
				include('access_denied.php');
			} elseif($p == "content"){
				$tab = 3;
				include('content_tool.php');
			} elseif($p == "add_homes"){
				$tab = 3;
				include('add_homes_stuff.php');
			} elseif($p == "banlist"){
				$tab = 5;
				include('banlist.php');
			} elseif($p == "unban"){
				$tab = 5;
				include('unbantool.php');
			} elseif($p == "alert"){
				$tab = 5;
				include('alert.php');
			} elseif($p == "massalert"){
				$tab = 5;
				include('massalert.php');
			} elseif($p == "alertlist"){
				$tab = 5;
				include('alertlist.php');
			} elseif($p == "massmail"){
				$tab = 5;
				include('massmail.php');
			} elseif($p == "minimail"){
				$tab = 5;
				include('minimail.php');
			} elseif($p == "onlinelist"){
				$tab = 5;
				include('onlinelist.php');
			} elseif($p == "chatlogs"){
				$tab = 5;
				include('chatlogs.php');
			} elseif($p == "dboptimize"){
				if($sysadmin == $my_id){
					$tab = 3;
					include('dboptimize.php');
				} else {
					$tab = 0;
					include('access_denied.php');
				}
			} elseif($p == "dbrepair"){
				if($sysadmin == $my_id){
					$tab = 3;
					include('dbrepair.php');
				} else {
					$tab = 0;
					include('access_denied.php');
				}
			} elseif($p == "dbquery"){
				if($sysadmin == $my_id){
					$tab = 3;
					include('dbquery.php');
				} else {
					$tab = 0;
					include('access_denied.php');
				}
			} elseif($p == "edituser"){
				$tab = 5;
				$userid = $_GET['key'];
				include('edituser.php');
			} else {
				$tab = 0;
				header("Location: index.php?p=dashboard");
				exit;
			}

		} else {

			session_destroy();
			header("Location: index.php");
			exit;

		}

	} else {
		include('login.php');
	}

include('footer.php');
exit;

?>