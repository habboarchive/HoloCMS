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

<!--[if lt IE 7]>
<script type="text/javascript">
Pngfix.doPngImageFix();
</script>
<![endif]-->

<div id="footer">
	<p><a href="index.php" target="_self">Homepage</a> | <a href="./disclaimer.php" target="_self">Terms of Service</a> | <a href="./privacy.php" target="_self">Privacy Policy</a><?php if($rank[iAdmin] == "1" || $user_rank > 5){ ?> | <a href="./housekeeping.php" target="_self"><b>Housekeeping</b></a><?php } ?></p>
	<?php /*@@* DO NOT EDIT OR REMOVE THE LINE BELOW WHATSOEVER! *@@*/ ?>
	<p>Powered by HoloCMS &copy 2008 Meth0d & Yifan<br><?php echo $locale['copyright_habbo']; ?></p>
	<?php /*@@* DO NOT EDIT OR REMOVE THE LINE ABOVE WHATSOEVER! *@@*/ ?>
</div>			</div>
        </div>
    </div>
</div>

<script type="text/javascript">
HabboView.run();
</script>

</body>
</html>