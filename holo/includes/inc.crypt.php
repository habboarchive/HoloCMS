<?php
/*===================================================+
|| # HoloCMS - Website and Content Management System
|+===================================================+
|| # Copyright � 2008 Meth0d. All rights reserved.
|| # http://www.meth0d.org
|+===================================================+
|| # HoloCMS is provided "as is" and comes without
|| # warrenty of any kind. HoloCMS is free software!
|+===================================================*/

if(!defined("IN_HOLOCMS")) { header("Location: ../index.php"); exit; }

function HoloHash($password, $username){
	@include('./config.php');
	@include('../config.php');
	if($encryption == "new"){
		$string = sha1($password.strtolower($username));
	}elseif($encryption == "old"){
		$random_salt = $hashtext;
		$string = md5($random_salt . $password);
	}elseif($encryption == "bad"){
		$string = sha1($password);
	}elseif($encryption == "verybad"){
		$string = $password;
	}else{
		echo "<h1>Alert</h1><hr>You didn't follow my instructions! Please run install.php step 1 ONLY to get an update config.php.<hr><i>HoloCMS</i>";
		exit;
	}
	return $string;
}

?>