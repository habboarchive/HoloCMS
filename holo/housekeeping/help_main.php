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

$pagename = "Help Centre";

@include('subheader.php');
@include('header.php');
?>
<table cellpadding='0' cellspacing='8' width='100%' id='tablewrap'>
<tr> <td width='22%' valign='top' id='leftblock'>
 <div>
 <!-- LEFT CONTEXT SENSITIVE MENU -->
<?php @include('helpmenu.php'); ?>
 <!-- / LEFT CONTEXT SENSITIVE MENU -->
 </div>
 </td>
 <td width='78%' valign='top' id='rightblock'>
 <div><!-- RIGHT CONTENT BLOCK -->
 
	<div id='acp-update-wrapper'>
		<div class='homepage_pane_border' id='acp-update-normal'>
		 <div class='homepage_section'>Help Centre - Main Page</div>
			<div style='font-size:12px;padding:4px; text-align:left'>
				<p>
				   Welcome to the <b>Help Centre</b>. On the following pages we will treat several subjects you might require help with. Most of the help topics are frequently asked questions or other topics we thought could be usefull whilst using and setting up HoloCMS and it's housekeeping.<br /><br />Should, however, the Help Centre not supply enough information and/or support, you can always ask your question on <a href='http://forum.ragezone.com/f282' target='_blank'>RaGEZONE</a> -- that's where the experts on this subject hang out ... as well as the idiots.<br /><br />What ever the reason your visit to this tab may be, I hope the Help Centre can help you out.
				</p>
			</div>
		</div>
	</div>
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