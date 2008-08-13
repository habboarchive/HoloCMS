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

$pagename = "Access Denied";

@include('subheader.php');
@include('header.php');
?>
<table cellpadding='0' cellspacing='8' width='100%' id='tablewrap'>
<tr>
 <td width='100%' valign='top' id='rightblock'>
 <div><!-- RIGHT CONTENT BLOCK -->

	<div id='acp-update-wrapper'>
		<div class='homepage_pane_border' id='acp-update-normal'>
		 <div class='homepage_section'>HoloCMS</div>
			<div style='font-size:12px;padding:4px; text-align:left'>
				<p>
					<h3>Access Denied</h3>
					Sorry, but you do not have access to this page. This could be due to one of the several reasons:<br />
					- Are you trying to access a System Administrator-only part of the site?<br />
					- Is your user rank insufficient to access this page?<br />
					<br />
					If you belive you are seeing this page in error, contact the System Administrator.
				</p>
				<p>
					<strong><a href='javascript:history.back(1);'>Back to previous page</a></strong>
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