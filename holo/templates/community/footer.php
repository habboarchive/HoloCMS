<?php
/*---------------------------------------------------+
| HoloCMS - Website and Content Management System
+----------------------------------------------------+
| Copyright © 2008 Meth0d
+----------------------------------------------------+
| HoloCMS is provided "as is" and comes without
| warrenty of any kind.
+---------------------------------------------------*/

if (!defined("IN_HOLOCMS")) { header("Location: ../../index.php"); exit; }

/*

	Please do not remove or edit the line defined below. If you do, you don't show much respect towards me.
	I have worked on HoloCMS for countless hours, I did this for free, without any personal gain for me at all.

	Please respect me and my work, and do not edit or remove the line defined below.

	If I do find people editing that line HoloCMS may go underground or I will simply stop developing, I'm
	prepared to go to the extreme.

	(Also, you're breaking the license if you do, and with that, copyright law)

	If you have any questions regarding this, feel free to e-mail me:
	meth0d at meth0d dot org

	Thanks in advance.

*/

?>
<?php if($noads == true){ ?>
<?php } elseif($pageid == "forum"){ ?>
<?php } else { ?>
<div id="column3" class="column">

<script type="text/javascript">
HabboView.run();

</script>
				<div class="habblet-container ">
						<div class="ad-container">
<?php $sql = mysql_query("SELECT * FROM cms_banners WHERE status = '1' ORDER BY id ASC");

while($row = mysql_fetch_assoc($sql)) { ?>
<?php if($row['advanced'] == "1"){
echo stripslashes($row['html'])."\n<br />\n";
}else{ ?>
<a target="blank" href="<?php echo $row['url']; ?>"><img src="<?php echo $row['banner']; ?>"></a><br />
<a target="blank" href="<?php echo $row['url']; ?>"><?php echo stripslashes($row['text']); ?></a><br />
<?php } ?>
<?php } ?>
						</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
</div>
<?php } ?>

<!--[if lt IE 7]>
<script type="text/javascript">
Pngfix.doPngImageFix();
</script>
<![endif]-->
    </div>
<div id="footer">
	<p><a href="index.php" target="_self">Homepage</a> | <a href="./disclaimer.php" target="_self">Terms of Service</a> | <a href="./privacy.php" target="_self">Privacy Policy</a></p>
	<?php /*@@* DO NOT EDIT OR REMOVE THE LINE BELOW WHATSOEVER! *@@ You ARE allowed to remove the link to the HoloCMS site though*/ ?>
	<p>Powered by <a href="http://www.holocms.com/">HoloCMS</a> &copy 2008 Meth0d & Parts by Yifan, sisija<br />HABBO is a registered trademark of Sulake Corporation. All rights reserved to their respective owner(s).<br />We are not endorsed, affiliated, or sponsered by Sulake Corporation Oy.</p>
	<?php /*@@* DO NOT EDIT OR REMOVE THE LINE ABOVE WHATSOEVER! *@@ You ARE allowed to remove the link to the HoloCMS site though*/ ?>
</div></div>

</div>

<script type="text/javascript">
HabboView.run();
</script>

<?php echo $analytics; ?>
</body>
</html>