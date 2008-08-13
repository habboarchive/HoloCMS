<?php
/*---------------------------------------------------+
| HoloCMS - Website and Content Management System
+----------------------------------------------------+
| Copyright © 2008 Meth0d
+----------------------------------------------------+
| HoloCMS is provided "as is" and comes without
| warrenty of any kind. 
+----------------------------------------------------+
| Debug tool to examine posted data. Originally coded
| by Jordi-Fun. Minor improvements by Meth0d.
+---------------------------------------------------*/

// ENABLE/DISABLE
$enabled = true;

// FUNCTION
if($enabled == true){
	echo "<h3>POST Debug</h3>The following POST variables were found.<br><br><br>";
	foreach($_POST as $key => $value){
		if($value == ""){ $value = "<i>(Blank)</i>"; }
	echo "<b>".$key.":</b>&nbsp;".$value."";
	echo "<br>";
	}
} else {
echo "<b>This tool is disabled by default for security reasons.</b><br>Set the variable 'enabled' in this file to '1' to use this tool.";
}

?> 
