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

$email = FilterText($_POST['email']);
$email_check = preg_match("/^[a-z0-9_\.-]+@([a-z0-9]+([\-]+[a-z0-9]+)*\.)+[a-z]{2,7}$/i", $email);

if(strlen($email) < 6){
	echo "register.message.invalid_email";
} elseif($email_check !== 1){
	echo "register.message.invalid_email";
} else {
	header("X-JSON: \"emailOk\"");
	echo "register.message.email_chars_ok";
}

?>

