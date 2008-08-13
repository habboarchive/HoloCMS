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

<DYNAMIC_MENU>
	<TYPE>BUTTON_MENU</TYPE>
	<BUTTON>
	<DEFAULT_BUTTON_TEXT><?php echo $online_count; ?> Obbahs online</DEFAULT_BUTTON_TEXT>
	<BUTTON_ICON_URL>http://yifanlu.getmyip.com/obbah/toolbar/friendlist.gif</BUTTON_ICON_URL>
	<BUTTON_TOOLTIP></BUTTON_TOOLTIP>
	<WITH_DROPDOWN>TRUE</WITH_DROPDOWN>
	</BUTTON>

	<MENU>
		<CAPTION />
		<ICON_URL />
		<HINT />
		<MENU_ITEM>
			<CAPTION><?php echo $online_count; ?> Obbahs online</CAPTION>
			<ICON_URL>http://yifanlu.getmyip.com/obbah/toolbar/online.gif</ICON_URL>
			<DATA>
				<TYPE>LINK</TYPE>
				<LINK>
					<URL>http://yifanlu.getmyip.com/obbah/index.php</URL>
				</LINK>
			</DATA>
		</MENU_ITEM>
		<MENU_ITEM>
		<?php
		$ahowmanyusers = mysql_query("SELECT COUNT(*) FROM users") or die(mysql_error());
		$howmanyusers = mysql_result($ahowmanyusers, 0); ?>
			<CAPTION><?php echo $howmanyusers; ?> Obbahs registered</CAPTION>
			<ICON_URL>http://yifanlu.getmyip.com/obbah/toolbar/offline.gif</ICON_URL>
			<DATA>
				<TYPE>LINK</TYPE>
				<LINK>
					<URL>http://yifanlu.getmyip.com/obbah/index.php</URL>
				</LINK>
			</DATA>
		</MENU_ITEM>
		<?php if($loggedin == 0){ ?>
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
		<?php } else { ?>
		<MENU>
		<?php
		$i = 0;
		$getem = mysql_query("SELECT userid_from FROM messenger_friendrequests WHERE userid_to='".$my_id."' ORDER BY userid_from ASC") or die(mysql_error());
		$numfriends = mysql_num_rows($getem); ?>
			<CAPTION><?php echo $numfriends; ?> Friend Requests</CAPTION>
			<ICON_URL>http://yifanlu.getmyip.com/obbah/toolbar/requests.gif</ICON_URL>
			<HINT />
		<?php

		while ($row = mysql_fetch_assoc($getem)) {
		$i++;
		$list_id = $i - 1;

		$aname = mysql_query("SELECT name FROM users WHERE id='".$row['userid_from']."' LIMIT 1");
		$name = mysql_result($aname, 0);

		?>
			<MENU_ITEM>
				<CAPTION><?php echo $name; ?></CAPTION>
				<ICON_URL>http://yifanlu.getmyip.com/obbah/toolbar/requests.gif</ICON_URL>
				<DATA>
					<TYPE>LINK</TYPE>
					<LINK>
						<URL>http://yifanlu.getmyip.com/obbah/user_profile.php?name=<?php echo $name; ?></URL>
					</LINK>
				</DATA>
			</MENU_ITEM>
		<?php } ?>
		</MENU>
		<MENU>
		<?php
		$i = 0;
		$getem = mysql_query("SELECT friendid FROM messenger_friendships WHERE userid='".$my_id."' ORDER BY friendid ASC") or die(mysql_error());
		$numfriends = mysql_num_rows($getem); ?>
		<?php

		while ($row = mysql_fetch_assoc($getem)) {
		$i++;
		$list_id = $i - 1;

		$aname = mysql_query("SELECT name FROM users WHERE id='".$row['friendid']."' LIMIT 1");
		$name = mysql_result($aname, 0);

		?>
			<MENU_ITEM>
				<CAPTION><?php echo $name; ?></CAPTION>
				<ICON_URL>http://yifanlu.getmyip.com/obbah/toolbar/friendlist.gif</ICON_URL>
				<DATA>
					<TYPE>LINK</TYPE>
					<LINK>
						<URL>http://yifanlu.getmyip.com/obbah/user_profile.php?name=<?php echo $name; ?></URL>
					</LINK>
				</DATA>
			</MENU_ITEM>
		<?php } ?>
				<?php
				$i = 0;
				$getem = mysql_query("SELECT userid FROM messenger_friendships WHERE friendid='".$my_id."' ORDER BY userid ASC") or die(mysql_error());
				$numfriends2 = mysql_num_rows($getem); ?>
							<CAPTION><?php echo $numfriends + $numfriends2; ?> Friends</CAPTION>
							<ICON_URL>http://yifanlu.getmyip.com/obbah/toolbar/friendlist.gif</ICON_URL>
							<HINT />
				<?php

				while ($row = mysql_fetch_assoc($getem)) {
				$i++;
				$list_id = $i - 1;

				$aname = mysql_query("SELECT name FROM users WHERE id='".$row['userid']."' LIMIT 1");
				$name = mysql_result($aname, 0);

				?>
					<MENU_ITEM>
						<CAPTION><?php echo $name; ?></CAPTION>
						<ICON_URL>http://yifanlu.getmyip.com/obbah/toolbar/friendlist.gif</ICON_URL>
						<DATA>
							<TYPE>LINK</TYPE>
							<LINK>
								<URL>http://yifanlu.getmyip.com/obbah/user_profile.php?name=<?php echo $name; ?></URL>
							</LINK>
						</DATA>
					</MENU_ITEM>
		<?php } ?>
		</MENU>
		<?php } ?>
	</MENU>

</DYNAMIC_MENU>