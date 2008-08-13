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

$pagename = "Database Toolbox - Manual Query";

if(isset($_POST['query'])){
    $query = $_POST['query'];
    $msg = "Executing query on ".$sqldb."..<br />";
    $query = mysql_query($query) or $errstr = mysql_error();
    if(!empty($errstr)){
	$msg = $msg . "</strong>Failure. The following error was encountered: <strong>" . $errstr;
    } else {
	$affected_rows = @mysql_affected_rows($query);
	$msg = $msg . "</strong>Success! Rows affected: <strong>" . $affected_rows;
    }
}

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

<form method='post'>
<b>MySQL Query on <?php echo $sqldb; ?>:</b><br />
<textarea name='query' cols='60' rows='10'><?php echo $_POST['query']; ?></textarea><br />
<input type='submit'>
</form>

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