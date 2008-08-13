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

$pagename = "HoloCMS";

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
				   <div align='center'>
					<img src='./images/holocms-logo.png' border='0' alt='HoloCMS'><br />
					v<?php echo $holocms['version'] . " " . $holocms['stable']; ?><br />
					Codename '<?php echo $holocms['title']; ?>'<br /><br />
				   </div>
					<br /><br />					
					HoloCMS is web interface for Holograph Emulator, or, to promote it a little: HoloCMS a free, advanced and complete website and content management solution for Holograph Emulator, published under the <a href='http://creativecommons.org/licenses/by-nc-nd/3.0/' target='_blank'>Creative Commons Attribution-Noncommercial-No Derivative Works 3.0 Unported license</a>. It is developed and published by Sisija.<br /><br />Should you be interested in contributing to HoloCMS in any way whatsoever, please contact us.<br /><br /><b>If you paid for this, go get your money back.</b>
				</p>
<?php echo "			<p><strong>HoloCMS Version:</strong> ".$holocms['version']." ".$holocms['stable']." [".$holocms['title']."]<br />\n<strong>HoloCMS Build Date:</strong> ".$holocms['date']."<br />\n<strong>Holograph Emulator Compatability:</strong> Build for <a href='http://trac2.assembla.com/holograph/changeset/".$holograph['revision']."' target='_blank'>Revision ".$holograph['revision']."</a> (".$holograph['type'].")</p>\n"; ?>
				<p>
					<strong><a href='index.php?p=credits'>Development Credits</a></strong>
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