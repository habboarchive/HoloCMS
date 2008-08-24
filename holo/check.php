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

require_once('./includes/version.php');

echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<title>HoloCMS</title>
<link rel=\"shortcut icon\" href=\"favicon.ico\" type=\"image/vnd.microsoft.icon\" />
<link rel=\"stylesheet\" href=\"web-gallery/v2/styles/install.css\" type=\"text/css\" />

</head>
<body>
<h1 id=\"logo\"><img alt=\"HoloCMS\" src=\"housekeeping/images/holocms-logo.png\" /></h1>\n";

echo "<p><strong>Welcome to HoloCMS</strong></p>\n";
echo "<p>In order to have the best experence with HoloCMS, you MUST do/already have done the following (failure to do all steps below with result in errors):</p>";
echo "<ol>\n";
echo "	<li>Replaced your old HoloCMS installation including all web-gallery files</li>\n";
echo "	<li>Excuted install.php.</li>\n";
echo "	<li>Have a <a href='http://www.ragezone.com/'>RaGEZONE</a> account (no reason here, but for those who came from another forum, know that this site is far superior).</li>\n";
echo "	<li>Make sure the link given to you was posted by yifan_lu, if not, HoloCMS will screw up your server</li>\n";
echo "<p><strong>Found a bug?</strong></p>";
echo "<p>Please do not post bugs in the forum topic, post it here: <a href='https://www.assembla.com/spaces/holocms3/tickets'>Assembla</a> so I can organize them and help you. I will not fix bugs posted in the forum.
</p>";
echo "<p><strong>Compatible Data</strong></p>\n";
echo "<p>If you came from:<br />";
echo "<strong>3.1.X BETA</strong>, <strong>3.0.X.X</strong>, <strong>2.1.1</strong>, or lower:<br />";
echo "Please replace all files and run install.php";
echo "<strong>3.1.X</strong>:<br />";
echo "Run replace all files and run upgrade.php";

?>
</body>
</html>