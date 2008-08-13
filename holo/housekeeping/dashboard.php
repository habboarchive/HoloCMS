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

$pagename = "Dashboard";

if(isset($_POST['notes'])){
	$notes = addslashes($_POST['notes']);
	mysql_query("UPDATE cms_system SET admin_notes = '".$notes."' LIMIT 1") or die(mysql_error());
}

$tmp = mysql_query("SELECT * FROM cms_system LIMIT 1") or die(mysql_error());
$system = mysql_fetch_assoc($tmp);

$onlineCutOff = (time() - 601);
$onlineUsers = mysql_evaluate("SELECT COUNT(*) FROM users WHERE online > " . $onlineCutOff);

@include('subheader.php');
@include('header.php');
?>
<table cellpadding='0' cellspacing='8' width='100%' id='tablewrap'>
<tr>	<td width='100%' valign='top' id='rightblock'>
	 <div><!-- RIGHT CONTENT BLOCK -->
	 
	 
	 <div style='font-size:30px; padding-left:7px; letter-spacing:-2px; border-bottom:1px solid #EDEDED'>
 HoloCMS Housekeeping
</div>
<br />
<div id='ipb-get-members' style='border:1px solid #000; background:#FFF; padding:2px;position:absolute;width:120px;display:none;z-index:100'></div>
<!--in_dev_notes-->
<!--in_dev_check-->
<table border='0' width='100%' cellpadding='0' cellspacing='4'>
<tr>
 <td valign='top' width='75%'>
	<table border='0' width='100%' cellpadding='0' cellspacing='0'>
	<tr>
	 <td>
		<div class='homepage_pane_border'>
		 <div class='homepage_section'>Tasks and Statistics</div>
		 <table width='100%' cellspacing='0' cellpadding='4'>
			 <tr>
			  <td width='50%' valign='top'>
			  	<div class='homepage_border'>
 <div class='homepage_sub_header'>System Overview</div>
 <table width='100%' cellpadding='4' cellspacing='0'>
 <tr>
  <td class='homepage_sub_row' width='60%'><strong>HoloCMS Version</strong> &nbsp;(<a href='http://trac2.assembla.com/HoloCMS'>History</a>)</td>
  <td class='homepage_sub_row' width='40%'><strong>v<?php echo $holocms['version'] . "</strong>&nbsp;" . $holocms['stable']; ?></td>
 </tr>
 <tr>
  <td class='homepage_sub_row'><strong>Members</strong></td>
  <td class='homepage_sub_row'>
  	<?php echo mysql_evaluate("SELECT COUNT(*) FROM users") . " (<a href='index.php?p=onlinelist'>" . $onlineUsers . "</a> online)"; ?>
  </td>
 </tr>
 <tr>
  <td class='homepage_sub_row'><strong>Rooms</strong></td>
  <td class='homepage_sub_row'>
  	<?php echo mysql_evaluate("SELECT COUNT(*) FROM rooms"); ?>
	(of which <?php echo mysql_evaluate("SELECT COUNT(*) FROM rooms WHERE owner IS NULL"); ?> public rooms)
  </td>
 </tr>
 <tr>
  <td class='homepage_sub_row'><strong>Furniture</strong></td>
  <td class='homepage_sub_row'>
  	<?php echo mysql_evaluate("SELECT COUNT(*) FROM furniture"); ?>
  </td>
 </tr>
 <tr>
  <td class='homepage_sub_row'><strong>Groups</strong></td>
  <td class='homepage_sub_row'>
  	<?php echo mysql_evaluate("SELECT COUNT(*) FROM groups_details"); ?>
  </td>
 </tr>
 <tr>
  <td class='homepage_sub_row'><strong>Stafflog Entries</strong></td>
  <td class='homepage_sub_row'>
  	<?php echo mysql_evaluate("SELECT COUNT(*) FROM system_stafflog"); ?>
  </td>
 </tr>
  <tr>
  <td class='homepage_sub_row'><strong>Active Bans</strong></td>
  <td class='homepage_sub_row'>
  	<a href='index.php?p=banlist'><?php echo mysql_evaluate("SELECT COUNT(*) FROM users_bans"); ?></a>
  </td>
 </tr>

 </table>
</div>
			  </td>
			  <td width='50%' valign='top'>
			  	<div class='homepage_border'>
 <div class='homepage_sub_header'>Server Setup</div>
 <table width='100%' cellpadding='4' cellspacing='0'>
 <tr>
  <td class='homepage_sub_row'><strong>Game Port</strong></td>
  <td class='homepage_sub_row'>
  	<?php echo FetchServerSetting('server_game_port'); ?>
  </td>
 </tr>
 <tr>
  <td class='homepage_sub_row'><strong>&nbsp;&nbsp;&nbsp;&nbsp;- MUS Port</strong></td>
  <td class='homepage_sub_row'>
  	<?php echo FetchServerSetting('server_mus_port'); ?>
  </td>
 </tr>
 <tr>
  <td class='homepage_sub_row'><strong>Maximum Game Connections</strong></td>
  <td class='homepage_sub_row'>
  	<?php echo FetchServerSetting('server_game_maxconnections'); ?>
  </td>
 </tr>
 <tr>
  <td class='homepage_sub_row'><strong>Trading Enabled</strong></td>
  <td class='homepage_sub_row'>
  	<?php echo FetchServerSetting('trading_enable', true); ?>
  </td>
 </tr>
 <tr>
  <td class='homepage_sub_row'><strong>Recycler Enabled</strong></td>
  <td class='homepage_sub_row'>
  	<?php echo FetchServerSetting('recycler_enable', true); ?>
  </td>
 </tr>
 <tr>
  <td class='homepage_sub_row'><strong>Wordfilter Enabled</strong></td>
  <td class='homepage_sub_row'>
  	<?php echo FetchServerSetting('wordfilter_enable', true); ?> (<?php echo FetchServerSetting('wordfilter_censor'); ?>)
  </td>
 </tr>
 </table>
