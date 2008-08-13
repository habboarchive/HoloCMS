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

session_start();

if(!session_is_registered(username)){
$loggedin = false;
} else {
$loggedin = true;
}

?>
<?php if($loggedin == 0){ ?>
<DYNAMIC_MENU>
	<TYPE>BUTTON_MENU</TYPE>
	<BUTTON>
	<DEFAULT_BUTTON_TEXT>Sign in</DEFAULT_BUTTON_TEXT>
	<BUTTON_ICON_URL>http://yifanlu.getmyip.com/obbah/toolbar/key.gif</BUTTON_ICON_URL>
	<BUTTON_TOOLTIP></BUTTON_TOOLTIP>
	<WITH_DROPDOWN>TRUE</WITH_DROPDOWN>
	</BUTTON>

	<MENU>
		<CAPTION />
		<ICON_URL />
		<HINT />
		<MENU_ITEM>
			<CAPTION>Sign in</CAPTION>
			<ICON_URL>http://yifanlu.getmyip.com/obbah/toolbar/key.gif</ICON_URL>
			<DATA>
				<TYPE>LINK</TYPE>
				<LINK>
					<URL>http://yifanlu.getmyip.com/obbah/index.php</URL>
				</LINK>
			</DATA>
		</MENU_ITEM>
	</MENU>
</DYNAMIC_MENU>
<?php }else{ ?>
<DYNAMIC_MENU>
	<TYPE>BUTTON_MENU</TYPE>
	<BUTTON>
	<DEFAULT_BUTTON_TEXT>My Obbah</DEFAULT_BUTTON_TEXT>
	<BUTTON_ICON_URL>http://yifanlu.getmyip.com/obbah/toolbar/myhabbo.gif</BUTTON_ICON_URL>
	<BUTTON_TOOLTIP></BUTTON_TOOLTIP>
	<WITH_DROPDOWN>TRUE</WITH_DROPDOWN>
	</BUTTON>

	<MENU>
		<CAPTION />
		<ICON_URL />
		<HINT />
		<MENU>
		<?php
		$i = 0;
		$getem = mysql_query("SELECT * FROM cms_minimail WHERE to_id='".$my_id."' AND read_mail = '0' ORDER BY id DESC") or die(mysql_error());
		$numfriends = mysql_num_rows($getem); ?>
			<CAPTION><?php echo $numfriends; ?> new mail</CAPTION>
			<ICON_URL>http://yifanlu.getmyip.com/obbah/toolbar/minimail.gif</ICON_URL>
			<HINT />
		<?php

		while ($row = mysql_fetch_assoc($getem)) {
		$i++;
		$list_id = $i - 1;

		$namefromid = mysql_query("SELECT name FROM users WHERE id='".$row['friendid']."' LIMIT 1");
		$namefrom = mysql_result($namefromid, 0);
		$message = substr($row['message'], 0, 10)

		?>
			<MENU_ITEM>
				<CAPTION><?php echo $namefrom; ?>: <?php echo $message; ?></CAPTION>
				<ICON_URL>http://yifanlu.getmyip.com/obbah/toolbar/minimail.gif</ICON_URL>
				<DATA>
					<TYPE>LINK</TYPE>
					<LINK>
						<URL>http://yifanlu.getmyip.com/obbah/user_profile.php?name=<?php echo $namefrom; ?></URL>
					</LINK>
				</DATA>
			</MENU_ITEM>
		<?php } ?>
		</MENU>
		<MENU_ITEM>
			<CAPTION>My Obbah</CAPTION>
			<ICON_URL>http://yifanlu.getmyip.com/obbah/toolbar/myhabbo.gif</ICON_URL>
			<DATA>
				<TYPE>LINK</TYPE>
				<LINK>
					<URL>http://yifanlu.getmyip.com/obbah/me.php</URL>
				</LINK>
			</DATA>
		</MENU_ITEM>
				<MENU_ITEM>
					<CAPTION>My homepage</CAPTION>
					<ICON_URL>http://yifanlu.getmyip.com/obbah/toolbar/home.gif</ICON_URL>
					<DATA>
						<TYPE>LINK</TYPE>
						<LINK>
							<URL>http://yifanlu.getmyip.com/obbah/user_profile.php?name=<?php echo $rawname; ?></URL>
						</LINK>
					</DATA>
		</MENU_ITEM>

		<SEPARATOR/>

		<MENU>
				<CAPTION>My Groups</CAPTION>
				<ICON_URL>http://yifanlu.getmyip.com/obbah/toolbar/mygroups.gif</ICON_URL>
				<HINT />
			<?php

			$getem = mysql_query("SELECT * FROM groups_memberships WHERE userid='".$my_id."'");

			while ($row = mysql_fetch_assoc($getem)) {
			$i++;
			$list_id = $i - 1;

			$sql2 = mysql_query("SELECT name FROM groups_details WHERE id ='".$row['groupid']."' LIMIT 1");
			$groupname = mysql_result($sql2, 0);

			?>
				<MENU_ITEM>
					<CAPTION><?php echo $groupname; ?></CAPTION>
					<ICON_URL>http://yifanlu.getmyip.com/obbah/toolbar/mygroups.gif</ICON_URL>
					<DATA>
						<TYPE>LINK</TYPE>
						<LINK>
							<URL>http://yifanlu.getmyip.com/obbah_beta/group_profile.php?id=<?php echo $row['groupid']; ?></URL>
						</LINK>
					</DATA>
				</MENU_ITEM>
			<?php } ?>
		</MENU>
	</MENU>

</DYNAMIC_MENU>
<?php } ?>