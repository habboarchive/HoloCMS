<?php
/*---------------------------------------------------+
| HoloCMS - Website and Content Management System
+----------------------------------------------------+
| Copyright © 2008 Meth0d
+----------------------------------------------------+
| HoloCMS is provided "as is" and comes without
| warrenty of any kind. 
+---------------------------------------------------*/

if (!defined("IN_HOLOCMS")) { header("Location: ../index.php"); exit; }

mysql_connect("$sqlhostname", "$sqlusername", "$sqlpassword")or die("<br><font size='2' face='Tahoma'><b>HoloCMS Configuration Error:</b><br>I was unable to connect to the provided MySQL server. Please review the error message above for details.</font>");
mysql_select_db("$sqldb")or die("<br><font size='2' face='Tahoma'><b>HoloCMS Configuration Error:</b><br>Unable to select the database you provided. Either I do not have premission to connect to that database, or the database doesn't exist.</font>");

?>