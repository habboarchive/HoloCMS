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

require_once('../core.php');
if($hkzone !== true){ header("Location: index.php?throwBack=true"); exit; }
if(!session_is_registered(acp)){ header("Location: index.php?p=login"); exit; }

$pagename = "Database Toolbox - Optimize";

$msg = "Repairing tables..</strong> (Note: Tables may not be 'broken', but this feature <i>attempts</i> to repair tables, wether it's needed or not)";
$getTables = mysql_query("SHOW TABLES FROM " . $sqldb) or die(mysql_error());
    while($tmp = mysql_fetch_array($getTables)){
        $table = $tmp[0];
        $msg = $msg . "<br />Repairing table " . $tmp[0] . "..";
        mysql_query("REPAIR TABLE " . $tmp[0]) or die(mysql_error());
        $msg = $msg . "<strong>OK!</strong>";
    }
$msg = $msg . "<br /><br />Tables in database optimized successfully!<strong>";

@include('subheader.php');
@include('header.php');
?>
<table cellpadding='0' cellspacing='8' width='100%' id='tablewrap'>
<tr> <td width='22%' valign='top' id='leftblock'>
 <div>
 <!-- LEFT CONTEXT SENSITIVE MENU -->
<?php @include('sitemenu.php'); ?>
 <!-- / LEFT CONTEXT SENSITIVE MENU -->
 </div>
 </td>
 <td width='78%' valign='top' id='rightblock'>
 <div><!-- RIGHT CONTENT BLOCK -->
 
<?php if(isset($msg)){ ?><p><strong><?php echo $msg; ?></strong></p><?php } ?>

</div><!-- / RIGHT CONTENT BLOCK -->
	 </td></tr>
</table>
</div><!-- / OUTERDIV -->
<div align='center'><br />
<?php
$mtime = explode(' ', microtime());
$totaltime = $mtime[0] + $mtime[1] - $starttime;
printf('Time: %.3f', $totaltime);
?>
</div>