</div>
			  </td>		   </tr>
	    </table>
	   </div>
	 </td>
	</tr>
	<tr>
	 <td>&nbsp;</td>
	</tr>
	<tr>
	 <td>
		<div class='homepage_pane_border'>
		 <div class='homepage_section'>Communication</div>
		 <table width='100%' cellspacing='0' cellpadding='4'>
			 <tr>
			  <td valign='top' width='50%'>
			  	<div class='homepage_border'>
					<div class='homepage_sub_header'>Housekeeping Notes</div>
					<br /><div align='center'>
<form action='index.php?p=dashboard&do=save' method='post'>
<textarea name='notes' style='background-color:#F9FFA2;border:1px solid #CCC;width:95%;font-family:verdana;font-size:10px' rows='8' cols='25'><?php echo stripslashes($system['admin_notes']); ?></textarea>
<div><br /><input type='submit' value='Save Admin Notes' class='realbutton' /></div>
</form>
</div><br />
				</div>
			  </td>
			  <td width='50%' valign='top'>
			  	<div class='homepage_border'>
 <div class='homepage_sub_header'><?php echo $sitename; ?> Administrators</div>
 <table width='100%' cellpadding='4' cellspacing='0'>
<?php
$get_em = mysql_query("SELECT id,name,email FROM users WHERE rank > 6 ORDER BY name ASC LIMIT 20") or die(mysql_error());
while($row = mysql_fetch_assoc($get_em)){
	printf(" <tr>
 <td class='tablerow1' align='center'>
	 <strong><div style='font-size:12px'><a href='../user_profile.php?name=%s' target='_blank'>%s</a></strong> (ID: %d)
</td>
 <td class='tablerow2'>
	<div style='margin-top:6px'><a href='mailto:%s'>%s</a></div>
</td>
</tr>", $row['name'], $row['name'], $row['id'], $row['email'], $row['email']);
}
?>
 </table>
</div>
			  </td>
			 </tr>
		 </table>
		</div>
	 </td>
	</tr>
	
	</table>
 </td>
 <td valign='top' width='25%'>
	<div id='acp-update-wrapper'>
		<div class='homepage_pane_border' id='acp-update-normal'>
		 <div class='homepage_section'>HoloCMS Updater</div>
			<div style='font-size:12px;padding:4px; text-align:center'>
                                <p>
                                        <?php echo "v" . $holocms['version'] . " " . $holocms['stable'] . "[" . $holocms['title'] . "]"; ?>
				<br />
					Check out the <a href='http://forum.ragezone.com/showthread.php?t=418018'>HoloCMS topic</a> for the latest updates.
				</p>
			</div>
		</div>
		<br />
	</div>
	<div id='acp-update-wrapper'>
		<div class='homepage_pane_border' id='acp-update-normal'>
		 <div class='homepage_section'>Support HoloCMS</div>
			<div style='font-size:12px;padding:4px; text-align:center'>
				<p>
					HoloCMS is, always has been, and always will be, free software. To help keep the developer happy and allow him to buy a pizza <sup>every now and then</sup>, you can make a donation. This is completely optional, and if you decide not to donate, you won't miss out on any advantages, besides the developer's gratitude. All donations are processed by PayPal. Donate to the current developer to encourage development and get faster releases (results may vary).<br /><br />

					Donate to Yifan Lu (the current HoloCMS developer):<br />
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but21.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
					<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
					<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
					">
					</form>
					<br /><br />
					Donate to Meth0d (the orginal HoloCMS developer):<br /><br />
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					<input type="hidden" name="cmd" value="_donations">
					<input type="hidden" name="business" value="meth0d@meth0d.org">
					<input type="hidden" name="item_name" value="HoloCMS Donation">
					<input type="hidden" name="no_shipping" value="0">
					<input type="hidden" name="no_note" value="1">
					<input type="hidden" name="currency_code" value="USD">
					<input type="hidden" name="tax" value="0">
					<input type="hidden" name="lc" value="GB">
					<input type="hidden" name="bn" value="PP-DonationsBF">
					<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but21.gif" border="0" name="submit" alt="Donate to HoloCMS using PayPal - The safer, easier way to pay online.">
					<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
					</form><br />
				</p>
			</div>
		</div>
		<br />
	</div>
	<div id='acp-update-wrapper'>
		<div class='homepage_pane_border' id='acp-update-normal'>
		 <div class='homepage_section'>Need assistance?</div>
			<div style='font-size:12px;padding:4px; text-align:center'>
				<p>
					If you need Help with your copy of HoloCMS, your first stop should be the 'Help' tab in Housekeeping. If you still have problems, feel free to ask for support on <a href='http://forum.ragezone.com/f282' target='_BLANK'>RaGEZONE</a> or <a href='http://www.meth0d.org' target='_BLANK'>Meth0d dot org</a>.
				</p>
			</div>
		</div>
		<br />
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