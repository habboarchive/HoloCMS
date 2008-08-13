<?php
/*---------------------------------------------------+
| HoloCMS - Website and Content Management System
+----------------------------------------------------+
| Copyright © 2008 Meth0d
+----------------------------------------------------+
| HoloCMS is provided "as is" and comes without
| warrenty of any kind.
+---------------------------------------------------*/

include('core.php');

?>

<DYNAMIC_MENU>
	<TYPE>BUTTON_MENU</TYPE>
	<BUTTON>
	<DEFAULT_BUTTON_TEXT>Community</DEFAULT_BUTTON_TEXT>
	<BUTTON_ICON_URL>http://yifanlu.getmyip.com/obbah/toolbar/community.gif</BUTTON_ICON_URL>
	<BUTTON_TOOLTIP></BUTTON_TOOLTIP>
	<WITH_DROPDOWN>TRUE</WITH_DROPDOWN>
	</BUTTON>

	<MENU>
		<CAPTION />
		<ICON_URL />
		<HINT />
		<MENU>
			<CAPTION>Hot groups</CAPTION>
			<ICON_URL>http://yifanlu.getmyip.com/obbah/toolbar/hotgroups.gif</ICON_URL>
			<HINT />
			<?php

			$getem = mysql_query("SELECT * FROM groups_details ORDER BY RAND() LIMIT 10");

			while ($row = mysql_fetch_assoc($getem)) {
			$i++;
			$list_id = $i - 1;

			?>
				<MENU_ITEM>
					<CAPTION><?php echo $row['name']; ?></CAPTION>
					<ICON_URL>http://yifanlu.getmyip.com/obbah/toolbar/hotgroups.gif</ICON_URL>
					<DATA>
						<TYPE>LINK</TYPE>
						<LINK>
							<URL>http://yifanlu.getmyip.com/obbah_beta/group_profile.php?id=<?php echo $row['id']; ?></URL>
						</LINK>
					</DATA>
				</MENU_ITEM>
			<?php } ?>
		</MENU>
		<MENU>
			<CAPTION>Hot topics</CAPTION>
			<ICON_URL>http://yifanlu.getmyip.com/obbah/toolbar/topics.gif</ICON_URL>
			<HINT />
		<?php
		$i = 0;
		$getem = mysql_query("SELECT * FROM cms_forum_threads ORDER BY RAND() LIMIT 10") or die(mysql_error());

		while ($row = mysql_fetch_assoc($getem)) {
		$i++;
		$list_id = $i - 1;

		$tname = $row['title'];
		$tid = $row['id'];

		?>
	<MENU_ITEM>
				<CAPTION><?php echo $tname; ?></CAPTION>
				<ICON_URL>http://yifanlu.getmyip.com/obbah/toolbar/topics.gif</ICON_URL>
				<DATA>
					<TYPE>LINK</TYPE>
					<LINK>
						<URL>http://yifanlu.getmyip.com/obbah/viewthread.php?thread=<?php echo $tid; ?></URL>
					</LINK>
				</DATA>
			</MENU_ITEM>
		<?php } ?>
		</MENU>

		<SEPARATOR/>

				<MENU_ITEM>
					<CAPTION>Obbah Forums</CAPTION>
					<ICON_URL>http://yifanlu.getmyip.com/obbah/toolbar/communitylist.gif</ICON_URL>
					<DATA>
						<TYPE>LINK</TYPE>
						<LINK>
							<URL>http://obbahhotel.ob.funpic.org/forums/</URL>
						</LINK>
					</DATA>
		</MENU_ITEM>
	</MENU>

</DYNAMIC_MENU>