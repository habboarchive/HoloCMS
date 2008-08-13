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

		if(getContent('client-widescreen') == "1"){
        $wide_enabled = true;
} else {
        $wide_enabled = false;
} ?>

<body id="client"<?php if($wide_enabled == true){ echo " class=\"wide\""; } ?>>
<div id="client-topbar" style="display:none">
  <div class="logo"><img src="web-gallery/images/popup/popup_topbar_habbologo.gif" alt="" align="middle"/></div>
  <div class="habbocount"><div id="habboCountUpdateTarget">
<?php echo $online_count; ?> <?php echo $shortname; ?>s online now
</div>
	<script language="JavaScript" type="text/javascript">
		setTimeout(function() {
			HabboCounter.init(600);
		}, 20000);
	</script>
</div>

  <div class="logout"><a href="#" onclick="self.close(); return false;">Close Hotel</a></div>
</div>