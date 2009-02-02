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

///////////////////////////////////////////////////////////////////////////////////////////////
define("IN_HOLOCMS", TRUE);
session_start();

// #########################################################################
// Start the initalization process

$bypass_check = true;
@include('./core.php');
@include('../core.php');

//$step = $_GET['step'];
//if($step < 0 || $step > 5 || !is_numeric($step)){ $step = 0; }
//if($step > 1){ @include('config.php'); }

///////////////////////////////////////////////////////////////////////////////////////////////

echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<title>HoloCMS - Upgrading</title>
<link rel=\"shortcut icon\" href=\"favicon.ico\" type=\"image/vnd.microsoft.icon\" />
<link rel=\"stylesheet\" href=\"web-gallery/v2/styles/install.css\" type=\"text/css\" />

</head>
<body>
<h1 id=\"logo\"><img alt=\"HoloCMS\" src=\"housekeeping/images/holocms-logo.png\" /></h1>\n";

///////////////////////////////////////////////////////////////////////////////////////////////

if($_GET['do'] == "upgrade_newsletter"){
	mysql_query("ALTER TABLE `users` ADD `newsletter` INT( 1 ) NULL DEFAULT '1' AFTER `hc_before` ;");
	mysql_query("INSERT INTO `cms_content` VALUES ('newsletter-1header', '<table width=\\\"98%\\\" height=\\\"98%\\\" cellpadding=\\\"0\\\" bgcolor=\\\"#ffffff\\\" background=\\\"".$path."newsletter/images/bg_new_website.gif\\\"><tbody><tr><td valign=\\\"top\\\">\r\n\r\n<table width=\\\"576\\\" cellspacing=\\\"0\\\" cellpadding=\\\"0\\\" border=\\\"0\\\" background=\\\"".$path."newsletter/images/bg_new_website.gif\\\" style=\\\"margin: 20px 20px 20px 40px;\\\">\r\n\r\n<tbody><tr> <td width=\\\"576\\\" height=\\\"20\\\" colspan=\\\"4\\\" style=\\\"margin: 0px;\\\"> <p style=\\\"font-size: 9px; color: black; font-family: verdana; padding-left: 10px;\\\">Add this address to your address book to make sure you receive our emails!</p></td></tr>\r\n\r\n<tr> <td width=\\\"576\\\" height=\\\"79\\\" background=\\\"".$path."newsletter/images/email_Header.gif\\\" colspan=\\\"4\\\" style=\\\"margin: 0px;\\\"></td>\r\n</tr>\r\n\r\n<tr> <td width=\\\"5\\\" background=\\\"".$path."newsletter/images/outline.gif\\\"></td>\r\n\r\n<td>\r\n\r\n<table width=\\\"566\\\" height=\\\"100%\\\" cellspacing=\\\"0\\\" cellpadding=\\\"0\\\" border=\\\"0\\\" style=\\\"padding-left: 5px; padding-top: 5px;\\\">\r\n\r\n<tbody><tr> <td width=\\\"366\\\" valign=\\\"top\\\" align=\\\"left\\\">\r\n\r\n<table width=\\\"362\\\" cellspacing=\\\"0\\\" cellpadding=\\\"0\\\">\r\n\r\n<tbody>\r\n\r\n</tbody></table>\r\n\r\n</td>\r\n\r\n<td width=\\\"210\\\" valign=\\\"top\\\" align=\\\"center\\\">\r\n\r\n<table width=\\\"189\\\" cellspacing=\\\"0\\\" cellpadding=\\\"0\\\">\r\n\r\n<tbody>\r\n<tr>\r\n<td height=\\\"2\\\"></td>\r\n</tr>\r\n</tbody></table>\r\n</td>\r\n</tr></tbody></table>\r\n</td>\r\n<td width=\\\"5\\\" background=\\\"".$path."newsletter/images/outline.gif\\\"> </td> </tr>\r\n<tr> <td width=\\\"5\\\" background=\\\"".$path."newsletter/images/outline.gif\\\"> </td>\r\n<td>\r\n\r\n<table width=\\\"100%\\\" cellspacing=\\\"0\\\" cellpadding=\\\"0\\\" border=\\\"0\\\">\r\n\r\n<tbody><tr> <td width=\\\"7\\\"> </td> <td width=\\\"552\\\" valign=\\\"bottom\\\" height=\\\"7\\\"><img width=\\\"552\\\" height=\\\"7\\\" src=\\\"".$path."newsletter/images/disclaimer_top.gif\\\" /></td> <td width=\\\"7\\\"> </td> </tr>\r\n\r\n<tr>\r\n<td width=\\\"7\\\"> </td>\r\n<td width=\\\"552\\\" bgcolor=\\\"#ffffff\\\">', 'Newsletter header', 'This will be the header for all newsletters. (HTML allowed).', '1', '5');");
	mysql_query("INSERT INTO `cms_content` VALUES ('newsletter-2footer', '</td>\r\n\r\n</tr>\r\n\r\n<tr> <td width=\\\"7\\\"> </td> <td width=\\\"552\\\" valign=\\\"top\\\" height=\\\"7\\\"><img width=\\\"552\\\" height=\\\"7\\\" src=\\\"".$path."newsletter/images/disclaimer_bottom.gif\\\" /></td> <td width=\\\"7\\\"> </td> </tr>\r\n\r\n</tbody></table>\r\n\r\n</td>\r\n\r\n<td width=\\\"5\\\" background=\\\"".$path."newsletter/images/outline.gif\\\"> </td>\r\n\r\n</tr><tr>\r\n<td width=\\\"576\\\" valign=\\\"top\\\" height=\\\"12\\\" bgcolor=\\\"#ffffff\\\" background=\\\"".$path."newsletter/images/bg_new_website.gif\\\" colspan=\\\"4\\\">\r\n<img src=\\\"".$path."newsletter/images/email_bottom.gif\\\" /></td>\r\n</tr>\r\n<tr> <td style=\\\"padding-left: 10px;\\\" colspan=\\\"4\\\">\r\n<p align=\\\"center\\\" style=\\\"font-family: Verdana; color: black; font-size: 9px;\\\">\r\nThis email has been sent to you by ".$sitename." (<a href=\\\"".$path."\\\" target=\\\"_blank\\\" style=\\\"color: rgb(0, 0, 255); font-size: 10px;\\\">".$path."</a>). If you do not want to receive news from updates, cool campaigns and other fun stuff in the future please visit the Account Settings page, then Email settings, and uncheck \\\"Yes, please send me Obbah Hotel updates, including the newsletter!\\\". A ".$sitename." user has registered to our service with this email address.\r\n\r\nNote: Replies to this email will not be processed.<br /><br />\r\n\r\nPowered by HoloCMS &copy 2008 Meth0d & Parts by Yifan, sisija<br />HABBO is a registered trademark of Sulake Corporation. All rights reserved to their respective owner(s).<br />We are not endorsed, affiliated, or sponsered by Sulake Corporation Oy.</p>\r\n\r\n</td></tr>\r\n</tbody></table>\r\n\r\n</td></tr>\r\n\r\n</tbody></table>', 'Newsletter footer', 'This will be the footer for all newsletters.', '1', '5');");
	mysql_query("INSERT INTO `cms_content` VALUES ('newsletter-3from', 'mailings@habbohotel.com', 'Newsletter From', 'This will the the From field on all outgoing messages.', '1', '5');");
	mysql_query("INSERT INTO `cms_content` VALUES ('newsletter-4fromname', '".$sitename."', 'Newsletter from name', 'The from name displayed.', '1', '5');");
	echo "<b>Succesfully upgraded.<br></b>Please run any other upgrades and then delete upgrade.php and install.php.<br>";
}elseif($_GET['do'] == "upgrade_referrals_email"){
	mysql_query("ALTER TABLE `users` ADD `email_verified` TINYINT( 1 ) NULL DEFAULT '0' AFTER `newsletter` ;");
	mysql_query("ALTER TABLE `users` DROP `screen` ;");
	mysql_query("ALTER TABLE `users` DROP `rea` ;");
	mysql_query("DROP TABLE IF EXISTS `cms_verify`;
CREATE TABLE `cms_verify` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
	echo "<b>NOTICE TO CONTINUE: You MUST run ONLY STEP 1 of install.php to generate a new config.php!<br></b>Please run any other upgrades and then delete upgrade.php and install.php.<br>";
}else{ ?>
Update to the newest database; no data will be lost. Please run any updates you haven't done before in the order they appear below:<br />
<p><a href="upgrade.php?do=upgrade_newsletter" class="button">Install Newsletter system (3.1.1.50)</a></p>
<p><a href="upgrade.php?do=upgrade_referrals_email" class="button">Install Referrals & Email Verification system (3.1.1.55)</a></p>
<?php }

///////////////////////////////////////////////////////////////////////////////////////////////

echo "\n</body>
</html>";

?>