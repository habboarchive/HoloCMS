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

if (!defined("IN_HOLOCMS")) { header("Location: ../index.php"); exit; }

session_start();

if(getContent('allow-guests') !== "1"){
    // we don't take kindly to your kind around here
    $allow_guests = false;
}

if(!session_is_registered(username) && $allow_guests !== true){
    header("Location: index.php"); exit;
}

?>