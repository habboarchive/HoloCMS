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

if($hkzone !== true){ header("Location: index.php?throwBack=true"); exit; }

if(empty($pagename)){
	$pnme = "HoloCMS Housekeeping";
} else {
	$pnme = "HoloCMS Housekeeping | " . $pagename . " ";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xml:lang="en" lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" /> 
<title><?php echo $pnme; ?></title>
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Expires" content="Mon, 06 May 1996 04:57:00 GMT" />
<link rel="shortcut icon" href="favicon.ico" />
<style type='text/css' media="all">
@import url( "./css/hk_style.css" );
</style>
 <script type="text/javascript" src='./js/hk_js.js'></script>
</head>