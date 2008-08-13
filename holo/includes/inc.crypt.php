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

// WARNING: Do NOT change the $random_salt manually if there are users in the database!
// Unless you know what you're doing, you might end up with a corrupted database.

if(!defined("IN_HOLOCMS")) { header("Location: ../index.php"); exit; }

function HoloHash($string){
	$random_salt = "235x17aXCaRb";
	$string = md5($random_salt . $string);
	return $string;
}

?>