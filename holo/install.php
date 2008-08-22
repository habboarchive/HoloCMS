<?php
/*===================================================+
|| # HoloCMS - Website and Content Management System
|+===================================================+
|| # Copyright � 2008 Meth0d. All rights reserved.
|| # http://www.meth0d.org
|+===================================================+
|| # HoloCMS is provided "as is" and comes without
|| # warrenty of any kind. HoloCMS is free software!
|+===================================================*/

require_once('./includes/version.php');

define("IN_HOLOCMS", TRUE);
$install_mode = true;

require_once('./includes/inc.crypt.php');

$H = date('H');
$i = date('i');
$s = date('s');
$m = date('m');
$d = date('d');
$Y = date('Y');

$date_full = date('d-m-Y H:i:s',mktime($H,$i,$s,$m,$d,$Y));
$date_normal = date('d-m-Y',mktime($m,$d,$Y));

session_start();

///////////////////////////////////////////////////////////////////////////////////////////////

function dbquery($query) {
	$result = @mysql_query($query);
	if (!$result) {
		echo mysql_error();
		return false;
	} else {
		return $result;
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////

function writeconfig($host, $username, $password, $db, $sitepath){
		$config = "<?php
/*===================================================+
|| # HoloCMS - Website and Content Management System
|+===================================================+
|| # Copyright � 2008 Meth0d. All rights reserved.
|| # http://www.meth0d.org
|+===================================================+
|| # HoloCMS is provided \"as is\" and comes without
|| # warrenty of any kind. HoloCMS is free software!
|+===================================================*/

/*-------------------------------------------------------*\
| ****** NOTE REGARDING THE VARIABLES IN THIS FILE ****** |
+---------------------------------------------------------+
| If you get any errors while attempting to connect to    |
| MySQL, you will need to email your webhost because we   |
| cannot tell you the correct values for the variables    |
| in this file.                                           |
\*-------------------------------------------------------*/

//	****** MASTER DATABASE SETTINGS ******
//	These are the settings required to connect to your MySQL Database.
"."$"."sqlhostname = \"".$host."\";
"."$"."sqlusername = \"".$username."\";
"."$"."sqlpassword = \"".$password."\";
"."$"."sqldb = \"".$db."\";

//	****** STATUS CHECKS SYSTEM ******
//	This option will allow HoloCMS to perform full status checks. This,
//	however, slows down your site A LOT. It is therefore disabled by
//	default.
"."$"."enable_status_image = \"1\";

//	****** SITE PATH ******
//	The full URL to your site; with an slash added on the end.
"."$"."path = \"".$sitepath."\";

//	****** REFFERAL REWARD ******
//	The amount of credits a user recieves upon referring a friend to the
//	hotel. Set to '500' by default.
"."$"."reward = \"50\";

//	****** HOLOCMS SYSTEM ADMINISTRATOR ******
//	User ID of the System Administrator. Will be granted access to sensitive
//	features. Set to '1' by default. This setting will not change your
//	ingame priveliges.
"."$"."sysadmin = \"1\";

?>";
	$temp = fopen("config.php","w");
	
	if (!fwrite($temp, $config)) {
		fclose($temp);
		return false;
	} else {
		fclose($temp);
		return true;
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////

$step = $_GET['step'];
if($step < 0 || $step > 5 || !is_numeric($step)){ $step = 0; }

///////////////////////////////////////////////////////////////////////////////////////////////

echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<title>HoloCMS - Setup</title>
<link rel=\"shortcut icon\" href=\"favicon.ico\" type=\"image/vnd.microsoft.icon\" />
<link rel=\"stylesheet\" href=\"web-gallery/v2/styles/install.css\" type=\"text/css\" />

</head>
<body>
<h1 id=\"logo\"><img alt=\"HoloCMS\" src=\"housekeeping/images/holocms-logo.png\" /></h1>\n";

///////////////////////////////////////////////////////////////////////////////////////////////

if($step == 0){
echo "<p><strong>Welcome to HoloCMS</strong></p>\n";
echo "<p>Welcome to HoloCMS. Before you can start using your new website, we'll need to set up a few things. In order for the installation to be successfull, you will need to know all of the following things.</p>\n";
echo "<ol>\n";
echo "	<li>MySQL Database name</li>\n";
echo "	<li>MySQL Username</li>\n";
echo "	<li>MySQL Password</li>\n";
echo "	<li>MySQL Host (if not on localhost)</li>\n";
echo "</ol>\n";
echo "<p><b>In order for this installation to complete successfully, the installer script must have access to write to config.php. For assistance with giving write permissions or connecting to your MySQL database, please consult your host/ISP.</b></p>\n";
echo "<p>Please be aware that this installer will override (and thus empty) any existing (and required) CMS tables in your MySQL Database, and possibly modify Holograph Emulator tables where needed. Please read the compatability information below for optimal performance and integration.</p>\n";
echo "<p><strong>HoloCMS Version:</strong> ".$holocms['version']." ".$holocms['stable']." [".$holocms['title']."]<br />\n<strong>HoloCMS Build Date:</strong> ".$holocms['date']."<br />\n<strong>Holograph Emulator Compatability:</strong> Build for <a href='http://trac2.assembla.com/holograph/changeset/".$holograph['revision']."' target='_blank'>Revision ".$holograph['revision']."</a> (".$holograph['type'].")</p>\n";
echo "<p><b><i><u>In order to use this unoffical HoloCMS build you do/have done the following: 1) Have an existing HoloCMS MySQL database, 2) Dropped all tables that begins with cms_, and 3) Excute cms_tables.sql located in the root of the download.</b></i></u>\n";
echo "<p><a href=\"install.php?step=1\" class=\"button\">New Installation</a>\n";

///////////////////////////////////////////////////////////////////////////////////////////////

} elseif($step == 1){

if(isset($_POST['name'])){
	$write = WriteConfig($_POST['host'], $_POST['username'], $_POST['pass'], $_POST['name'], $_POST['path']);
	if(!$write){
		$message = "Unable to write config file, this script may not have the correct read/write permissions; please CHMOD config.php to 777, but remember to return it to normal after you finish using the installer.";
	} else {
		$db_connect = @mysql_connect($_POST['host'], $_POST['username'], $_POST['pass']);
		$db_select = @mysql_select_db($_POST['name']);
		if (!$db_connect) {
			$message = "Unable to connect to MySQL server. Please double-check the hostname, username, and password you have provided.";
		} elseif (!$db_select) {
			$message = "Unable to select database. Please double-check the database name you have provided.";
		} else {
			$hideform = true;
		}
	}
}

if(empty($_POST['name'])){ $_POST['name'] = "holodb"; }
if(empty($_POST['username'])){ $_POST['username'] = "root"; }
if(empty($_POST['host'])){ $_POST['host'] = "localhost"; }
if(empty($_POST['path'])){ $_POST['path'] = "http://www.mysite.com/cms/"; }

	if($hideform !== true){
		echo "<form method=\"post\" action=\"install.php?step=1&key=submit\">
	<p><strong>MySQL / Site Path</strong></p>\n";
		if(isset($message)){ echo "<p><b>" . $message . "</b></p>"; }	
		echo "<p>Please enter your MySQL database credentials below, and the path to your site. If you're not sure about the MySQL settings, please contact your host.</p>
	<table class=\"form-table\">
		<tr>
			<th scope=\"row\">Database Name</th>
			<td><input name=\"name\" type=\"text\" size=\"25\" value=\"".$_POST['name']."\" /></td>
			<td>The name of the shared Holograph Emulator / HoloCMS database.</td>
		</tr>
		<tr>
			<th scope=\"row\">User Name</th>
			<td><input name=\"username\" type=\"text\" size=\"25\" value=\"".$_POST['username']."\" /></td>
			<td>Your MySQL username, usually 'root'.</td>
		</tr>
		<tr>
			<th scope=\"row\">Password</th>
			<td><input name=\"pass\" type=\"text\" size=\"25\" value=\"".$_POST['pass']."\" /></td>
			<td>The password linked to the MySQL Username.</td>
		</tr>
		<tr>
			<th scope=\"row\">Database Host</th>
			<td><input name=\"host\" type=\"text\" size=\"25\" value=\"".$_POST['host']."\" /></td>
			<td>90% chance you won't need to change this value.</td>
		</tr>
		<tr>
			<th scope=\"row\">Site Path</th>
			<td><input name=\"path\" type=\"text\" size=\"25\" value=\"".$_POST['path']."\" /></td>
			<td>The full path to your HoloCMS directory, with a ending slash (/).</td>
		</tr>
	</table>
	<h2 class=\"step\">
	<input name=\"submit\" type=\"submit\" value=\"Submit\" class=\"button\" />
	</h2>
</form>";
	} else {
		echo "<p><strong>Successfully connected to MySQL database.</strong></p>\n";
		echo "<p>The configuration file has been written and the MySQL connection has been set up successfully. Press 'Start Installation' to start the automated installation process, or press 'Change Settings' to go back to the previous step.<br /><br /><strong>Warning</strong><br />This will rebuild any existing HoloCMS database tables and add new ones where needed, and thus also reset them. You will be given to oppertunity to create a admin user later on during the installation process.<strong>WARNING: This will drop all databases that begins with cms_ make sure you backed up your data!</strong></p>";
		echo "<p><a href=\"install.php?step=2\" class=\"button\">Start Installation</a>\n&nbsp;<a href=\"install.php?step=1\" class=\"button\">Go back</a></p>\n";
	}

///////////////////////////////////////////////////////////////////////////////////////////////

} elseif($step == 2){

	// We've written the config, so let's use that
	require_once('config.php');
	require_once('./includes/mysql.php');

	// note that the script would have died in a mysql error if there was a problem with the mysql config by now
	// so we should be safe to do anything we please here!

		echo "<p><strong>Installation</strong></p>\n";
		echo "<p>The installation is now in process, this should take only a second or two on an average system to complete.</p>\n";
		echo "<p>Dropping all cms_ databases...</p>\n";
		mysql_query("DROP TABLE IF EXISTS cms_application_forms");
		mysql_query("DROP TABLE IF EXISTS cms_application_questions");
		mysql_query("DROP TABLE IF EXISTS cms_applications");
		mysql_query("DROP TABLE IF EXISTS cms_banners");
		mysql_query("DROP TABLE IF EXISTS cms_collectables");
		mysql_query("DROP TABLE IF EXISTS cms_content");
		mysql_query("DROP TABLE IF EXISTS cms_forum_posts");
		mysql_query("DROP TABLE IF EXISTS cms_forum_threads");
		mysql_query("DROP TABLE IF EXISTS cms_homes_catalouge");
		mysql_query("DROP TABLE IF EXISTS cms_homes_inventory");
		mysql_query("DROP TABLE IF EXISTS cms_homes_stickers");
		mysql_query("DROP TABLE IF EXISTS cms_minimail");
		mysql_query("DROP TABLE IF EXISTS cms_news");
		mysql_query("DROP TABLE IF EXISTS cms_noobgifts");
		mysql_query("DROP TABLE IF EXISTS cms_recommended");
		mysql_query("DROP TABLE IF EXISTS cms_tags");
		mysql_query("DROP TABLE IF EXISTS cms_system");
		echo "<p>Making new cms_ databases...</p>\n";
		mysql_query("CREATE TABLE `cms_application_forms` (
  `id` int(5) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '0' COMMENT 'For what rank can people applicate?',
  `introduction` text NOT NULL COMMENT 'What''s the description of the rank it self?',
  `requirements` text NOT NULL COMMENT 'What are the requirments if you want to do this job?',
  `hconly` int(1) NOT NULL default '0' COMMENT 'Can only people who are HC applicate or everyone?',
  `username` int(1) NOT NULL default '0' COMMENT 'Show username in application form?',
  `realname` int(1) NOT NULL default '0' COMMENT 'Need people to fill in their real name?',
  `birth` int(1) NOT NULL default '0' COMMENT 'Show birth in application form?',
  `sex` int(1) NOT NULL default '0' COMMENT 'Need people to fill in what there sex is (male/female/shemale)?',
  `country` int(1) NOT NULL default '0' COMMENT 'Need people to fill in, in what country they live?',
  `general_information` int(1) NOT NULL default '0' COMMENT 'Need people to fill in general information (reasons why you''re interested/why the staff should choose you)?',
  `experience` int(1) NOT NULL default '0' COMMENT 'Need people to fill in if they have work experience?',
  `education` int(1) NOT NULL default '0' COMMENT 'Need people to fill in what level they (did) study?',
  `additional_information` int(1) NOT NULL default '0' COMMENT 'Need people to fill in hobbies or interests?',
  `show_disclaimer` int(1) NOT NULL default '0' COMMENT 'Must there be a disclaimer that the people who applicates need to accept?',
  `disclaimer_text` text NOT NULL COMMENT 'The disclaimer it self (shown if configurated).',
  `enabled` int(11) NOT NULL default '0' COMMENT 'Can you right now apply for this rank?',
  `deleted` int(1) NOT NULL default '0' COMMENT 'If you ''delete'' a application form it will be invisible. We don''t delete it in the sql table so no errors come up if someone applied for this rank.',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='You DO NOT find any applications in this table, only the for'");
		mysql_query("CREATE TABLE `cms_application_forms` (
  `id` int(5) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '0' COMMENT 'For what rank can people applicate?',
  `introduction` text NOT NULL COMMENT 'What''s the description of the rank it self?',
  `requirements` text NOT NULL COMMENT 'What are the requirments if you want to do this job?',
  `hconly` int(1) NOT NULL default '0' COMMENT 'Can only people who are HC applicate or everyone?',
  `username` int(1) NOT NULL default '0' COMMENT 'Show username in application form?',
  `realname` int(1) NOT NULL default '0' COMMENT 'Need people to fill in their real name?',
  `birth` int(1) NOT NULL default '0' COMMENT 'Show birth in application form?',
  `sex` int(1) NOT NULL default '0' COMMENT 'Need people to fill in what there sex is (male/female/shemale)?',
  `country` int(1) NOT NULL default '0' COMMENT 'Need people to fill in, in what country they live?',
  `general_information` int(1) NOT NULL default '0' COMMENT 'Need people to fill in general information (reasons why you''re interested/why the staff should choose you)?',
  `experience` int(1) NOT NULL default '0' COMMENT 'Need people to fill in if they have work experience?',
  `education` int(1) NOT NULL default '0' COMMENT 'Need people to fill in what level they (did) study?',
  `additional_information` int(1) NOT NULL default '0' COMMENT 'Need people to fill in hobbies or interests?',
  `show_disclaimer` int(1) NOT NULL default '0' COMMENT 'Must there be a disclaimer that the people who applicates need to accept?',
  `disclaimer_text` text NOT NULL COMMENT 'The disclaimer it self (shown if configurated).',
  `enabled` int(11) NOT NULL default '0' COMMENT 'Can you right now apply for this rank?',
  `deleted` int(1) NOT NULL default '0' COMMENT 'If you ''delete'' a application form it will be invisible. We don''t delete it in the sql table so no errors come up if someone applied for this rank.',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='You DO NOT find any applications in this table, only the for';");
		mysql_query("CREATE TABLE `cms_applications` (
  `id` int(15) NOT NULL auto_increment,
  `rankname` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `realname` varchar(50) NOT NULL,
  `birth` varchar(15) NOT NULL,
  `sex` varchar(10) NOT NULL,
  `country` varchar(50) NOT NULL,
  `general_information` text NOT NULL,
  `experience` text NOT NULL,
  `education` varchar(50) NOT NULL,
  `additional_information` text NOT NULL,
  `accepted_disclaimer` int(1) NOT NULL default '0',
  `admin_reacted` int(1) NOT NULL default '0',
  `admin_read` int(1) NOT NULL default '0',
  `admin_deleted` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
		mysql_query("CREATE TABLE `cms_banners` (
  `id` int(35) NOT NULL auto_increment,
  `text` varchar(50) NOT NULL,
  `banner` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `status` smallint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;");
		mysql_query("CREATE TABLE `cms_collectables` (
  `id` int(15) NOT NULL auto_increment,
  `title` varchar(50) NOT NULL default 'Unknown furniture',
  `image_small` varchar(255) default NULL,
  `image_large` varchar(255) NOT NULL,
  `tid` int(20) NOT NULL default '0',
  `description` varchar(175) NOT NULL default 'No description given!',
  `month` int(2) NOT NULL default '1',
  `year` int(2) NOT NULL,
  `showroom` int(2) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;");
		mysql_query("CREATE TABLE `cms_content` (
  `contentkey` text NOT NULL,
  `contentvalue` text NOT NULL,
  `setting_title` text NOT NULL,
  `setting_desc` text NOT NULL,
  `fieldtype` enum('1','2','3') NOT NULL default '1',
  `category` int(11) NOT NULL default '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
		mysql_query("CREATE TABLE `cms_forum_posts` (
  `id` int(11) NOT NULL auto_increment,
  `threadid` int(11) NOT NULL default '0',
  `message` text NOT NULL,
  `author` varchar(25) NOT NULL,
  `date` varchar(30) NOT NULL,
  `edit_date` varchar(30) NOT NULL,
  `edit_author` varchar(25) NOT NULL,
  `forumid` int(10) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=63 DEFAULT CHARSET=latin1;");
		mysql_query("CREATE TABLE `cms_forum_threads` (
  `id` int(4) NOT NULL auto_increment,
  `type` tinyint(4) NOT NULL,
  `title` varchar(30) NOT NULL,
  `author` varchar(25) NOT NULL,
  `date` varchar(35) NOT NULL,
  `lastpost_author` varchar(25) NOT NULL,
  `lastpost_date` varchar(35) NOT NULL,
  `views` int(11) NOT NULL,
  `posts` tinyint(4) NOT NULL,
  `unix` varchar(40) NOT NULL,
  `forumid` int(10) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;");
		mysql_query("CREATE TABLE `cms_homes_catalouge` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `type` varchar(1) NOT NULL,
  `subtype` varchar(1) NOT NULL,
  `data` text NOT NULL,
  `price` int(11) NOT NULL,
  `amount` int(11) NOT NULL default '1',
  `category` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2655 DEFAULT CHARSET=latin1;");
		mysql_query("CREATE TABLE `cms_homes_inventory` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL,
  `type` varchar(1) NOT NULL,
  `subtype` varchar(1) NOT NULL,
  `data` text NOT NULL,
  `amount` varchar(3) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3696 DEFAULT CHARSET=latin1;");
		mysql_query("CREATE TABLE `cms_homes_stickers` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL,
  `x` varchar(6) NOT NULL default '1' COMMENT 'left',
  `y` varchar(6) NOT NULL default '1' COMMENT 'top',
  `z` varchar(6) NOT NULL default '1' COMMENT 'z-index',
  `data` text NOT NULL,
  `type` varchar(1) NOT NULL default '1',
  `subtype` varchar(1) NOT NULL default '0' COMMENT 'Widget Type (if widget)',
  `skin` text NOT NULL,
  `groupid` int(11) NOT NULL default '-1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3944 DEFAULT CHARSET=latin1;");
		mysql_query("CREATE TABLE `cms_minimail` (
  `senderid` int(11) NOT NULL,
  `to_id` int(11) default NULL,
  `subject` varchar(30) NOT NULL,
  `date` varchar(30) NOT NULL,
  `message` text NOT NULL,
  `read_mail` enum('0','1') NOT NULL default '0',
  `id` int(11) NOT NULL auto_increment,
  `deleted` int(10) default '0',
  `conversationid` int(10) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=latin1;");
		mysql_query("CREATE TABLE `cms_news` (
  `num` int(4) NOT NULL auto_increment,
  `title` text collate latin1_general_ci NOT NULL,
  `category` text collate latin1_general_ci NOT NULL,
  `topstory` varchar(100) collate latin1_general_ci NOT NULL,
  `short_story` text collate latin1_general_ci NOT NULL,
  `story` longtext collate latin1_general_ci NOT NULL,
  `date` date NOT NULL,
  `author` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`num`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='HoloCMS';");
		mysql_query("CREATE TABLE `cms_noobgifts` (
  `id` int(20) NOT NULL auto_increment,
  `userid` int(30) NOT NULL,
  `gift` int(2) NOT NULL,
  `read` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
		mysql_query("CREATE TABLE `cms_recommended` (
  `id` int(10) NOT NULL auto_increment,
  `rec_id` int(10) default NULL,
  `type` varchar(10) default 'group',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;");
		mysql_query("CREATE TABLE `cms_tags` (
  `id` int(255) NOT NULL auto_increment,
  `ownerid` int(11) NOT NULL default '0',
  `tag` varchar(25) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=394 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='HoloCMS';");
		mysql_query("CREATE TABLE `cms_system` (
  `sitename` varchar(30) collate latin1_general_ci NOT NULL,
  `shortname` varchar(30) collate latin1_general_ci NOT NULL,
  `site_closed` enum('0','1') collate latin1_general_ci NOT NULL COMMENT 'Maintenance Mode',
  `enable_sso` enum('0','1') collate latin1_general_ci NOT NULL,
  `language` varchar(2) collate latin1_general_ci NOT NULL,
  `ip` varchar(50) collate latin1_general_ci NOT NULL,
  `port` varchar(5) collate latin1_general_ci NOT NULL,
  `texts` varchar(250) collate latin1_general_ci NOT NULL,
  `variables` varchar(250) collate latin1_general_ci NOT NULL,
  `dcr` varchar(250) collate latin1_general_ci NOT NULL,
  `reload_url` varchar(250) collate latin1_general_ci NOT NULL,
  `localhost` enum('0','1') collate latin1_general_ci NOT NULL COMMENT 'Local server?',
  `start_credits` int(11) NOT NULL default '0',
  `admin_notes` text collate latin1_general_ci NOT NULL,
  `loader` int(1) NOT NULL default '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='HoloCMS';");
		mysql_query("CREATE TABLE `cms_guestbook` (
  `id` int(10) NOT NULL auto_increment,
  `message` varchar(1000) default NULL,
  `time` varchar(1000) default NULL,
  `widget_id` int(10) default NULL,
  `userid` int(10) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;");
		
		echo "<strong>done!</strong><br />\n";
		echo "Inserting data and building default content..";

		mysql_query("INSERT INTO `cms_news` (`num`, `title`, `category`, `topstory`, `short_story`, `story`, `date`, `author`) VALUES (2, 'Installation Complete', 'HoloCMS', 'http://images.habbohotel.com/c_images/Top_Story_Images/Rel22_commu_topstory_300x187.gif', 'Welcome, to your brand new HoloCMS installation!', 'Hello, and thank you very much for choosing HoloCMS; a rich and complete website and content management system for Holograph Emulator, that you''ve just installed .. successfully. And guess what? Everything seems to be working just fine!\r\n\r\nI hope you enjoy using HoloCMS and Holograph Emulator - if you need any future support, just ask away on the <a href=''http://forum.ragezone.com/forumdisplay.php?f=282'' target=''_blank''>RaGEZONE Habbo board</a>.\r\n\r\nYou can edit or delete this example news article by logging into Housekeeping (accessible through the green ''Housekeeping'' tab in the navigation). Once you are logged in in Housekeeping, you will find yourself on the Dashboard. You will find the page ''Manage News Articles'' under the site tab.\r\n\r\nThanks again, and have fun!\r\n\r\n\r\nOn behalf of everyone involved,', '0000-00-00', 'Meth0d');") or die(mysql_error());
		mysql_query("INSERT INTO `cms_system` (`sitename`, `shortname`, `site_closed`, `enable_sso`, `language`, `ip`, `port`, `texts`, `variables`, `dcr`, `reload_url`, `localhost`, `start_credits`, `admin_notes`, `loader`) VALUES ('Holo Hotel', 'Holo', '0', '1', 'en', '127.0.0.1', '21', 'http://www.habbohotel.co.uk/gamedata/external?id=external_texts', 'http://www.habbohotel.co.uk/gamedata/external?id=external_variables', 'http://images.habbohotel.co.uk/dcr/r22_20080519_1524_5590_66afcf07d8b708feecf6e2e0e797ec09/habbo.dcr', '".$path."client.php', '0', 500, 'You can use this to keep notes for yourself, other mods or admins, etc', '1');") or die(mysql_error());
		mysql_query("UPDATE users SET postcount = '0'") or die(mysql_error());
		mysql_query("INSERT INTO `cms_content` (`contentkey`, `contentvalue`, `setting_title`, `setting_desc`, `fieldtype`, `category`) VALUES
('credits1', 'Credits are the hotel\'s currency. You can use them to buy all kinds of things, from rubber ducks and sofas, to Habbo Club membership, jukeboxes and teleports.', 'Credits Content Box 1', 'The text within a content box on the credits page.', '1', '3'),
('credits2', '<img class=\'credits-image\' src=\'./web-gallery/album1/palmchair.gif\' align=\'left\' />To buy furniture or play games, you need <b>credits</b>. We provide you with free credits on registration, and if you run out, there are several ways you can earn more credits:<li>* Refer a friend to the hotel and earn credits</li><li>* Ask a staff member ingame</li><li>* Redeem a voucher if you have one</li><li>* Trade your furniture with others for credit furniture</li>', 'Credits Content Box 2', 'The text within a content box on the credits page.', '1', '3'),
('credits1-heading', 'What are credits?', 'Credits Content Box 1 Heading', 'The title (heading) of Credit Content Box 1.', '1', '3'),
('credits2-heading', 'Get credits!', 'Credit Content Box 2 Heading', 'The title (heading) of Credit Content Box 2.', '1', '3'),
('staff1', 'You can find the staff members all over the hotel -- in the public rooms, their own rooms, or that dark little corner in your room. But how can you call them if you actually need them!? Easy. If it\'s urgent, use the Call for Help system ingame by using the blue questionmark in the right bottom of your screen. If it isn\'t urgent, use the Help Tool on the website.', 'Staff Content Box 1', 'The text within a content box on the staff page (if enabled).', '1', '2'),
('staff2', 'So you want that sexy staff badge next to your name in the hotel and on the site? Do you want to join Holo Hotel\'s moderation team? Keep your eyes focused on this section and the news -- if we\'re looking for staff it will be announced there, and surely you won\'t miss it!', 'Staff Content Box 2', 'The text within a content box on the staff page (if enabled).', '1', '2'),
('staff1-heading', 'Need our help?', 'Staff Content Box 1 Heading', 'The title (heading) of Staff Content Box 1.', '1', '2'),
('staff2-heading', 'Joining the Team', 'Staff Content Box 2 Heading', 'The title (heading) of Staff Content Box 2.', '1', '2'),
('staff1-color', 'green', 'Staff Content Box 1 Color', 'Only valid colors defined in CSS such as ''orange'', ''blue'', etc', '3', '2'),
('staff2-color', 'green', 'Staff Content Box 2 Color', 'Only valid colors defined in CSS such as ''orange'', ''blue'', etc', '3', '2'),
('mod_staff-enabled', '1', 'Staff Module Enabled', 'This determines wether the Staff Module (staff.php) will be displayed/enabled.', '2', '2'),
('client-widescreen', '1', 'Client in Widescreen', 'This determines wether the game client should display in widescreen mode or not.', '2', '1'),
('birthday-notifications', '1', 'Birthday Notifications Enabled', 'This will determine wether a ''Happy birthday'' message will be shown on a user''s birthday.', '2', '1'),
('allow-group-purchase', '1', 'Group Purchasing Enabled', 'This determines wether new groups can be created or not.', '2', '1'),
('forum-enabled', '1', 'Forum Enabled', 'This determines wether the Discussion Board will be shown/enabled.', '2', '4'),
('smilies-enabled', '1', 'Smilies Enabled', 'This determines wether Smilies will be shown on the Discussion Board and on Homes.', '2', '4'),
('enable-flash-promo', '1', 'Enable Flash Promo', 'This determines wether the Flash Promo on the login page will be displayed. If disabled, a HTML version will be used.', '2', '1'),
('allow-guests', '1', 'Allow guests?', 'This determines wether guest mode is enabled. Guest Mode allows you to visit (parts of) your site (with limitations) without logging in.', '2', '1'),
('hc-maxmonths', '6', 'HC Months Limit', 'This will limit the allowed amount of subscribed Club months per user at once to this number. Set to ''0'' to have no limit.', '1', '1');") or die(mysql_error());

		echo "<strong>done!</strong><br />\n";		
		echo "Transforming HoloDB tables where needed (most will not return errors on failure)..<strong>done!</strong><br />\n";
		@mysql_query("ALTER TABLE `users` CHANGE `id` `id` INT( 15 ) NOT NULL AUTO_INCREMENT"); // do not die in error!
		@mysql_query("ALTER TABLE `groups_details` ADD `type` VARCHAR( 1 ) NOT NULL ;");
		@mysql_query("ALTER TABLE `groups_details` CHANGE `type` `type` INT( 1 ) NOT NULL DEFAULT '0' ");
		@mysql_query("ALTER TABLE `groups_memberships` CHANGE `iscurrent` `is_current` ENUM( '0', '1' ) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '0' ");
		@mysql_query("ALTER TABLE `groups_memberships` ADD `is_pending` ENUM( '0', '1' ) NOT NULL DEFAULT '0';");
		@mysql_query("ALTER TABLE `groups_details` CHANGE `id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT ");
		@mysql_query("UPDATE groups_details SET type = '2' WHERE type = ''");
		mysql_query("ALTER TABLE `users` ADD `noob` INT( 1 ) NOT NULL DEFAULT '0' AFTER `ipaddress_last` ,
ADD `gift` SMALLINT( 2 ) NOT NULL AFTER `noob` ,
ADD `sort` SMALLINT( 1 ) NOT NULL AFTER `gift` ,
ADD `roomid` INT( 15 ) NOT NULL AFTER `sort` ,
ADD `lastgift` SMALLINT( 2 ) NOT NULL AFTER `roomid` ;");

mysql_query("ALTER TABLE `groups_details` ADD `views` INT( 15 ) NOT NULL ;");

mysql_query("INSERT DELAYED INTO `cms_banners` (`id`, `text`, `banner`, `url`, `status`) VALUES
(1, 'HoloCMS, succesfully installed or upgraded!', 'http://images.habbohotel.nl/c_images/album1663/cheerthumb.gif', 'news.php?id=2', 1),
(2, 'Discussion time! Talk with other Holos!', 'http://images.habbohotel.nl/c_images/album824/weekagendathumb.gif', 'forum.php', 1);");
	
	mysql_query("ALTER TABLE `users` CHANGE `online` `online` MEDIUMINT( 30 ) NOT NULL DEFAULT '1'");
		
		echo "Building homes catalouge (4574 records)..";
		mysql_query("INSERT INTO `cms_homes_catalouge` VALUES ('2', 'Trax Sfx', '1', '0', 'trax_sfx', '1', '1', '3');
INSERT INTO `cms_homes_catalouge` VALUES ('3', 'Trax Disco', '1', '0', 'trax_disco', '1', '1', '3');
INSERT INTO `cms_homes_catalouge` VALUES ('4', 'Trax 8 bit', '1', '0', 'trax_8_bit', '1', '1', '3');
INSERT INTO `cms_homes_catalouge` VALUES ('5', 'Trax Electro', '1', '0', 'trax_electro', '1', '1', '3');
INSERT INTO `cms_homes_catalouge` VALUES ('6', 'Trax Reggae', '1', '0', 'trax_reggae', '1', '1', '3');
INSERT INTO `cms_homes_catalouge` VALUES ('7', 'Trax Ambient', '1', '0', 'trax_ambient', '1', '1', '3');
INSERT INTO `cms_homes_catalouge` VALUES ('8', 'Trax Bling', '1', '0', 'trax_bling', '1', '1', '3');
INSERT INTO `cms_homes_catalouge` VALUES ('9', 'Trax Heavy', '1', '0', 'trax_heavy', '1', '1', '3');
INSERT INTO `cms_homes_catalouge` VALUES ('10', 'Trax Latin', '1', '0', 'trax_latin', '1', '1', '3');
INSERT INTO `cms_homes_catalouge` VALUES ('11', 'Trax Rock', '1', '0', 'trax_rock', '1', '1', '3');
INSERT INTO `cms_homes_catalouge` VALUES ('12', 'Rain Background', '4', '0', 'bg_rain', '3', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('13', 'Notes', '3', '0', 'stickienote', '2', '5', '29');
INSERT INTO `cms_homes_catalouge` VALUES ('14', 'Serpentine Darkblue Background', '4', '0', 'bg_serpentine_darkblue', '3', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('15', 'Serpentine Darkred Background 2', '4', '0', 'bg_serpntine_darkred', '3', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('16', 'Serpentine Background', '4', '0', 'bg_serpentine_1', '3', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('17', 'Serpentine  Background 2', '4', '0', 'bg_serpentine_2', '3', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('18', 'Denim Background', '4', '0', 'bg_denim', '3', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('19', 'Lace Background', '4', '0', 'bg_lace', '3', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('20', 'Stitched Background', '4', '0', 'bg_stitched', '3', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('21', 'Wood Background', '4', '0', 'bg_wood', '3', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('22', 'Cork Background', '4', '0', 'bg_cork', '3', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('23', 'Stone Background', '4', '0', 'bg_stone', '3', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('24', 'Pattern Bricks Background', '4', '0', 'bg_pattern_bricks', '3', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('25', 'Ruled Paper Background', '4', '0', 'bg_ruled_paper', '3', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('26', 'Grass Background', '4', '0', 'bg_grass', '3', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('27', 'Hotel Background', '4', '0', 'bg_hotel', '3', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('28', 'Bubble Background', '4', '0', 'bg_bubble', '3', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('29', 'Pattern Bobbaskulls  Background', '4', '0', 'bg_pattern_bobbaskulls1', '3', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('30', 'Pattern Space Background', '4', '0', 'bg_pattern_space', '3', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('31', 'Image Submarine Background', '4', '0', 'bg_image_submarine', '3', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('32', 'Metal Background 2', '4', '0', 'bg_metal2', '3', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('33', 'Broken Glass Background', '4', '0', 'bg_broken_glass', '3', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('34', 'Pattern Clouds Background', '4', '0', 'bg_pattern_clouds', '3', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('35', 'Comic Background', '4', '0', 'bg_comic2', '3', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('36', 'Pattern Floral Background 1', '4', '0', 'bg_pattern_floral_01', '3', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('37', 'A', '1', '0', 'a', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('38', 'B', '1', '0', 'b_2', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('39', 'C', '1', '0', 'c', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('40', 'D', '1', '0', 'd', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('41', 'E', '1', '0', 'e', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('42', 'F', '1', '0', 'f', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('43', 'G', '1', '0', 'g', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('44', 'H', '1', '0', 'h', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('45', 'I', '1', '0', 'i', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('46', 'J', '1', '0', 'j', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('47', 'K', '1', '0', 'k', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('48', 'L', '1', '0', 'l', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('49', 'M', '1', '0', 'm', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('50', 'N', '1', '0', 'n', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('51', 'O', '1', '0', 'o', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('52', 'P', '1', '0', 'p', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('53', 'Q', '1', '0', 'q', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('54', 'R', '1', '0', 'r', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('55', 'S', '1', '0', 's', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('56', 'T', '1', '0', 't', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('57', 'U', '1', '0', 'u', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('58', 'V', '1', '0', 'v', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('59', 'W', '1', '0', 'w', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('60', 'X', '1', '0', 'x', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('61', 'Y', '1', '0', 'y', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('62', 'Z', '1', '0', 'z', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('63', 'Bling Star', '1', '0', 'bling_star', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('64', 'Bling a', '1', '0', 'bling_a', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('65', 'Bling b', '1', '0', 'bling_b', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('66', 'Bling c', '1', '0', 'bling_c', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('67', 'Bling d', '1', '0', 'bling_d', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('68', 'Bling e', '1', '0', 'bling_e', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('69', 'Bling f', '1', '0', 'bling_f', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('70', 'Bling g', '1', '0', 'bling_g', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('71', 'Bling h', '1', '0', 'bling_h', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('72', 'Bling i', '1', '0', 'bling_i', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('73', 'Bling j', '1', '0', 'bling_j', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('74', 'Bling k', '1', '0', 'bling_k', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('75', 'Bling l', '1', '0', 'bling_l', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('76', 'Bling m', '1', '0', 'bling_m', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('77', 'Bling n', '1', '0', 'bling_n', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('78', 'Bling o', '1', '0', 'bling_o', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('79', 'Bling p', '1', '0', 'bling_p', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('80', 'Bling q', '1', '0', 'bling_q', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('81', 'Bling r', '1', '0', 'bling_r', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('82', 'Bling s', '1', '0', 'bling_s', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('83', 'Bling t', '1', '0', 'bling_t', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('84', 'Bling u', '1', '0', 'bling_u', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('85', 'Bling v', '1', '0', 'bling_v', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('86', 'Bling w', '1', '0', 'bling_w', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('87', 'Bling x', '1', '0', 'bling_x', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('88', 'Bling y', '1', '0', 'bling_y', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('89', 'Bling z', '1', '0', 'bling_z', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('90', 'Bling Underscore', '1', '0', 'bling_underscore', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('91', 'Bling Comma', '1', '0', 'bling_comma', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('92', 'Bling Dot', '1', '0', 'bling_dot', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('93', 'Bling Exclamation', '1', '0', 'bling_exclamation', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('94', 'Bling Question', '1', '0', 'bling_question', '1', '1', '6');
INSERT INTO `cms_homes_catalouge` VALUES ('95', 'A with Circle', '1', '0', 'a_with_circle', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('96', 'A with Dots', '1', '0', 'a_with_dots', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('97', 'O with Dots', '1', '0', 'o_with_dots', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('98', 'Dot', '1', '0', 'dot', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('99', 'Acsent 1', '1', '0', 'acsent1', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('100', 'Acsent 2', '1', '0', 'acsent2', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('101', 'Underscore', '1', '0', 'underscore', '1', '1', '5');
INSERT INTO `cms_homes_catalouge` VALUES ('103', 'Chain Horizontal', '1', '0', 'chain_horizontal', '1', '1', '7');
INSERT INTO `cms_homes_catalouge` VALUES ('104', 'Chain Vertical', '1', '0', 'chain_vertical', '1', '1', '7');
INSERT INTO `cms_homes_catalouge` VALUES ('105', 'Ruler Horizontal', '1', '0', 'ruler_horizontal', '1', '1', '7');
INSERT INTO `cms_homes_catalouge` VALUES ('106', 'Ruler Vertical', '1', '0', 'ruler_vertical', '1', '1', '7');
INSERT INTO `cms_homes_catalouge` VALUES ('107', 'Vine', '1', '0', 'vine', '1', '1', '7');
INSERT INTO `cms_homes_catalouge` VALUES ('108', 'Vine 2', '1', '0', 'vine2', '1', '1', '7');
INSERT INTO `cms_homes_catalouge` VALUES ('109', 'Leafs 1', '1', '0', 'leafs1', '1', '1', '7');
INSERT INTO `cms_homes_catalouge` VALUES ('110', 'Leafs 2', '1', '0', 'leafs2', '1', '1', '7');
INSERT INTO `cms_homes_catalouge` VALUES ('111', 'Sticker Zipper V Tile', '1', '0', 'sticker_zipper_v_tile', '1', '1', '7');
INSERT INTO `cms_homes_catalouge` VALUES ('112', 'Sticker Zipper H Tile', '1', '0', 'sticker_zipper_h_tile', '1', '1', '7');
INSERT INTO `cms_homes_catalouge` VALUES ('113', 'Sticker Zipper H Normal Lock', '1', '0', 'sticker_zipper_h_normal_lock', '1', '1', '7');
INSERT INTO `cms_homes_catalouge` VALUES ('114', 'Sticker Zipper H Bobba Lock', '1', '0', 'sticker_zipper_h_bobba_lock', '1', '1', '7');
INSERT INTO `cms_homes_catalouge` VALUES ('115', 'Sticker Zipper H End', '1', '0', 'sticker_zipper_h_end', '1', '1', '7');
INSERT INTO `cms_homes_catalouge` VALUES ('116', 'Sticker Zipper V End', '1', '0', 'sticker_zipper_v_end', '1', '1', '7');
INSERT INTO `cms_homes_catalouge` VALUES ('117', 'Sticker Zipper V Bobba Lock', '1', '0', 'sticker_zipper_v_bobba_lock', '1', '1', '7');
INSERT INTO `cms_homes_catalouge` VALUES ('118', 'Sticker Zipper V Normal Lock', '1', '0', 'sticker_zipper_v_normal_lock', '1', '1', '7');
INSERT INTO `cms_homes_catalouge` VALUES ('119', 'Wormhand', '1', '0', 'wormhand', '5', '1', '8');
INSERT INTO `cms_homes_catalouge` VALUES ('120', 'Sticker Gentleman', '1', '0', 'sticker_gentleman', '2', '1', '8');
INSERT INTO `cms_homes_catalouge` VALUES ('121', 'Chewed Bubblegum', '1', '0', 'chewed_bubblegum', '1', '1', '8');
INSERT INTO `cms_homes_catalouge` VALUES ('122', 'Cactus', '1', '0', 'sticker_cactus_anim', '2', '1', '8');
INSERT INTO `cms_homes_catalouge` VALUES ('123', 'Sticker Spaceduck', '1', '0', 'sticker_spaceduck', '1', '1', '8');
INSERT INTO `cms_homes_catalouge` VALUES ('124', 'Sticker Moonpig', '1', '0', 'sticker_moonpig', '2', '1', '8');
INSERT INTO `cms_homes_catalouge` VALUES ('125', 'Swimming Fish', '1', '0', 'swimming_fish', '2', '1', '8');
INSERT INTO `cms_homes_catalouge` VALUES ('126', 'Sticker Boxer', '1', '0', 'sticker_boxer', '2', '1', '8');
INSERT INTO `cms_homes_catalouge` VALUES ('127', 'Wunder Frank', '1', '0', 'wunderfrank', '1', '1', '8');
INSERT INTO `cms_homes_catalouge` VALUES ('128', 'Sticker Submarine', '1', '0', 'sticker_submarine', '2', '1', '8');
INSERT INTO `cms_homes_catalouge` VALUES ('129', 'Sticker Clown Anim', '1', '0', 'sticker_clown_anim', '2', '1', '8');
INSERT INTO `cms_homes_catalouge` VALUES ('130', 'Bling Bling Stars', '1', '0', 'blingblingstars', '2', '1', '9');
INSERT INTO `cms_homes_catalouge` VALUES ('131', 'Bling Hearts', '1', '0', 'blinghearts', '2', '1', '9');
INSERT INTO `cms_homes_catalouge` VALUES ('132', 'Sticker Heartbeat', '1', '0', 'sticker_heartbeat', '2', '1', '9');
INSERT INTO `cms_homes_catalouge` VALUES ('133', 'Sticker Catinabox', '1', '0', 'sticker_catinabox', '2', '1', '9');
INSERT INTO `cms_homes_catalouge` VALUES ('134', 'Bear', '1', '0', 'bear', '2', '1', '9');
INSERT INTO `cms_homes_catalouge` VALUES ('135', 'Gothic Draculacape', '1', '0', 'gothic_draculacape ', '3', '1', '10');
INSERT INTO `cms_homes_catalouge` VALUES ('136', 'Evil Giant Bunny', '1', '0', 'evil_giant_bunny', '2', '1', '10');
INSERT INTO `cms_homes_catalouge` VALUES ('137', 'Zombie Pupu', '1', '0', 'zombiepupu', '2', '1', '10');
INSERT INTO `cms_homes_catalouge` VALUES ('138', 'Skeletor 1', '1', '0', 'skeletor_001', '2', '1', '10');
INSERT INTO `cms_homes_catalouge` VALUES ('139', 'Skull', '1', '0', 'skull', '2', '1', '10');
INSERT INTO `cms_homes_catalouge` VALUES ('140', 'Skull 2', '1', '0', 'skull2', '2', '1', '10');
INSERT INTO `cms_homes_catalouge` VALUES ('141', 'Scubacapsule Anim', '1', '0', 'scubacapsule_anim', '2', '1', '10');
INSERT INTO `cms_homes_catalouge` VALUES ('142', 'Bobbaskull', '1', '0', 'sticker_bobbaskull', '2', '1', '10');
INSERT INTO `cms_homes_catalouge` VALUES ('143', 'Sticker Flower', '1', '0', 'sticker_flower1', '3', '5', '11');
INSERT INTO `cms_homes_catalouge` VALUES ('144', 'Icecube Big', '1', '0', 'icecube_big', '3', '10', '11');
INSERT INTO `cms_homes_catalouge` VALUES ('145', 'Leafs 2', '1', '0', 'leafs2', '5', '7', '11');
INSERT INTO `cms_homes_catalouge` VALUES ('146', 'Vine 2', '1', '0', 'vine2', '3', '5', '11');
INSERT INTO `cms_homes_catalouge` VALUES ('147', 'Chain Horizontal', '1', '0', 'chain_horizontal', '3', '5', '11');
INSERT INTO `cms_homes_catalouge` VALUES ('148', 'Icecube Small', '1', '0', 'icecube_small', '3', '10', '11');
INSERT INTO `cms_homes_catalouge` VALUES ('149', 'Sticker Arrow Up', '1', '0', 'sticker_arrow_up', '2', '1', '12');
INSERT INTO `cms_homes_catalouge` VALUES ('150', 'Sticker Arrow Down', '1', '0', 'sticker_arrow_down', '2', '1', '12');
INSERT INTO `cms_homes_catalouge` VALUES ('151', 'Sticker Arrow Left', '1', '0', 'sticker_arrow_left', '2', '1', '12');
INSERT INTO `cms_homes_catalouge` VALUES ('152', 'Sticker Arrow Right', '1', '0', 'sticker_arrow_right', '2', '1', '12');
INSERT INTO `cms_homes_catalouge` VALUES ('153', 'Sticker Pointing Hand 1', '1', '0', 'sticker_pointing_hand_1', '2', '1', '12');
INSERT INTO `cms_homes_catalouge` VALUES ('154', 'Sticker Pointing Hand 2', '1', '0', 'sticker_pointing_hand_2', '2', '1', '12');
INSERT INTO `cms_homes_catalouge` VALUES ('155', 'Sticker Pointing Hand 3', '1', '0', 'sticker_pointing_hand_3', '2', '1', '12');
INSERT INTO `cms_homes_catalouge` VALUES ('156', 'Sticker Pointing Hand 4', '1', '0', 'sticker_pointing_hand_4', '2', '1', '12');
INSERT INTO `cms_homes_catalouge` VALUES ('157', 'Nail 2', '1', '0', 'nail2', '2', '1', '12');
INSERT INTO `cms_homes_catalouge` VALUES ('158', 'Nail 3', '1', '0', 'nail3', '2', '1', '12');
INSERT INTO `cms_homes_catalouge` VALUES ('159', 'Needle 1', '1', '0', 'needle_1', '1', '1', '12');
INSERT INTO `cms_homes_catalouge` VALUES ('160', 'Needle 2', '1', '0', 'needle_2', '1', '1', '12');
INSERT INTO `cms_homes_catalouge` VALUES ('161', 'Needle 3', '1', '0', 'needle_3', '1', '1', '12');
INSERT INTO `cms_homes_catalouge` VALUES ('162', 'Needle 4', '1', '0', 'needle_4', '1', '1', '12');
INSERT INTO `cms_homes_catalouge` VALUES ('163', 'Needle 5', '1', '0', 'needle_5', '1', '1', '12');
INSERT INTO `cms_homes_catalouge` VALUES ('164', 'Grass Meadow', '1', '0', 'grass', '2', '1', '13');
INSERT INTO `cms_homes_catalouge` VALUES ('165', 'Sticker Flower', '1', '0', 'sticker_flower1', '1', '1', '13');
INSERT INTO `cms_homes_catalouge` VALUES ('166', 'Sticker Flower Big Yellow', '1', '0', 'sticker_flower_big_yellow', '1', '1', '13');
INSERT INTO `cms_homes_catalouge` VALUES ('167', 'Sticker Flower Pink', '1', '0', 'sticker_flower_pink', '1', '1', '13');
INSERT INTO `cms_homes_catalouge` VALUES ('168', 'Bobba badge', '1', '0', 'sticker_bobbaskull', '1', '1', '14');
INSERT INTO `cms_homes_catalouge` VALUES ('169', 'I love Coffee', '1', '0', 'i_love_coffee', '1', '1', '14');
INSERT INTO `cms_homes_catalouge` VALUES ('170', 'Sticker Effect Bam', '1', '0', 'sticker_effect_bam', '1', '1', '14');
INSERT INTO `cms_homes_catalouge` VALUES ('171', 'Sticker Effect Burp', '1', '0', 'sticker_effect_burp', '1', '1', '14');
INSERT INTO `cms_homes_catalouge` VALUES ('172', 'Sticker Effect Woosh', '1', '0', 'sticker_effect_woosh', '1', '1', '14');
INSERT INTO `cms_homes_catalouge` VALUES ('173', 'Sticker Effect Zap', '1', '0', 'sticker_effect_zap', '1', '1', '14');
INSERT INTO `cms_homes_catalouge` VALUES ('174', 'Sticker Effect Whoosh 2', '1', '0', 'sticker_effect_whoosh2', '1', '1', '14');
INSERT INTO `cms_homes_catalouge` VALUES ('175', 'Icecube Small', '1', '0', 'icecube_small', '1', '1', '15');
INSERT INTO `cms_homes_catalouge` VALUES ('176', 'Snowball Machine', '1', '0', 'ss_snowballmachine', '1', '1', '15');
INSERT INTO `cms_homes_catalouge` VALUES ('177', 'Icecube Big', '1', '0', 'icecube_big', '1', '1', '15');
INSERT INTO `cms_homes_catalouge` VALUES ('178', 'Bootsitjalapaset Red', '1', '0', 'bootsitjalapaset_red', '2', '1', '15');
INSERT INTO `cms_homes_catalouge` VALUES ('179', 'Boots and Gloves', '1', '0', 'ss_bootsitjalapaset_blue', '2', '1', '15');
INSERT INTO `cms_homes_catalouge` VALUES ('180', 'Red SnowStorm Costume', '1', '0', 'ss_costume_red', '2', '1', '15');
INSERT INTO `cms_homes_catalouge` VALUES ('181', 'Snowstorm Blue Costume', '1', '0', 'ss_costume_blue', '2', '1', '15');
INSERT INTO `cms_homes_catalouge` VALUES ('182', 'Splash!', '1', '0', 'ss_hits_by_snowball', '1', '1', '15');
INSERT INTO `cms_homes_catalouge` VALUES ('183', 'SnowStorm Duck!', '1', '0', 'extra_ss_duck_left', '1', '1', '15');
INSERT INTO `cms_homes_catalouge` VALUES ('184', 'Snowtree', '1', '0', 'ss_snowtree', '2', '1', '15');
INSERT INTO `cms_homes_catalouge` VALUES ('185', 'SnowStorm Duck!', '1', '0', 'extra_ss_duck_right', '1', '1', '15');
INSERT INTO `cms_homes_catalouge` VALUES ('186', 'Snowman', '1', '0', 'ss_snowman', '2', '1', '15');
INSERT INTO `cms_homes_catalouge` VALUES ('187', 'Lumihiutale', '1', '0', 'ss_snowflake2', '1', '1', '15');
INSERT INTO `cms_homes_catalouge` VALUES ('188', 'Snow Queen', '1', '0', 'ss_snowqueen', '2', '1', '15');
INSERT INTO `cms_homes_catalouge` VALUES ('189', 'Battle 1', '1', '0', 'battle1', '1', '1', '16');
INSERT INTO `cms_homes_catalouge` VALUES ('190', 'Battle 3', '1', '0', 'battle3', '1', '1', '16');
INSERT INTO `cms_homes_catalouge` VALUES ('191', 'HC Hat', '1', '0', 'hc_hat', '5', '1', '18');
INSERT INTO `cms_homes_catalouge` VALUES ('192', 'Eye Left', '1', '0', 'eyeleft', '2', '1', '18');
INSERT INTO `cms_homes_catalouge` VALUES ('193', 'Eye Right', '1', '0', 'eyeright', '2', '1', '18');
INSERT INTO `cms_homes_catalouge` VALUES ('194', 'Angel Wings', '1', '0', 'angelwings_anim', '3', '1', '18');
INSERT INTO `cms_homes_catalouge` VALUES ('195', 'Sticker Gurubeard Gray', '1', '0', 'sticker_gurubeard_gray', '1', '1', '18');
INSERT INTO `cms_homes_catalouge` VALUES ('196', 'Sticker Gurubeard Brown', '1', '0', 'sticker_gurubeard_brown', '1', '1', '18');
INSERT INTO `cms_homes_catalouge` VALUES ('197', 'Supernerd', '1', '0', 'sticker_glasses_supernerd', '1', '1', '18');
INSERT INTO `cms_homes_catalouge` VALUES ('198', 'Goofy Glasses', '1', '0', 'sticker_glasses_elton', '1', '1', '18');
INSERT INTO `cms_homes_catalouge` VALUES ('199', 'Blue Eyes', '1', '0', 'sticker_eyes_blue', '1', '1', '18');
INSERT INTO `cms_homes_catalouge` VALUES ('200', 'Sticker Eye Anim', '1', '0', 'sticker_eye_anim', '2', '1', '18');
INSERT INTO `cms_homes_catalouge` VALUES ('201', 'Sticker Eye Evil Anim', '1', '0', 'sticker_eye_evil_anim', '2', '1', '18');
INSERT INTO `cms_homes_catalouge` VALUES ('202', 'Sticker Eraser', '1', '0', 'sticker_eraser', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('203', 'Star', '1', '0', 'star', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('204', 'Sticker Pencil', '1', '0', 'sticker_pencil', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('205', 'Sticker Dreamer', '1', '0', 'sticker_dreamer', '3', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('206', 'Sticker Pencil 2', '1', '0', 'sticker_pencil_2', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('207', 'Sticker Lonewolf', '1', '0', 'sticker_lonewolf', '3', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('208', 'Sticker Prankster', '1', '0', 'sticker_prankster', '3', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('209', 'Sticker Prankster', '1', '0', 'sticker_prankster', '3', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('210', 'Sticker Romantic', '1', '0', 'sticker_romantic', '3', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('211', 'Redlamp', '1', '0', 'redlamp', '2', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('212', 'Lightbulb', '1', '0', 'lightbulb', '2', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('213', 'Bullet 1', '1', '0', 'bullet1', '2', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('214', 'Spill 1', '1', '0', 'spill1', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('215', 'Spill 2', '1', '0', 'spill2', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('216', 'Spill 3', '1', '0', 'spill3', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('217', 'Sticker Coffee Stain', '1', '0', 'sticker_coffee_stain', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('218', 'Sticker Hole', '1', '0', 'sticker_hole', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('219', 'Sticker Flames', '1', '0', 'sticker_flames', '2', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('220', 'Paper Clip 1', '1', '0', 'paper_clip_1', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('221', 'Paper Clip 2', '1', '0', 'paper_clip_2', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('222', 'Paper Clip 3', '1', '0', 'paper_clip_3', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('223', 'Highlighter 1', '1', '0', 'highlighter_1', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('224', 'Highlighter Mark 5', '1', '0', 'highlighter_mark5', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('225', 'Highlighter Mark 6', '1', '0', 'highlighter_mark6', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('226', 'Highlighter Mark 4', '1', '0', 'highlighter_mark4b', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('227', 'Highlighter 2', '1', '0', 'highlighter_2', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('228', 'Highlighter Mark 1', '1', '0', 'highlighter_mark1', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('229', 'Highlighter Mark 2', '1', '0', 'highlighter_mark2', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('230', 'Highlighter Mark 3', '1', '0', 'highlighter_mark3', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('231', 'Plaster', '1', '0', 'plaster1', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('232', 'Plaster 2', '1', '0', 'plaster2', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('233', 'Sticker Croco', '1', '0', 'sticker_croco', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('234', 'Fish', '1', '0', 'fish', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('235', 'Parrot', '1', '0', 'parrot', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('236', 'Sticker Sleeping Habbo', '1', '0', 'sticker_sleeping_habbo', '2', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('237', 'Burger', '1', '0', 'burger', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('238', 'Juice', '1', '0', 'juice', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('239', 'Sticker Coffee Steam Blue', '1', '0', 'sticker_coffee_steam_blue', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('240', 'Sticker Coffee Steam Grey', '1', '0', 'sticker_coffee_steam_grey', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('241', 'Cassette 1', '1', '0', 'cassette1', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('242', 'Cassette 2', '1', '0', 'cassette2', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('243', 'Cassette 3', '1', '0', 'cassette3', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('244', 'Cassette 4', '1', '0', 'cassette4', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('245', 'Football', '1', '0', 'football', '1', '1', '20');
INSERT INTO `cms_homes_catalouge` VALUES ('246', 'Pattern Floral Background 2', '4', '0', 'bg_pattern_floral_02', '2', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('247', 'Pattern Floral Background 3', '4', '0', 'bg_pattern_floral_03', '2', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('248', 'Pattern Cars Background', '4', '0', 'bg_pattern_cars', '2', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('249', 'Pattern Carpants Background', '4', '0', 'bg_pattern_carpants', '2', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('250', 'Pattern Plasto Background', '4', '0', 'bg_pattern_plasto', '2', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('251', 'Pattern Tinyroom Background', '4', '0', 'bg_pattern_tinyroom', '2', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('252', 'Pattern Hearts Background', '4', '0', 'bg_pattern_hearts', '2', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('253', 'Pattern Abstract Background', '4', '0', 'bg_pattern_abstract1', '2', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('254', 'Bathroom Tile Background', '4', '0', 'bg_bathroom_tile', '2', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('255', 'Pattern Fish Background', '4', '0', 'bg_pattern_fish', '2', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('256', 'Pattern Deepred Background', '4', '0', 'bg_pattern_deepred', '2', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('257', 'Colour 02 Background', '4', '0', 'bg_colour_02', '2', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('258', 'Colour 03 Background', '4', '0', 'bg_colour_03', '2', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('259', 'Colour 04 Background', '4', '0', 'bg_colour_04', '2', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('260', 'Colour 05 Background', '4', '0', 'bg_colour_05', '2', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('261', 'Colour 06 Background', '4', '0', 'bg_colour_06', '2', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('262', 'Colour 07 Background', '4', '0', 'bg_colour_07', '2', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('263', 'Colour 08 Background', '4', '0', 'bg_colour_08', '2', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('264', 'Colour 09 Background', '4', '0', 'bg_colour_09', '2', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('265', 'Colour 10 Background', '4', '0', 'bg_colour_10', '2', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('266', 'Colour 11 Background', '4', '0', 'bg_colour_11', '2', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('267', 'Colour 12 Background', '4', '0', 'bg_colour_12', '2', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('268', 'Colour 13 Background', '4', '0', 'bg_colour_13', '2', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('269', 'Colour 14 Background', '4', '0', 'bg_colour_14', '2', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('270', 'Colour 15 Background', '4', '0', 'bg_colour_15', '2', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('271', 'Colour 17 Background', '4', '0', 'bg_colour_17', '2', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('272', 'Tonga Background', '4', '0', 'bg_tonga', '2', '1', '27');
INSERT INTO `cms_homes_catalouge` VALUES ('275', 'Alhambra Group', '4', '0', 'alhambragroup', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('276', 'Themepark Background 1', '4', '0', 'themepark_bg_01', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('277', 'Themepark Background 2', '4', '0', 'themepark_bg_02', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('278', 'Unofficial Fansites Background', '4', '0', 'bg_unofficial_fansites', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('279', 'Official Fansites Background', '4', '0', 'bg_official_fansites2', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('280', 'Welcoming Party', '4', '0', 'welcoming_party', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('281', 'Random Habbos', '4', '0', 'random_habbos', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('282', 'Habborella Sea Background', '4', '0', 'habborella_sea_bg', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('283', 'Penelope', '4', '0', 'penelope', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('284', 'Orca', '4', '0', 'orca', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('285', 'Sttrinians Group', '4', '0', 'sttriniansgroup', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('286', 'Sttrinians Blackboard', '4', '0', 'sttriniansblackboard', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('287', 'Habbox Background', '4', '0', 'habbox', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('288', 'Christmas Background 2 ', '4', '0', 'christmas2007bg_001', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('289', 'Kerrang', '4', '0', 'bg_kerrang2', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('291', 'Voice of Teens Background', '4', '0', 'bg_voiceofteens', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('292', 'Makeover Background', '4', '0', 'makeover', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('293', 'Snowstorm Background', '4', '0', 'snowstorm_bg', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('294', 'Habbo Group Background', '4', '0', 'habbogroup', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('295', 'Wobble Squabble Background', '4', '0', 'bg_wobble_squabble', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('296', 'VIP Background', '4', '0', 'bg_vip', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('297', 'Lido Background', '4', '0', 'bg_lidoo', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('298', 'Lido Flat Background', '4', '0', 'bg_lido_flat', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('299', 'Infobus Yellow Background', '4', '0', 'bg_infobus_yellow', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('300', 'Infobus White Background', '4', '0', 'bg_infobus_white', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('301', 'Infobus Blue Background', '4', '0', 'bg_infobus_blue', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('302', 'Battle Ball Background 2', '4', '0', 'bg_battle_ball2', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('303', 'Grunge Background', '4', '0', 'grungewall', '0', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('1001', 'OB Clubber', '1', '0', 'ob_clubber_146x146', '2', '1', '201');
INSERT INTO `cms_homes_catalouge` VALUES ('1002', 'OB Devil', '1', '0', 'ob_devil_146x146', '2', '1', '201');
INSERT INTO `cms_homes_catalouge` VALUES ('1003', 'OB Doctor', '1', '0', 'ob_doctor_146x146', '2', '1', '201');
INSERT INTO `cms_homes_catalouge` VALUES ('1004', 'OB Fairy', '1', '0', 'ob_fairy_146x146', '2', '1', '201');
INSERT INTO `cms_homes_catalouge` VALUES ('1005', 'OB Jetsetter', '1', '0', 'ob_jetsetter_146x146', '2', '1', '201');
INSERT INTO `cms_homes_catalouge` VALUES ('1006', 'OB Misuniverse', '1', '0', 'ob_misuniverse_146x146', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1007', 'OB Shopaholic', '1', '0', 'ob_shopaholic_146x146', '2', '1', '201');
INSERT INTO `cms_homes_catalouge` VALUES ('1008', 'OB Sport', '1', '0', 'ob_sporty_146x146', '2', '1', '201');
INSERT INTO `cms_homes_catalouge` VALUES ('1009', 'Edynamic Animator Sticker', '1', '0', 'edynamic_animator_sticker', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1010', 'Edynamic Sticker Avatar', '1', '0', 'edynamic_sticker_avatar', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1011', 'Sticker Themepark', '1', '0', '27224_sticker_themepark_001', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1012', '3 Year .ca', '1', '0', '3yearca', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1013', 'A', '1', '0', 'a', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1014', 'A with Circle', '1', '0', 'a_with_circle', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1015', 'A with Dots', '1', '0', 'a_with_dots', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1017', 'Acento', '1', '0', 'acento', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1018', 'Acentoagudo', '1', '0', 'acentoagudo', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1023', 'Acma Notepad Background', '1', '0', 'acma_notepad_bg_lge', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1026', 'Acsent 1', '1', '0', 'acsent1', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1027', 'Acsent 2', '1', '0', 'acsent2', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1028', 'Ad Cats', '1', '0', 'adcats', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('1029', 'Ad Dogs', '1', '0', 'addogs', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('1030', 'Ad Holiday', '1', '0', 'adholiday', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('1031', 'Adidabounce Sticker', '1', '0', 'adidabounce_sticker', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1032', 'Ad Party', '1', '0', 'adparty', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('1033', 'Ad Skating', '1', '0', 'adskating', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('1034', 'Agudo', '1', '0', 'agudo', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1035', 'Alhambra Sticker', '1', '0', 'alhambra_sticker', '2', '1', '205');
INSERT INTO `cms_homes_catalouge` VALUES ('1036', 'Alhambra Wallsticker', '1', '0', 'alhambra_wallsticker', '2', '1', '205');
INSERT INTO `cms_homes_catalouge` VALUES ('1037', 'Alhambra Logo', '1', '0', 'alhambralogo', '2', '1', '205');
INSERT INTO `cms_homes_catalouge` VALUES ('1039', 'Ametrin', '1', '0', 'ametrin', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1040', 'Angel', '1', '0', 'angel_146x146', '2', '1', '201');
INSERT INTO `cms_homes_catalouge` VALUES ('1041', 'Angel Winganim', '1', '0', 'angelwinganim', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1043', 'Anim Appart 732', '1', '0', 'anim_appart_732', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1044', 'Anim Boule Cristal', '1', '0', 'anim_boule_cristal', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1045', 'Anim Brasero', '1', '0', 'anim_brasero', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1046', 'Anim Cook', '1', '0', 'anim_cook', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1047', 'Anim Elvis', '1', '0', 'anim_elvis', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1048', 'Anim Enseigne', '1', '0', 'anim_enseigne', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1049', 'Anim Hockey', '1', '0', 'anim_hockey', '2', '1', '213');
INSERT INTO `cms_homes_catalouge` VALUES ('1051', 'Anim Oeil', '1', '0', 'anim_oeil', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1052', 'Anim Ventilo', '1', '0', 'anim_ventilo', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1053', 'Anim Viking Hole', '1', '0', 'anim_viking_hole', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1054', 'Anti Eco', '1', '0', 'anti_eco', '2', '1', '235');
INSERT INTO `cms_homes_catalouge` VALUES ('1055', 'Aprilfool 08', '1', '0', 'aprilfool08', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1056', 'Aprilfool 08 2', '1', '0', 'aprilfool08_2', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1057', 'Argentina', '1', '0', 'argentina', '2', '1', '232');
INSERT INTO `cms_homes_catalouge` VALUES ('1058', 'Asterisco 1', '1', '0', 'asterisco1', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1059', 'AU 3rd Bday 1', '1', '0', 'au_3rdbday_01', '2', '1', '206');
INSERT INTO `cms_homes_catalouge` VALUES ('1060', 'AU 3rd Bday 2', '1', '0', 'au_3rdbday_02', '2', '1', '206');
INSERT INTO `cms_homes_catalouge` VALUES ('1061', 'AU 3rd Bday 3', '1', '0', 'au_3rdbday_03', '2', '1', '206');
INSERT INTO `cms_homes_catalouge` VALUES ('1062', 'AU Greengold', '1', '0', 'au_greengold', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('1063', 'AU Lifesaver', '1', '0', 'au_lifesaver', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1064', 'AU Surfing', '1', '0', 'au_surfing', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('1065', 'B', '1', '0', 'b', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1067', 'Ballsma Honey', '1', '0', 'ballsmahoney', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1068', 'Baltasar', '1', '0', 'baltasar', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1069', 'Banks Bobby', '1', '0', 'banksbobby', '2', '1', '236');
INSERT INTO `cms_homes_catalouge` VALUES ('1070', 'Banks Can', '1', '0', 'bankscan', '2', '1', '236');
INSERT INTO `cms_homes_catalouge` VALUES ('1071', 'Banks Door', '1', '0', 'banksdoor', '2', '1', '236');
INSERT INTO `cms_homes_catalouge` VALUES ('1072', 'Banks Heater', '1', '0', 'banksheater', '2', '1', '236');
INSERT INTO `cms_homes_catalouge` VALUES ('1073', 'Barbequeset', '1', '0', 'barbequeset', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('1074', 'Barra', '1', '0', 'barra', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1075', 'Bartender Costume', '1', '0', 'bartender_costume', '2', '1', '208');
INSERT INTO `cms_homes_catalouge` VALUES ('1076', 'Batista', '1', '0', 'batista', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1077', 'Battle 1', '1', '0', 'battle1', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1078', 'Battle 3', '1', '0', 'battle3', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1079', 'Beachbunny Beachball', '1', '0', 'beachbunny_beachball', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('1080', 'Beachbunny Beachball Bouncing', '1', '0', 'beachbunny_beachball_bouncing', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('1081', 'Beachbunny Bmovie Poster', '1', '0', 'beachbunny_bmovieposter', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('1082', 'Beachbunny Bunny Suit', '1', '0', 'beachbunny_bunny_suit', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('1083', 'Beachbunny Peep', '1', '0', 'beachbunny_peep', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('1084', 'Beachbunny Roaster Bunny', '1', '0', 'beachbunny_roaster_bunny', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('1085', 'Beachgirl', '1', '0', 'beachgirl_146x146', '2', '1', '201');
INSERT INTO `cms_homes_catalouge` VALUES ('1086', 'Bear', '1', '0', 'bear', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('1087', 'Bellydancer', '1', '0', 'bellydancer', '2', '1', '205');
INSERT INTO `cms_homes_catalouge` VALUES ('1088', 'Beth Phoenix', '1', '0', 'bethphoenix', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1089', 'Beth Phoenix Skinny', '1', '0', 'bethphoenixskinny', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1090', 'Groupinfo Background', '1', '0', 'bg_groupinfo', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1092', 'Big Daddy V', '1', '0', 'bigdaddyv', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1093', 'Big Show', '1', '0', 'bigshow', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1094', 'Billy Graham', '1', '0', 'billygraham', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1095', 'Bird Suit', '1', '0', 'bird_suit', '2', '1', '210');
INSERT INTO `cms_homes_catalouge` VALUES ('1096', 'Bling a', '1', '0', 'bling_a', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1097', 'Bling b', '1', '0', 'bling_b', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1098', 'Bling c', '1', '0', 'bling_c', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1099', 'Bling Comma', '1', '0', 'bling_comma', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1100', 'Bling d', '1', '0', 'bling_d', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1101', 'Bling Dot', '1', '0', 'bling_dot', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1102', 'Bling e', '1', '0', 'bling_e', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1103', 'Bling Exclamation', '1', '0', 'bling_exclamation', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1104', 'Bling f', '1', '0', 'bling_f', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1105', 'Bling g', '1', '0', 'bling_g', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1106', 'Bling h', '1', '0', 'bling_h', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1107', 'Bling i', '1', '0', 'bling_i', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1108', 'Bling j', '1', '0', 'bling_j', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1109', 'Bling k', '1', '0', 'bling_k', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1110', 'Bling l', '1', '0', 'bling_l', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1111', 'Bling Line', '1', '0', 'bling_line', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1112', 'Bling m', '1', '0', 'bling_m', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1113', 'Bling n', '1', '0', 'bling_n', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1114', 'Bling o', '1', '0', 'bling_o', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1115', 'Bling p', '1', '0', 'bling_p', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1116', 'Bling q', '1', '0', 'bling_q', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1117', 'Bling Question', '1', '0', 'bling_question', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1118', 'Bling r', '1', '0', 'bling_r', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1119', 'Bling s', '1', '0', 'bling_s', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1120', 'Bling Star', '1', '0', 'bling_star', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1121', 'Bling t', '1', '0', 'bling_t', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1122', 'Bling u', '1', '0', 'bling_u', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1123', 'Bling Underscore', '1', '0', 'bling_underscore', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1124', 'Bling v', '1', '0', 'bling_v', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1125', 'Bling w', '1', '0', 'bling_w', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1126', 'Bling x', '1', '0', 'bling_x', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1127', 'Bling y', '1', '0', 'bling_y', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1128', 'Bling z', '1', '0', 'bling_z', '2', '1', '211');
INSERT INTO `cms_homes_catalouge` VALUES ('1129', 'Bling Bling Stars', '1', '0', 'blingblingstars', '2', '1', '237');
INSERT INTO `cms_homes_catalouge` VALUES ('1130', 'Bling Hearts', '1', '0', 'blinghearts', '2', '1', '237');
INSERT INTO `cms_homes_catalouge` VALUES ('1131', 'Bling Heartp', '1', '0', 'blingheartp', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1132', 'Blue Hockey Stick', '1', '0', 'bluehockeystick', '2', '1', '213');
INSERT INTO `cms_homes_catalouge` VALUES ('1133', 'Blue Starfish', '1', '0', 'bluestarfish', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1134', 'Bobba Curse', '1', '0', 'bobbacurse', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1136', 'Boborton', '1', '0', 'boborton', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1137', 'Bonbon Duck', '1', '0', 'bonbon_duck_146x146', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1138', 'Bonbon Mouse', '1', '0', 'bonbon_mouse_146x146', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1139', 'Bonbon Rat', '1', '0', 'bonbon_rat_146x146', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1140', 'Boogeyman', '1', '0', 'boogeyman', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1141', 'Bootsitjalapaset Red', '1', '0', 'bootsitjalapaset_red', '2', '1', '208');
INSERT INTO `cms_homes_catalouge` VALUES ('1142', 'Bottes Bleu', '1', '0', 'bottesbleu', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1143', 'Bouledeneige', '1', '0', 'bouledeneige', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1144', 'Bowser Sticker v1', '1', '0', 'bowser_sticker_v1', '2', '1', '212');
INSERT INTO `cms_homes_catalouge` VALUES ('1145', 'Bozzanova', '1', '0', 'bozzanova', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1146', 'BRA Football Guest 2', '1', '0', 'bra_football_guest2', '2', '1', '232');
INSERT INTO `cms_homes_catalouge` VALUES ('1147', 'Bratz Featured Clique', '1', '0', 'bratz_featuredclique', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1148', 'Brian Kendrick', '1', '0', 'briankendrick', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1149', 'British Legion', '1', '0', 'britishlegion', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1150', 'Britney', '1', '0', 'britney', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1151', 'Bronze Medallion', '1', '0', 'bronzemedallion', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1152', 'Bullet 1', '1', '0', 'bullet1', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1153', 'Bullybuster', '1', '0', 'bullybuster', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1155', 'Burger', '1', '0', 'burger', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1156', 'Businesswoman', '1', '0', 'businesswoman_146x146', '2', '1', '201');
INSERT INTO `cms_homes_catalouge` VALUES ('1157', 'Butterfly', '1', '0', 'butterfly_01', '2', '1', '210');
INSERT INTO `cms_homes_catalouge` VALUES ('1158', 'C', '1', '0', 'c', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1159', 'CA Hockeygoalie 2', '1', '0', 'ca_hockeygoalie2', '2', '1', '213');
INSERT INTO `cms_homes_catalouge` VALUES ('1160', 'Camel', '1', '0', 'camel', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1162', 'Candice Michelle', '1', '0', 'candicemichelle', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1163', 'Candycorn', '1', '0', 'candycorn', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1164', 'Carlito', '1', '0', 'carlito', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1165', 'Cassette 1', '1', '0', 'cassette1', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1166', 'Cassette 2', '1', '0', 'cassette2', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1167', 'Cassette 3', '1', '0', 'cassette3', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1168', 'Cassette 4', '1', '0', 'cassette4', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1170', 'Celeb Ticket Veronicas', '1', '0', 'celebticket_veronicas', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1171', 'Cena', '1', '0', 'cena', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1172', 'Chain Horizontal', '1', '0', 'chain_horizontal', '2', '1', '215');
INSERT INTO `cms_homes_catalouge` VALUES ('1173', 'Chain Vertical', '1', '0', 'chain_vertical', '2', '1', '215');
INSERT INTO `cms_homes_catalouge` VALUES ('1174', 'Cheerleader', '1', '0', 'cheerleader_146x146', '2', '1', '201');
INSERT INTO `cms_homes_catalouge` VALUES ('1175', 'Cheese Badge', '1', '0', 'cheese_badge', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('1177', 'Cheese Suit Sticker', '1', '0', 'cheese_suit_sticker', '2', '1', '208');
INSERT INTO `cms_homes_catalouge` VALUES ('1178', 'Cheshirecat', '1', '0', 'cheshirecat', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1179', 'Chewed Bubblegum', '1', '0', 'chewed_bubblegum', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1180', 'Chile', '1', '0', 'chile', '2', '1', '232');
INSERT INTO `cms_homes_catalouge` VALUES ('1181', 'Chocolates', '1', '0', 'chocolates', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1183', 'Chris Jericho', '1', '0', 'chrisjericho', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1184', 'Chuck Palumbo', '1', '0', 'chuckpalumbo', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1185', 'Clothes line', '1', '0', 'clothesline', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1186', 'Clothes line 5', '1', '0', 'clothesline5', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1187', 'CM Punk', '1', '0', 'cmpunk', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1188', 'CN Teleskull', '1', '0', 'cn_teleskull', '2', '1', '220');
INSERT INTO `cms_homes_catalouge` VALUES ('1190', 'CNY Dragon Body L', '1', '0', 'cny_dragon_body_l', '2', '1', '217');
INSERT INTO `cms_homes_catalouge` VALUES ('1191', 'CNY Dragon Body R', '1', '0', 'cny_dragon_body_r', '2', '1', '217');
INSERT INTO `cms_homes_catalouge` VALUES ('1192', 'CNY Dragon Head L', '1', '0', 'cny_dragon_head_l', '2', '1', '217');
INSERT INTO `cms_homes_catalouge` VALUES ('1193', 'CNY Dragon Head R', '1', '0', 'cny_dragon_head_r', '2', '1', '217');
INSERT INTO `cms_homes_catalouge` VALUES ('1195', 'CNY Kunfu Dude', '1', '0', 'cny_kunfu_dude', '2', '1', '217');
INSERT INTO `cms_homes_catalouge` VALUES ('1197', 'CNY Kunfu Girl', '1', '0', 'cny_kungfu_girl', '2', '1', '217');
INSERT INTO `cms_homes_catalouge` VALUES ('1198', 'Colombia', '1', '0', 'colombia', '2', '1', '232');
INSERT INTO `cms_homes_catalouge` VALUES ('1199', 'Comma', '1', '0', 'comma', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1203', 'Crase', '1', '0', 'crase', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1205', 'Cube Deglace', '1', '0', 'cubedeglace', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1206', 'Curt Hawkins', '1', '0', 'curthawkins', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1207', 'D', '1', '0', 'd', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1208', 'Dag of Habbo Trophy', '1', '0', 'dagofhabbo_trophy', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1209', 'DagorNot', '1', '0', 'dagornot', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1210', 'DE 4th Bday', '1', '0', 'de_bday_4', '2', '1', '206');
INSERT INTO `cms_homes_catalouge` VALUES ('1211', 'Deal Girasoles', '1', '0', 'dealgirasoles', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1214', 'Deal easter 6', '1', '0', 'deal_eas07_6', '2', '1', '210');
INSERT INTO `cms_homes_catalouge` VALUES ('1215', 'Deal easter 7', '1', '0', 'deal_eas07_7', '2', '1', '210');
INSERT INTO `cms_homes_catalouge` VALUES ('1216', 'Deal easter 8', '1', '0', 'deal_eas07_8', '2', '1', '210');
INSERT INTO `cms_homes_catalouge` VALUES ('1217', 'Deal goth border Horizontale', '1', '0', 'deal_goth_border_hor', '2', '1', '219');
INSERT INTO `cms_homes_catalouge` VALUES ('1218', 'deal goth border Verticale', '1', '0', 'deal_goth_border_ver', '2', '1', '219');
INSERT INTO `cms_homes_catalouge` VALUES ('1219', 'DH Smith', '1', '0', 'dhsmith', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1220', 'Dia de Internetsticker 2008', '1', '0', 'diadeinternetsticker2008_001', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1221', 'Diamond Reward', '1', '0', 'diamond_reward', '2', '1', '237');
INSERT INTO `cms_homes_catalouge` VALUES ('1222', 'Dim Sims', '1', '0', 'dimsims', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1223', 'DK Bobbacurse 2', '1', '0', 'dk_bobbacurse_2', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1224', 'Doelee', '1', '0', 'doelee', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1225', 'Donk', '1', '0', 'donk', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1226', 'Dot', '1', '0', 'dot', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1227', 'Dudesons', '1', '0', 'dudesons', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1228', 'Durian', '1', '0', 'durian', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1230', 'E', '1', '0', 'e', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1231', 'Easter Bird', '1', '0', 'easter_bird', '2', '1', '210');
INSERT INTO `cms_homes_catalouge` VALUES ('1236', 'Easter Broomstick_001', '1', '0', 'easter_broomstick_001', '2', '1', '210');
INSERT INTO `cms_homes_catalouge` VALUES ('1238', 'Easter Bunnymoped', '1', '0', 'easter_bunnymoped', '2', '1', '210');
INSERT INTO `cms_homes_catalouge` VALUES ('1240', 'Easter Carrot_rocket', '1', '0', 'easter_carrot_rocket', '2', '1', '210');
INSERT INTO `cms_homes_catalouge` VALUES ('1251', 'Easter Pointyhat', '1', '0', 'easter_pointyhat', '2', '1', '210');
INSERT INTO `cms_homes_catalouge` VALUES ('1253', 'Easter Rabbit_in_hole', '1', '0', 'easter_rabbit_in_hole', '2', '1', '210');
INSERT INTO `cms_homes_catalouge` VALUES ('1255', 'Easter Egg Costume', '1', '0', 'easteregg_costume', '2', '1', '210');
INSERT INTO `cms_homes_catalouge` VALUES ('1258', 'Edge', '1', '0', 'edge', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1259', 'Edito Fisherman Bait', '1', '0', 'edito_fisherman_bait', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1260', 'Edito Logo', '1', '0', 'edito_logo', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1261', 'Edito Logo 1', '1', '0', 'edito_logo_001', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1262', 'Edito Petit Talingots', '1', '0', 'edito_petit_talingots', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1263', 'Eggs', '1', '0', 'eggs', '2', '1', '220');
INSERT INTO `cms_homes_catalouge` VALUES ('1264', 'Elegant Bling', '1', '0', 'elegant_bling', '2', '1', '237');
INSERT INTO `cms_homes_catalouge` VALUES ('1265', 'Elijah Burke', '1', '0', 'elijahburke', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1266', 'E4 Years', '1', '0', 'e4_years', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1267', 'Educk Sticker', '1', '0', 'educk_sticker', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1268', 'Evil Easter Bunny', '1', '0', 'evil_easterbunny', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1269', 'Evil Giant Bunny', '1', '0', 'evil_giant_bunny', '2', '1', '220');
INSERT INTO `cms_homes_catalouge` VALUES ('1270', 'Exclamation', '1', '0', 'exclamation', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1271', 'Executive Cheappen', '1', '0', 'exe_sticker_cheappen', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1272', 'Executive Luxurypen', '1', '0', 'exe_sticker_luxurypen', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1273', 'Extra Sduck Left', '1', '0', 'extra_sduck_left', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1274', 'Extra Sduck Right', '1', '0', 'extra_sduck_right', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1275', 'Extra Ssnowball', '1', '0', 'extra_ssnowball', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1276', 'Extreme Dudesons', '1', '0', 'extreme_dudesons', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1277', 'Eye Left', '1', '0', 'eyeleft', '2', '1', '220');
INSERT INTO `cms_homes_catalouge` VALUES ('1278', 'Eye Right', '1', '0', 'eyeright', '2', '1', '220');
INSERT INTO `cms_homes_catalouge` VALUES ('1279', 'F', '1', '0', 'f', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1280', 'Fantastic Four Logo', '1', '0', 'fantasticfourlogo', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1281', 'Featuredgroup', '1', '0', 'featuredgroup', '2', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('1283', 'Featuredgroup Sticker', '1', '0', 'featuredgroup_sticker', '2', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('1284', 'Felix 1', '1', '0', 'felix01', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1285', 'Felix 2', '1', '0', 'felix02', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1286', 'Felix 3', '1', '0', 'felix03b', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1289', 'Festus', '1', '0', 'festus', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1290', 'Fikoirakisu', '1', '0', 'fikoirakisu', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1291', 'FI Golden Snake', '1', '0', 'fi_golden_snake', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1292', 'FI Koirakisu 2', '1', '0', 'fi_koirakisu2', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1293', 'FI Mino', '1', '0', 'fi_mino', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1294', 'FI Posti Box', '1', '0', 'fi_posti_box', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1295', 'FI Posti Girl', '1', '0', 'fi_posti_girl', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1296', 'FI Rasmu 2', '1', '0', 'fi_rasmu2_194x130', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1297', 'Fieldberries', '1', '0', 'fieldberries', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1298', 'Fiesta Chica Tampax', '1', '0', 'fiesta_chica_tampax', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1299', 'Fiesta Minifalda 1', '1', '0', 'fiesta_minifalda_1', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1300', 'Fiesta Minifalda 2', '1', '0', 'fiesta_minifalda_2', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1301', 'Fiesta Welcome', '1', '0', 'fiesta_welcome', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1302', 'Filmstrip Corner Botleft', '1', '0', 'filmstrip_corner_botleft', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1303', 'Filmstrip Corner Botright', '1', '0', 'filmstrip_corner_botright', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1304', 'Filmstrip Corner Topleft', '1', '0', 'filmstrip_corner_topleft', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1305', 'Filmstrip Corner Topright', '1', '0', 'filmstrip_corner_topright', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1306', 'Filmstrip Horiz', '1', '0', 'filmstrip_horiz', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1307', 'Filmstrip Vert', '1', '0', 'filmstrip_vert', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1308', 'Finger Push', '1', '0', 'finger_push', '2', '1', '222');
INSERT INTO `cms_homes_catalouge` VALUES ('1309', 'Fish', '1', '0', 'fish', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1310', 'Flameskull', '1', '0', 'flameskull', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1311', 'Flower 1', '1', '0', 'flower1png', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1312', 'Flower 2', '1', '0', 'flower2', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1313', 'Football', '1', '0', 'football', '2', '1', '232');
INSERT INTO `cms_homes_catalouge` VALUES ('1314', 'Free Hugs', '1', '0', 'free_hugs', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1317', 'Funaki', '1', '0', 'funaki', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1318', 'FWRK Blue', '1', '0', 'fwrk_blue', '2', '1', '223');
INSERT INTO `cms_homes_catalouge` VALUES ('1319', 'FWRK Pink', '1', '0', 'fwrk_pink', '2', '1', '223');
INSERT INTO `cms_homes_catalouge` VALUES ('1320', 'FWRK Yellow', '1', '0', 'fwrk_yellow', '2', '1', '223');
INSERT INTO `cms_homes_catalouge` VALUES ('1321', 'G', '1', '0', 'g', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1322', 'Galasticker', '1', '0', 'galasticker', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1323', 'Gamask', '1', '0', 'gamask', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1324', 'Gaspar', '1', '0', 'gaspar', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1325', 'Gaur', '1', '0', 'gaur', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1327', 'Gaursticker 3', '1', '0', 'gaursticker3', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1328', 'Genie Fire Head', '1', '0', 'genie_fire_head', '2', '1', '208');
INSERT INTO `cms_homes_catalouge` VALUES ('1330', 'Girlfriend Blue', '1', '0', 'gf_hotornot_blue', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1331', 'Girlfriend Pink', '1', '0', 'gf_hotornot_pink', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1332', 'Global World Sticker', '1', '0', 'globalw_sticker', '2', '1', '235');
INSERT INTO `cms_homes_catalouge` VALUES ('1333', 'Global World Sticker 2', '1', '0', 'globalw_sticker_001', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1334', 'Global World Tstorieoldweb', '1', '0', 'globalw_tstorieoldweb', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1335', 'Gold Medallion', '1', '0', 'goldmedallion', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1336', 'Gorilla Hand', '1', '0', 'gorilla_hand', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1337', 'Gorilla Hand 1', '1', '0', 'gorillahand1', '2', '1', '220');
INSERT INTO `cms_homes_catalouge` VALUES ('1338', 'Gorilla Hand 2', '1', '0', 'gorillahand2', '2', '1', '220');
INSERT INTO `cms_homes_catalouge` VALUES ('1344', 'Gothic Draculacape', '1', '0', 'gothic_draculacape', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1346', 'Gothic Scienceplatform', '1', '0', 'gothic_scienceplatform', '2', '1', '219');
INSERT INTO `cms_homes_catalouge` VALUES ('1348', 'GP Logo White', '1', '0', 'gplogo_white', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1351', 'Gras Deal', '1', '0', 'grasdeal', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1352', 'Grave Sticker', '1', '0', 'gravesticker', '2', '1', '220');
INSERT INTO `cms_homes_catalouge` VALUES ('1353', 'Grave Sticker 2', '1', '0', 'gravesticker2', '2', '1', '220');
INSERT INTO `cms_homes_catalouge` VALUES ('1354', 'Green Hockey Stick', '1', '0', 'greenhockeystick', '2', '1', '213');
INSERT INTO `cms_homes_catalouge` VALUES ('1355', 'Greenpeace', '1', '0', 'greenpeace', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1356', 'Greenpeace Sticker', '1', '0', 'greenpeace_sticker', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1357', 'Greenpeace White', '1', '0', 'greenpeacewhite', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1359', 'Grossebouledeneige', '1', '0', 'grossebouledeneige', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1360', 'Grunge Polaroid 1', '1', '0', 'grunge_polaroid_1', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1361', 'Grunge Polaroid 2', '1', '0', 'grunge_polaroid_2', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1362', 'Grunge Polaroid 3', '1', '0', 'grunge_polaroid_3', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1363', 'Grunge Polaroid 4', '1', '0', 'grunge_polaroid_4', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1364', 'Grunge Polaroid 5', '1', '0', 'grunge_polaroid_5', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1365', 'Guard 1', '1', '0', 'guard1', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1366', 'Guard 2', '1', '0', 'guard2', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1367', 'Guccimama', '1', '0', 'guccimama', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1368', 'H', '1', '0', 'h', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1370', 'Habbo Island', '1', '0', 'habbo_island', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('1371', 'Habbo Toolbar Sticker', '1', '0', 'habbo_toolbar_sticker', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1372', 'Habbo By Night-sticker', '1', '0', 'habbobynightsticker', '2', '1', '220');
INSERT INTO `cms_homes_catalouge` VALUES ('1373', 'Habbohome Of The Month', '1', '0', 'habbohome_of_the_month', '2', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('1375', 'Habborella Logo', '1', '0', 'habborella_logo', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1376', 'Habboween Background', '1', '0', 'habboween_bg', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1377', 'Hacksaw', '1', '0', 'hacksaw', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1378', 'Hat Clown 2', '1', '0', 'hat_clown2', '2', '1', '208');
INSERT INTO `cms_homes_catalouge` VALUES ('1379', 'HC Hat', '1', '0', 'hc_hat', '2', '1', '208');
INSERT INTO `cms_homes_catalouge` VALUES ('1380', 'Highlighter 1', '1', '0', 'highlighter_1', '2', '1', '238');
INSERT INTO `cms_homes_catalouge` VALUES ('1381', 'Highlighter 2', '1', '0', 'highlighter_2', '2', '1', '238');
INSERT INTO `cms_homes_catalouge` VALUES ('1382', 'Highlighter Mark 1', '1', '0', 'highlighter_mark1', '2', '1', '238');
INSERT INTO `cms_homes_catalouge` VALUES ('1383', 'Highlighter Mark 2', '1', '0', 'highlighter_mark2', '2', '1', '238');
INSERT INTO `cms_homes_catalouge` VALUES ('1384', 'Highlighter Mark 3', '1', '0', 'highlighter_mark3', '2', '1', '238');
INSERT INTO `cms_homes_catalouge` VALUES ('1385', 'Highlighter Mark 4', '1', '0', 'highlighter_mark4b', '2', '1', '238');
INSERT INTO `cms_homes_catalouge` VALUES ('1386', 'Highlighter Mark 5', '1', '0', 'highlighter_mark5', '2', '1', '238');
INSERT INTO `cms_homes_catalouge` VALUES ('1387', 'Highlighter Mark 6', '1', '0', 'highlighter_mark6', '2', '1', '238');
INSERT INTO `cms_homes_catalouge` VALUES ('1388', 'Hockey Habbo', '1', '0', 'hockey_habbo', '2', '1', '213');
INSERT INTO `cms_homes_catalouge` VALUES ('1389', 'Hockeyref', '1', '0', 'hockeyref', '2', '1', '213');
INSERT INTO `cms_homes_catalouge` VALUES ('1390', 'Holycarp', '1', '0', 'holycarp', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1391', 'Homer', '1', '0', 'homer', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1392', 'Horizontalink', '1', '0', 'horizontalink', '2', '1', '238');
INSERT INTO `cms_homes_catalouge` VALUES ('1393', 'Hornswoggle', '1', '0', 'hornswoggle', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1394', 'HP Guest', '1', '0', 'hp_guest', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1421', 'JP 5 Years', '1', '0', 'hwjp_5years', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1422', 'HW Lapanen Blue', '1', '0', 'hwlapanen_blue', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1423', 'HW Ssnowqueen', '1', '0', 'hwssnowqueen', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1424', 'HW Sticker Galafinal', '1', '0', 'hwsticker_galafinal', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1425', 'Valentine Cupido Anim', '1', '0', 'hwval_cupido_anim', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1426', 'Valentine Sticker Bobbadice', '1', '0', 'hwval_sticker_bobbadice', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1427', 'Xmas Box Suit Mint 2', '1', '0', 'hwxmabox_suit_mint2', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1428', 'Xmas Cat Animated', '1', '0', 'hwxmacat_animated', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1429', '3d Glasses', '1', '0', 'hw_3d_glasses', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1430', 'Actionstar', '1', '0', 'hw_actionstar', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1431', 'Amp Big', '1', '0', 'hw_amp_big', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1432', 'Amp Small', '1', '0', 'hw_amp_small', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1433', 'Bartender Costume', '1', '0', 'hw_bartender_costume', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1434', 'Bassplayer Boy', '1', '0', 'hw_bassplayer_boy', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1435', 'Bassplayer Girl', '1', '0', 'hw_bassplayer_girl', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1437', 'Skull Background', '1', '0', 'hw_bgpattern_skull', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1438', 'Bigcamera', '1', '0', 'hw_bigcamera', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1439', 'Bouncers', '1', '0', 'hw_bouncers', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1440', 'Camera L', '1', '0', 'hw_camera_l', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1441', 'Camera R', '1', '0', 'hw_camera_r', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1442', 'Camopant L', '1', '0', 'hw_camopantl', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1443', 'Camopant R', '1', '0', 'hw_camopantr', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1444', 'Camopant R1', '1', '0', 'hw_camopantr1', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1445', 'Carpet Corner Down', '1', '0', 'hw_carpet_corner_down', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1446', 'Carpet Corner Up', '1', '0', 'hw_carpet_corner_up', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1447', 'Carpet End Ldown', '1', '0', 'hw_carpet_end_ldown', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1448', 'Carpet End Lup', '1', '0', 'hw_carpet_end_lup', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1449', 'Carpet End Rdown', '1', '0', 'hw_carpet_end_rdown', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1450', 'Carpet End Rup', '1', '0', 'hw_carpet_end_rup', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1451', 'Carpet L', '1', '0', 'hw_carpet_l', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1452', 'Carpet R', '1', '0', 'hw_carpet_r', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1453', 'Drummer Boy', '1', '0', 'hw_drummer_boy', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1454', 'Drummer Girl', '1', '0', 'hw_drummer_girl', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1455', 'Edito Logo', '1', '0', 'hw_edito_logo', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1456', 'Guitarplayer Black', '1', '0', 'hw_guitarplayer_black', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1457', 'Guitarplayer White', '1', '0', 'hw_guitarplayer_white', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1458', 'Hairspray', '1', '0', 'hw_hairspray', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1459', 'Hippie', '1', '0', 'hw_hippie', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1460', 'Hitcher', '1', '0', 'hw_hitcher', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1461', 'Inmate', '1', '0', 'hw_inmate', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1462', 'Kenny Burger', '1', '0', 'hw_kenny_burger', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1463', 'Kenny Fight', '1', '0', 'hw_kenny_fight', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1464', 'Kenny Shock', '1', '0', 'hw_kenny_shock', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1465', 'Keyboards', '1', '0', 'hw_keyboards', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1468', 'Logoanim', '1', '0', 'hw_logoanim', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1469', 'Mega Afro', '1', '0', 'hw_mega_afro', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1470', 'Microphone', '1', '0', 'hw_microphone', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1471', 'Moh Logo', '1', '0', 'hw_moh_logo', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1472', 'Shades', '1', '0', 'hw_shades', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1473', 'Speedobunny', '1', '0', 'hw_speedobunny', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1474', 'Valentine Love Costume', '1', '0', 'hw_val_sticker_love-costume', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1475', 'Veronicas', '1', '0', 'hw_veronicas', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1476', 'Habbowood Klaffi 1', '1', '0', 'hwood07klaffi2', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1477', 'Habbowood Klaffi 2', '1', '0', 'hwood07_klaffi2', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1478', 'I', '1', '0', 'i', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1480', 'I love Bobba', '1', '0', 'i_love_bobba', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('1481', 'I love Coffee', '1', '0', 'i_love_coffee', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('1483', 'Icecube Big', '1', '0', 'icecube_big', '2', '1', '235');
INSERT INTO `cms_homes_catalouge` VALUES ('1484', 'Icecube Small', '1', '0', 'icecube_small', '2', '1', '235');
INSERT INTO `cms_homes_catalouge` VALUES ('1487', 'Impactsdeneige', '1', '0', 'impactsdeneige', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1488', 'Inked Antisquidf', '1', '0', 'inked_antisquidf', '2', '1', '239');
INSERT INTO `cms_homes_catalouge` VALUES ('1489', 'Inked Antisquidm', '1', '0', 'inked_antisquidm', '2', '1', '239');
INSERT INTO `cms_homes_catalouge` VALUES ('1490', 'Inked Antisquidrank', '1', '0', 'inked_antisquidrank', '2', '1', '239');
INSERT INTO `cms_homes_catalouge` VALUES ('1491', 'Inked Inkedblink', '1', '0', 'inked_inkedblink', '2', '1', '239');
INSERT INTO `cms_homes_catalouge` VALUES ('1492', 'Inked Lamp', '1', '0', 'inked_lamp', '2', '1', '239');
INSERT INTO `cms_homes_catalouge` VALUES ('1493', 'Inked Love', '1', '0', 'inked_love', '2', '1', '239');
INSERT INTO `cms_homes_catalouge` VALUES ('1494', 'Inked Poster', '1', '0', 'inked_poster', '2', '1', '239');
INSERT INTO `cms_homes_catalouge` VALUES ('1495', 'Inked Ship', '1', '0', 'inked_ship', '2', '1', '239');
INSERT INTO `cms_homes_catalouge` VALUES ('1496', 'Inked Spit', '1', '0', 'inked_spit', '2', '1', '239');
INSERT INTO `cms_homes_catalouge` VALUES ('1497', 'Inked Squidpants', '1', '0', 'inked_squidpants', '2', '1', '239');
INSERT INTO `cms_homes_catalouge` VALUES ('1498', 'Inked Suidpatrol', '1', '0', 'inked_suidpatrol', '2', '1', '239');
INSERT INTO `cms_homes_catalouge` VALUES ('1499', 'Island', '1', '0', 'island', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('1500', 'IT Habbohome', '1', '0', 'it_habbohome', '2', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('1501', 'IT Stickerpodio', '1', '0', 'it_stickerpodio', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1502', 'IT Toilet', '1', '0', 'it_toilet', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1503', 'J', '1', '0', 'j', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1504', 'James Curtis', '1', '0', 'jamescurtis', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1505', 'Jared', '1', '0', 'jared', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1506', 'Jarno Guest', '1', '0', 'jarno_guest', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1507', 'Jarppi Guest', '1', '0', 'jarppi_guest', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1508', 'Jarppi Guest 2', '1', '0', 'jarppi_guest2', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1509', 'JBL', '1', '0', 'jbl', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1510', 'Jeff Hardy', '1', '0', 'jeffhardy', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1511', 'Jenkki Monster Sticker', '1', '0', 'jenkki_monster_sticker', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1513', 'Jesse', '1', '0', 'jesse', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1514', 'Jigoku Shojo 1', '1', '0', 'jigoku_shojo_1', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1515', 'Jigoku Shojo 2', '1', '0', 'jigoku_shojo_2', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1516', 'Jimmyhart', '1', '0', 'jimmyhart', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1517', 'Jimmysnuka', '1', '0', 'jimmysnuka', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1518', 'Johnmorrison', '1', '0', 'johnmorrison', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1519', 'JP 5 Uears', '1', '0', 'jp_5years', '2', '1', '206');
INSERT INTO `cms_homes_catalouge` VALUES ('1520', 'JP Godzilla', '1', '0', 'jp_godzilla', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('1521', 'JP Sushi', '1', '0', 'jp_sushi', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('1522', 'Juice', '1', '0', 'juice', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1523', 'Jukka Guest', '1', '0', 'jukka_guest', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1524', 'Jukka Guest2', '1', '0', 'jukka_guest2', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1525', 'July0408 Boom 1', '1', '0', 'july0408_boom_1', '2', '1', '223');
INSERT INTO `cms_homes_catalouge` VALUES ('1526', 'July408 Auntsamantha', '1', '0', 'july408_auntsamantha', '2', '1', '223');
INSERT INTO `cms_homes_catalouge` VALUES ('1527', 'July408 Boom 2', '1', '0', 'july408_boom_2', '2', '1', '223');
INSERT INTO `cms_homes_catalouge` VALUES ('1528', 'July408 Boom 3', '1', '0', 'july408_boom_3', '2', '1', '223');
INSERT INTO `cms_homes_catalouge` VALUES ('1529', 'July408 Boom 4', '1', '0', 'july408_boom_4', '2', '1', '223');
INSERT INTO `cms_homes_catalouge` VALUES ('1530', 'July408 Border', '1', '0', 'july408_border', '2', '1', '223');
INSERT INTO `cms_homes_catalouge` VALUES ('1531', 'July408 July4th', '1', '0', 'july408_july4th', '2', '1', '223');
INSERT INTO `cms_homes_catalouge` VALUES ('1532', 'July408 Unclesam', '1', '0', 'july408_unclesam', '2', '1', '223');
INSERT INTO `cms_homes_catalouge` VALUES ('1533', 'K', '1', '0', 'k', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1534', 'Karigrandi Sticker', '1', '0', 'karigrandi_sticker', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1535', 'Kennydy Kstra', '1', '0', 'kennydykstra', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1536', 'Ketupat', '1', '0', 'ketupat', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1537', 'Kevinthorn', '1', '0', 'kevinthorn', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1538', 'KFP Sticker 1', '1', '0', 'kfp_sticker_01', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1539', 'KFP Sticker 2', '1', '0', 'kfp_sticker_02', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1540', 'KFP Sticker 3', '1', '0', 'kfp_sticker_03', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1541', 'KFP Sticker 4', '1', '0', 'kfp_sticker_04', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1542', 'KFP Sticker 5', '1', '0', 'kfp_sticker_05', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1543', 'KFP Sticker 6', '1', '0', 'kfp_sticker_06', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1544', 'KFP Sticker 7', '1', '0', 'kfp_sticker_07', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1545', 'KIP Partnerbtn', '1', '0', 'kip_partnerbtn', '2', '1', '225');
INSERT INTO `cms_homes_catalouge` VALUES ('1546', 'KIR Stamp 1', '1', '0', 'kir-stamp1', '2', '1', '225');
INSERT INTO `cms_homes_catalouge` VALUES ('1547', 'KIR Bam Sticker', '1', '0', 'kir_bam_sticker', '2', '1', '225');
INSERT INTO `cms_homes_catalouge` VALUES ('1548', 'KIR Elkah Sticker', '1', '0', 'kir_elkah_sticker', '2', '1', '225');
INSERT INTO `cms_homes_catalouge` VALUES ('1549', 'KIR Foz Sticker', '1', '0', 'kir_foz_sticker', '2', '1', '225');
INSERT INTO `cms_homes_catalouge` VALUES ('1550', 'KIR Fushi Sticker', '1', '0', 'kir_fushi_sticker', '2', '1', '225');
INSERT INTO `cms_homes_catalouge` VALUES ('1551', 'KIR Stamp 1', '1', '0', 'kir_stamp1', '2', '1', '225');
INSERT INTO `cms_homes_catalouge` VALUES ('1552', 'KIR Stamp 2', '1', '0', 'kir_stamp2', '2', '1', '225');
INSERT INTO `cms_homes_catalouge` VALUES ('1553', 'KIR Stamp 3', '1', '0', 'kir_stamp3', '2', '1', '225');
INSERT INTO `cms_homes_catalouge` VALUES ('1555', 'Kir Stamp', '1', '0', 'kir_stamp4', '2', '1', '225');
INSERT INTO `cms_homes_catalouge` VALUES ('1557', 'KIR Stamp 5', '1', '0', 'kir_stamp5', '2', '1', '225');
INSERT INTO `cms_homes_catalouge` VALUES ('1558', 'KIR Stamp 6', '1', '0', 'kir_stamp6', '2', '1', '225');
INSERT INTO `cms_homes_catalouge` VALUES ('1559', 'KIR Stamp 7', '1', '0', 'kir_stamp6_002', '2', '1', '225');
INSERT INTO `cms_homes_catalouge` VALUES ('1561', 'KIR Sticker', '1', '0', 'kir_sticker', '2', '1', '225');
INSERT INTO `cms_homes_catalouge` VALUES ('1562', 'KIR Winner 1', '1', '0', 'kir_winner_01', '2', '1', '225');
INSERT INTO `cms_homes_catalouge` VALUES ('1563', 'KIR Winner 2', '1', '0', 'kir_winner_02', '2', '1', '225');
INSERT INTO `cms_homes_catalouge` VALUES ('1564', 'KIR Winner 3', '1', '0', 'kir_winner_03', '2', '1', '225');
INSERT INTO `cms_homes_catalouge` VALUES ('1565', 'KIR Winner 4', '1', '0', 'kir_winner_04', '2', '1', '225');
INSERT INTO `cms_homes_catalouge` VALUES ('1566', 'KIR Winner 5', '1', '0', 'kir_winner_05', '2', '1', '225');
INSERT INTO `cms_homes_catalouge` VALUES ('1567', 'KIR Winner 6', '1', '0', 'kir_winner_06', '2', '1', '225');
INSERT INTO `cms_homes_catalouge` VALUES ('1568', 'KIR Winner 7', '1', '0', 'kir_winner_07', '2', '1', '225');
INSERT INTO `cms_homes_catalouge` VALUES ('1569', 'KIR Winner 8', '1', '0', 'kir_winner_08', '2', '1', '225');
INSERT INTO `cms_homes_catalouge` VALUES ('1570', 'KIR Winner 9', '1', '0', 'kir_winner_09', '2', '1', '225');
INSERT INTO `cms_homes_catalouge` VALUES ('1571', 'KIR Winner 10', '1', '0', 'kir_winner_10', '2', '1', '225');
INSERT INTO `cms_homes_catalouge` VALUES ('1572', 'KIR Winner 11', '1', '0', 'kir_winner_11', '2', '1', '225');
INSERT INTO `cms_homes_catalouge` VALUES ('1573', 'KIR Yobbo Sticker', '1', '0', 'kir_yobbo_sticker', '2', '1', '225');
INSERT INTO `cms_homes_catalouge` VALUES ('1574', 'Kitsune Wonders', '1', '0', 'kitsune_wonders', '2', '1', '224');
INSERT INTO `cms_homes_catalouge` VALUES ('1575', 'Kitsune Yelling', '1', '0', 'kitsune_yelling', '2', '1', '224');
INSERT INTO `cms_homes_catalouge` VALUES ('1576', 'Kitune Fighting', '1', '0', 'kitune_fighting', '2', '1', '224');
INSERT INTO `cms_homes_catalouge` VALUES ('1580', 'Krans', '1', '0', 'krans', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1581', 'L', '1', '0', 'l', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1582', 'Lamp 1', '1', '0', 'lamp_1', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('1583', 'Lamp 2', '1', '0', 'lamp_2', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1584', 'Lamp 3', '1', '0', 'lamp_3', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1585', 'Lamp 4', '1', '0', 'lamp_4', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1586', 'Lamp Group', '1', '0', 'lamp_group', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1588', 'Lapanen Blue', '1', '0', 'lapanen_blue', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1593', 'Lapanen Purple', '1', '0', 'lapanen_purple', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1594', 'Lapanen Red', '1', '0', 'lapanen_red', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1596', 'Lapanen Yellow', '1', '0', 'lapanen_yellow', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1597', 'Lashey', '1', '0', 'lashey', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1598', 'Leafs 1', '1', '0', 'leafs1', '2', '1', '215');
INSERT INTO `cms_homes_catalouge` VALUES ('1599', 'Leafs 2', '1', '0', 'leafs2', '2', '1', '215');
INSERT INTO `cms_homes_catalouge` VALUES ('1600', 'Leapday', '1', '0', 'leapday', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1602', 'Letrah', '1', '0', 'letrah', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1603', 'Letrai', '1', '0', 'letrai', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1604', 'Lightbulb', '1', '0', 'lightbulb', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1605', 'Limo Back', '1', '0', 'limo_back', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1606', 'Limo Doorpiece', '1', '0', 'limo_doorpiece', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1607', 'Limo Front', '1', '0', 'limo_front', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1608', 'Limo Windowpiece', '1', '0', 'limo_windowpiece', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1609', 'Linha', '1', '0', 'linha', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1610', 'Linnsticker', '1', '0', 'linnsticker', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1611', 'Little Dog Little Dog', '1', '0', 'littledoglittledog', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1612', 'Little Dog Mechahead', '1', '0', 'littledogmechahead', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1613', 'Little Dog Walking Mecha', '1', '0', 'littledogwalkingmecha', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1614', 'Loderse', '1', '0', 'loderse', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1616', 'Lovebed', '1', '0', 'lovebed', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('1617', 'M', '1', '0', 'm', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1620', 'Malecapitain Costume', '1', '0', 'malecapitain_costume', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1622', 'Mansikka Tarra', '1', '0', 'mansikka_tarra', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1623', 'Maria', '1', '0', 'maria', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1624', 'Mark Henry', '1', '0', 'markhenry', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1625', 'Matt Hardy', '1', '0', 'matthardy', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1626', 'Matt Striker', '1', '0', 'mattstriker', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1627', 'May Weather', '1', '0', 'mayweather', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1628', 'Melchor', '1', '0', 'melchor', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1629', 'Melina', '1', '0', 'melina', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1631', 'Mexico', '1', '0', 'mexico', '2', '1', '232');
INSERT INTO `cms_homes_catalouge` VALUES ('1633', 'Mid Sommar 1', '1', '0', 'midsommar_1', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1634', 'Mike Knoxx', '1', '0', 'mikeknoxx', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1635', 'Miss J', '1', '0', 'missj', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1636', 'Money Low', '1', '0', 'money_o', '2', '1', '215');
INSERT INTO `cms_homes_catalouge` VALUES ('1637', 'Money Stash', '1', '0', 'money_stash', '2', '1', '215');
INSERT INTO `cms_homes_catalouge` VALUES ('1638', 'Money High', '1', '0', 'money_v', '2', '1', '215');
INSERT INTO `cms_homes_catalouge` VALUES ('1639', 'Mooncake Dark', '1', '0', 'mooncake_dark', '2', '1', '217');
INSERT INTO `cms_homes_catalouge` VALUES ('1640', 'Mooncake Light', '1', '0', 'mooncake_light', '2', '1', '217');
INSERT INTO `cms_homes_catalouge` VALUES ('1641', 'Mr Kennedy', '1', '0', 'mrkennedy', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1642', 'Mummimor', '1', '0', 'mummimor', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1643', 'N', '1', '0', 'n', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1644', 'Nail 2', '1', '0', 'nail2', '2', '1', '222');
INSERT INTO `cms_homes_catalouge` VALUES ('1645', 'Nail 3', '1', '0', 'nail3', '2', '1', '222');
INSERT INTO `cms_homes_catalouge` VALUES ('1646', 'Needle 1', '1', '0', 'needle_1', '2', '1', '222');
INSERT INTO `cms_homes_catalouge` VALUES ('1647', 'Needle 2', '1', '0', 'needle_2', '2', '1', '222');
INSERT INTO `cms_homes_catalouge` VALUES ('1648', 'Needle 3', '1', '0', 'needle_3', '2', '1', '222');
INSERT INTO `cms_homes_catalouge` VALUES ('1649', 'Needle 4', '1', '0', 'needle_4', '2', '1', '222');
INSERT INTO `cms_homes_catalouge` VALUES ('1650', 'Needle 5', '1', '0', 'needle_5', '2', '1', '222');
INSERT INTO `cms_homes_catalouge` VALUES ('1652', 'Newyear 2007', '1', '0', 'newyear_2007_anim', '2', '1', '223');
INSERT INTO `cms_homes_catalouge` VALUES ('1655', 'Newyear Sparkler', '1', '0', 'newyear_sparkler', '2', '1', '223');
INSERT INTO `cms_homes_catalouge` VALUES ('1656', 'Nigiri Sushi', '1', '0', 'nigirisushi', '2', '1', '224');
INSERT INTO `cms_homes_catalouge` VALUES ('1657', 'NL Coinguy Animated', '1', '0', 'nl_coinguy_animated', '2', '1', '230');
INSERT INTO `cms_homes_catalouge` VALUES ('1658', 'NL Cupido', '1', '0', 'nl_cupido', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('1659', 'NL Downtown Costume', '1', '0', 'nl_downtown_costume', '2', '1', '208');
INSERT INTO `cms_homes_catalouge` VALUES ('1660', 'NL Firecracker', '1', '0', 'nl_firecracker', '2', '1', '217');
INSERT INTO `cms_homes_catalouge` VALUES ('1661', 'NL Football Guest', '1', '0', 'nl_football_guest', '2', '1', '232');
INSERT INTO `cms_homes_catalouge` VALUES ('1662', 'NL Football Guest 2', '1', '0', 'nl_football_guest2', '2', '1', '232');
INSERT INTO `cms_homes_catalouge` VALUES ('1663', 'NL Limo', '1', '0', 'nl_limo', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1664', 'NL Wanted Costume', '1', '0', 'nl_wanted_costume', '2', '1', '208');
INSERT INTO `cms_homes_catalouge` VALUES ('1665', 'No Space Sticker', '1', '0', 'no_space_sticker', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('1666', 'No Way Out Slanted', '1', '0', 'nowayout_slanted', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1667', 'N-Jury', '1', '0', 'njury', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1668', 'N-Logo', '1', '0', 'nlogo', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1669', 'N-Stage', '1', '0', 'nstage', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1670', 'Nunzio', '1', '0', 'nunzio', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1671', 'NZ Heart', '1', '0', 'nz_heart', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('1672', 'NZ Tiki', '1', '0', 'nz_tiki', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('1673', 'O', '1', '0', 'o', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1674', 'O with Dots', '1', '0', 'o_with_dots', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1675', 'Ogopogo 1', '1', '0', 'ogopogo1', '2', '1', '217');
INSERT INTO `cms_homes_catalouge` VALUES ('1676', 'Oldfence Left', '1', '0', 'oldfence_left', '2', '1', '230');
INSERT INTO `cms_homes_catalouge` VALUES ('1677', 'Oldfence Right', '1', '0', 'oldfence_right', '2', '1', '230');
INSERT INTO `cms_homes_catalouge` VALUES ('1678', 'Orca Ideal Home', '1', '0', 'orca_ideal_home', '2', '1', '230');
INSERT INTO `cms_homes_catalouge` VALUES ('1679', 'P', '1', '0', 'p', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1680', 'Paper Clip 1', '1', '0', 'paper_clip_1', '2', '1', '222');
INSERT INTO `cms_homes_catalouge` VALUES ('1681', 'Paper Clip 2', '1', '0', 'paper_clip_2', '2', '1', '222');
INSERT INTO `cms_homes_catalouge` VALUES ('1682', 'Paper Clip 3', '1', '0', 'paper_clip_3', '2', '1', '222');
INSERT INTO `cms_homes_catalouge` VALUES ('1683', 'Parrot', '1', '0', 'parrot', '2', '1', '230');
INSERT INTO `cms_homes_catalouge` VALUES ('1684', 'Patsday Claddagh', '1', '0', 'patsday_claddagh', '2', '1', '241');
INSERT INTO `cms_homes_catalouge` VALUES ('1685', 'Patsday Kissme', '1', '0', 'patsday_kissme', '2', '1', '241');
INSERT INTO `cms_homes_catalouge` VALUES ('1687', 'Patsday Potogold', '1', '0', 'patsday_potogold', '2', '1', '241');
INSERT INTO `cms_homes_catalouge` VALUES ('1688', 'Patsday Shamborderh', '1', '0', 'patsday_shamborderh', '2', '1', '241');
INSERT INTO `cms_homes_catalouge` VALUES ('1689', 'Patsday Shamborderv', '1', '0', 'patsday_shamborderv', '2', '1', '241');
INSERT INTO `cms_homes_catalouge` VALUES ('1690', 'Patsday Shamrock', '1', '0', 'patsday_shamrock', '2', '1', '241');
INSERT INTO `cms_homes_catalouge` VALUES ('1691', 'Paul London', '1', '0', 'paullondon', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1692', 'Paul Orndorff', '1', '0', 'paulorndorff', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1693', 'Petite Boule', '1', '0', 'petiteboule', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1694', 'Pinrock Inrio', '1', '0', 'pinrockinrio', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1695', 'Pirate', '1', '0', 'pirate', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1696', 'Pirate Captain', '1', '0', 'piratecaptain', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1697', 'Pirate Cutlass', '1', '0', 'piratecutlass', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1698', 'Pirate Dude 1', '1', '0', 'piratedude01', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1699', 'Pirate Dude 2', '1', '0', 'piratedude02', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1700', 'Pirate Flag', '1', '0', 'pirateflag', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1701', 'Pirate Scroll', '1', '0', 'piratescroll', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1702', 'Pirate Treasure 1', '1', '0', 'piratetreasure01', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1703', 'Pirate Treasure 2', '1', '0', 'piratetreasure02', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1704', 'Plaster', '1', '0', 'plaster', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1705', 'Plaster 2', '1', '0', 'plaster2', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1706', 'Ponto', '1', '0', 'ponto', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1707', 'Poppy', '1', '0', 'poppy', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1708', 'Poptart', '1', '0', 'poptartcv1b2', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1710', 'Poptart 2', '1', '0', 'poptartcv2b2', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1711', 'Poptart 3', '1', '0', 'poptartcv2ba', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1712', 'Poptarts 1', '1', '0', 'poptartsb1b', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1713', 'Poptarts 2', '1', '0', 'poptartsb2b', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1714', 'Pro Eco', '1', '0', 'pro_eco', '2', '1', '235');
INSERT INTO `cms_homes_catalouge` VALUES ('1717', 'Prom of the Dead Sticker Brains', '1', '0', 'promofthedead_sticker_brains', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1718', 'Prom of the Dead Sticker Dress', '1', '0', 'promofthedead_sticker_dress', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1719', 'Prom of the Dead Sticker Duck', '1', '0', 'promofthedead_sticker_duck', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1720', 'Prom of the Dead Sticker Poster', '1', '0', 'promofthedead_sticker_poster', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1721', 'Prom of the Dead Sticker Suit', '1', '0', 'promofthedead_sticker_suit', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1722', 'Prom of the Dead Sticker Zombie', '1', '0', 'promofthedead_sticker_zombie', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('1723', 'Q', '1', '0', 'q', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1724', 'Queensibidi', '1', '0', 'queensibidi', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1725', 'Question', '1', '0', 'question', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1726', 'R', '1', '0', 'r', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1727', 'Radar', '1', '0', 'radar', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1728', 'Randy Orton', '1', '0', 'randyorton', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1729', 'Rasta', '1', '0', 'rasta', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1730', 'Ratv 2', '1', '0', 'ratv2', '2', '1', '217');
INSERT INTO `cms_homes_catalouge` VALUES ('1731', 'Red Hockeystick', '1', '0', 'redhockeystick', '2', '1', '213');
INSERT INTO `cms_homes_catalouge` VALUES ('1732', 'Redlamp', '1', '0', 'redlamp', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1733', 'Red Starfish', '1', '0', 'redstarfish', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1734', 'Referee 1 Guest', '1', '0', 'referee_01_guest', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1735', 'Referee 2 Guest', '1', '0', 'referee_02_guest', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1736', 'Referee 3 Guest', '1', '0', 'referee_03_guest', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1737', 'Reymysterio', '1', '0', 'reymysterio', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1738', 'Ricflair', '1', '0', 'ricflair', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1739', 'Roddypiper', '1', '0', 'roddypiper', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1740', 'Ronsimmons', '1', '0', 'ronsimmons', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1741', 'Room of the Week', '1', '0', 'room_of_the_week', '2', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('1742', 'Rt Stiker', '1', '0', 'rtsstciker', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1744', 'Rt Sticker v1', '1', '0', 'rtsticker_v1', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1745', 'Ruler Horizontal', '1', '0', 'ruler_horizontal', '2', '1', '215');
INSERT INTO `cms_homes_catalouge` VALUES ('1746', 'Ruler Vertical', '1', '0', 'ruler_vertical', '2', '1', '215');
INSERT INTO `cms_homes_catalouge` VALUES ('1747', 'S', '1', '0', 's', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1748', 'Sack Costume', '1', '0', 'sackcostume', '2', '1', '208');
INSERT INTO `cms_homes_catalouge` VALUES ('1749', 'Safer Internet 2008', '1', '0', 'saferinternet2008', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1750', 'Samsung 1', '1', '0', 'samsung1', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1751', 'Samsung 2', '1', '0', 'samsung2', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1752', 'Samsung 3', '1', '0', 'samsung3', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1753', 'Samsung 4', '1', '0', 'samsung4', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1754', 'Samsung 5', '1', '0', 'samsung5', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1755', 'Santa 3000 Character', '1', '0', 'santa3000_character', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1756', 'Santinoma Rella', '1', '0', 'santinomarella', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1758', 'Sasquatch', '1', '0', 'sasquatch7', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1759', 'Sasquatch Hands', '1', '0', 'sasquatch_hands', '2', '1', '208');
INSERT INTO `cms_homes_catalouge` VALUES ('1760', 'Satay', '1', '0', 'satay', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1761', 'Scubacapsule Anim', '1', '0', 'scubacapsule_anim', '2', '1', '208');
INSERT INTO `cms_homes_catalouge` VALUES ('1762', 'SE Sticker Competition', '1', '0', 'se_sticker_competition', '2', '1', '208');
INSERT INTO `cms_homes_catalouge` VALUES ('1763', 'Seagull 1', '1', '0', 'seagull_01', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('1764', 'Seagull 2', '1', '0', 'seagull_02', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('1767', 'Sea Shell', '1', '0', 'seashell', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('1768', 'Sergeant Slaughter', '1', '0', 'sergeantslaughter', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1769', 'MTV Sticker 1', '1', '0', 'sg_mtv_sticker_01', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1770', 'MTV Sticker 2', '1', '0', 'sg_mtv_sticker_02', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1771', 'MTV Sticker 3', '1', '0', 'sg_mtv_sticker_03', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1772', 'Shell', '1', '0', 'shell', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('1773', 'Shelton Benjamin', '1', '0', 'sheltonbenjamin', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1774', 'Shrubbery', '1', '0', 'shrubbery', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1775', 'Silver Medallion', '1', '0', 'silvermedallion', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1776', 'Skeletor 1', '1', '0', 'skeletor_001', '2', '1', '220');
INSERT INTO `cms_homes_catalouge` VALUES ('1777', 'Skull', '1', '0', 'skull', '2', '1', '220');
INSERT INTO `cms_homes_catalouge` VALUES ('1778', 'Skull 2', '1', '0', 'skull2', '2', '1', '220');
INSERT INTO `cms_homes_catalouge` VALUES ('1779', 'Slash', '1', '0', 'slash', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1780', 'Smichaels', '1', '0', 'smichaels', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1781', 'Snitsky', '1', '0', 'snitsky', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1782', 'Snowball', '1', '0', 'snowball', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1783', 'Snowball Bumpy', '1', '0', 'snowball_bumpy', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1784', 'Snowball Smooth', '1', '0', 'snowball_smooth', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1785', 'Soccer Dude 1', '1', '0', 'soccer_dude_1', '2', '1', '232');
INSERT INTO `cms_homes_catalouge` VALUES ('1786', 'Soccer Dude 2', '1', '0', 'soccer_dude_2', '2', '1', '232');
INSERT INTO `cms_homes_catalouge` VALUES ('1787', 'Soccer Dude 3', '1', '0', 'soccer_dude_3', '2', '1', '232');
INSERT INTO `cms_homes_catalouge` VALUES ('1788', 'Soccer Dude 4', '1', '0', 'soccer_dude_4', '2', '1', '232');
INSERT INTO `cms_homes_catalouge` VALUES ('1789', 'Soccer Dude 5', '1', '0', 'soccer_dude_5', '2', '1', '232');
INSERT INTO `cms_homes_catalouge` VALUES ('1790', 'Soccer Shirt 1', '1', '0', 'soccer_shirt1', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1791', 'Soccer Shirt 10', '1', '0', 'soccer_shirt10', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1792', 'Soccer Shirt 11', '1', '0', 'soccer_shirt11', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1793', 'Soccer Shirt 12', '1', '0', 'soccer_shirt12', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1794', 'Soccer Shirt 13', '1', '0', 'soccer_shirt13', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1795', 'Soccer Shirt 14', '1', '0', 'soccer_shirt14', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1796', 'Soccer Shirt 15', '1', '0', 'soccer_shirt15', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1797', 'Soccer Shirt 16', '1', '0', 'soccer_shirt16', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1798', 'Soccer Shirt  17', '1', '0', 'soccer_shirt17', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1799', 'Soccer Shirt 18', '1', '0', 'soccer_shirt18', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1800', 'Soccer Shirt 19', '1', '0', 'soccer_shirt19', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1801', 'Soccer Shirt 2', '1', '0', 'soccer_shirt2', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1802', 'Soccer Shirt 20', '1', '0', 'soccer_shirt20', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1803', 'Soccer Shirt 3', '1', '0', 'soccer_shirt3', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1804', 'Soccer Shirt 4', '1', '0', 'soccer_shirt4', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1805', 'Soccer Shirt 5', '1', '0', 'soccer_shirt5', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1806', 'Soccer Shirt 6', '1', '0', 'soccer_shirt6', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1807', 'Soccer Shirt 7', '1', '0', 'soccer_shirt7', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1808', 'Soccer Shirt 8', '1', '0', 'soccer_shirt8', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1809', 'Soccer Shirt 9', '1', '0', 'soccer_shirt9', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1810', 'Soccer Short 1', '1', '0', 'soccer_short1', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1811', 'Soccer Short 10', '1', '0', 'soccer_short10', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1812', 'Soccer Short 11', '1', '0', 'soccer_short11', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1813', 'Soccer Short 12', '1', '0', 'soccer_short12', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1814', 'Soccer Short 13', '1', '0', 'soccer_short13', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1815', 'Soccer Short 14', '1', '0', 'soccer_short14', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1816', 'Soccer Short 15', '1', '0', 'soccer_short15', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1817', 'Soccer Short 16', '1', '0', 'soccer_short16', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1818', 'Soccer Short 17', '1', '0', 'soccer_short17', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1819', 'Soccer Short 18', '1', '0', 'soccer_short18', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1820', 'Soccer Short 19', '1', '0', 'soccer_short19', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1821', 'Soccer Short 2', '1', '0', 'soccer_short2', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1822', 'Soccer Short 20', '1', '0', 'soccer_short20', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1823', 'Soccer Short 3', '1', '0', 'soccer_short3', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1824', 'Soccer Short 4', '1', '0', 'soccer_short4', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1825', 'Soccer Short 5', '1', '0', 'soccer_short5', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1826', 'Soccer Short 6', '1', '0', 'soccer_short6', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1827', 'Soccer Short 7', '1', '0', 'soccer_short7', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1828', 'Soccer Short 8', '1', '0', 'soccer_short8', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1829', 'Soccer Short 9', '1', '0', 'soccer_short9', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1830', 'Sofresh', '1', '0', 'sofresh', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('1831', 'spiderwick Goblin 1 L', '1', '0', 'spiderwick_goblin1_l', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1832', 'spiderwick Goblin 1 R', '1', '0', 'spiderwick_goblin1_r', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1833', 'spiderwick Goblin 2 L', '1', '0', 'spiderwick_goblin2_l', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1834', 'spiderwick Goblin 2 R', '1', '0', 'spiderwick_goblin2_r', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1835', 'spiderwick Goblin Anim L', '1', '0', 'spiderwick_goblin_anim_l', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1836', 'spiderwick Goblin Anim R', '1', '0', 'spiderwick_goblin_anim_r', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1837', 'spiderwick Griffin L', '1', '0', 'spiderwick_griffin_l', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1838', 'spiderwick Griffin R', '1', '0', 'spiderwick_griffin_r', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1839', 'spiderwick Sprite 1 L', '1', '0', 'spiderwick_sprite1_l', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1840', 'spiderwick Sprite 1 R', '1', '0', 'spiderwick_sprite1_r', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1841', 'spiderwick Sprite 2 L', '1', '0', 'spiderwick_sprite2_l', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1842', 'spiderwick Sprite 2 R', '1', '0', 'spiderwick_sprite2_r', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1843', 'spiderwick Toadstooll', '1', '0', 'spiderwick_toadstooll', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1844', 'Spiderwick Toadstooll 2', '1', '0', 'spiderwick_toadstoolr', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1845', 'Spill 1', '1', '0', 'spill1', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1846', 'Spill 2', '1', '0', 'spill2', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1847', 'Spill 3', '1', '0', 'spill3', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1848', 'Spotlight Sticker', '1', '0', 'spotlight_sticker', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1849', 'Spotlight Sticker 2', '1', '0', 'spotlight_sticker2_001', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1852', 'Spray', '1', '0', 'spray1', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1853', 'Spray 2', '1', '0', 'spray2', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1854', 'Squib', '1', '0', 'squib', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1856', 'Snowstorm Boots Blue', '1', '0', 'sbootsitjalapaset_blue', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1857', 'Snowstorm Boots Red ', '1', '0', 'sbootsitjalapaset_red', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1858', 'Snowstorm Costume Blue', '1', '0', 'scostume_blue', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1859', 'Snowstorm Costume Red', '1', '0', 'scostume_red', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1860', 'Shitby Snowball', '1', '0', 'shitby_snowball', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1861', 'Snowstorm Ballmachine', '1', '0', 'ssnowballmachine', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1862', 'Snowstorm Flake', '1', '0', 'ssnowflake1', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1865', 'Snowstorm Flake 2', '1', '0', 'ssnowflake2', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1866', 'Snowstorm Man', '1', '0', 'ssnowman', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1867', 'Snowstorm Queen', '1', '0', 'ssnowqueen', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1868', 'Snowstorm Tree', '1', '0', 'ssnowtree', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1869', 'Stanley Cup Sticker', '1', '0', 'stanleycupsticker', '2', '1', '213');
INSERT INTO `cms_homes_catalouge` VALUES ('1870', 'Star', '1', '0', 'star', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('1872', 'Starburst', '1', '0', 'starburst', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1873', 'Stevie Richards', '1', '0', 'stevierichards', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('1874', 'Stewardes Costume', '1', '0', 'stewardescostume', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1875', 'Sticker Aunt Samantha', '1', '0', 'stickerauntsamantha', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1876', 'Sticker Flagborder', '1', '0', 'stickerflagborder', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1877', 'Sticker 3 Years', '1', '0', 'sticker_3years', '2', '1', '206');
INSERT INTO `cms_homes_catalouge` VALUES ('1879', 'Sticker Arrow Down', '1', '0', 'sticker_arrow_down', '2', '1', '222');
INSERT INTO `cms_homes_catalouge` VALUES ('1880', 'Sticker Arrow Left', '1', '0', 'sticker_arrow_left', '2', '1', '222');
INSERT INTO `cms_homes_catalouge` VALUES ('1881', 'Sticker Arrow Right', '1', '0', 'sticker_arrow_right', '2', '1', '222');
INSERT INTO `cms_homes_catalouge` VALUES ('1882', 'Sticker Arrow Up', '1', '0', 'sticker_arrow_up', '2', '1', '222');
INSERT INTO `cms_homes_catalouge` VALUES ('1883', 'Sticker Award Bronze', '1', '0', 'sticker_award_bronze', '2', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('1884', 'Sticker Award Gold', '1', '0', 'sticker_award_gold', '2', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('1886', 'Sticker Award Silver', '1', '0', 'sticker_award_silver', '2', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('1888', 'Sticker Bonsai Ninjaf', '1', '0', 'sticker_bonsai_ninjaf', '2', '1', '224');
INSERT INTO `cms_homes_catalouge` VALUES ('1889', 'Sticker Bonsai Ninjafa', '1', '0', 'sticker_bonsai_ninjafa', '2', '1', '224');
INSERT INTO `cms_homes_catalouge` VALUES ('1890', 'Sticker Bonsai Ninjam', '1', '0', 'sticker_bonsai_ninjam', '2', '1', '224');
INSERT INTO `cms_homes_catalouge` VALUES ('1891', 'Sticker Bonsai Ninjama', '1', '0', 'sticker_bonsai_ninjama', '2', '1', '224');
INSERT INTO `cms_homes_catalouge` VALUES ('1892', 'Sticker Bonsai Samuraif', '1', '0', 'sticker_bonsai_samuraif', '2', '1', '224');
INSERT INTO `cms_homes_catalouge` VALUES ('1893', 'Sticker Bonsai Samuraifa', '1', '0', 'sticker_bonsai_samuraifa', '2', '1', '224');
INSERT INTO `cms_homes_catalouge` VALUES ('1894', 'Sticker Bonsai Samuraim', '1', '0', 'sticker_bonsai_samuraim', '2', '1', '224');
INSERT INTO `cms_homes_catalouge` VALUES ('1895', 'Sticker Bonsai Samuraima', '1', '0', 'sticker_bonsai_samuraima', '2', '1', '224');
INSERT INTO `cms_homes_catalouge` VALUES ('1897', 'Sticker Boxer', '1', '0', 'sticker_boxer', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1898', 'Sticker Caballoons', '1', '0', 'sticker_caballoons', '2', '1', '213');
INSERT INTO `cms_homes_catalouge` VALUES ('1899', 'Sticker Cabin', '1', '0', 'sticker_cabin', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1900', 'Sticker Cactuanim', '1', '0', 'sticker_cactuanim', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1901', 'Sticker Cais 4', '1', '0', 'sticker_cais4', '2', '1', '213');
INSERT INTO `cms_homes_catalouge` VALUES ('1902', 'sticker_Cape', '1', '0', 'sticker_cape', '2', '1', '208');
INSERT INTO `cms_homes_catalouge` VALUES ('1903', 'Sticker Catinabox', '1', '0', 'sticker_catinabox', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1904', 'Sticker Chauvesouri', '1', '0', 'sticker_chauvesouri', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1905', 'Sticker Chauvesouris', '1', '0', 'sticker_chauvesouris', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1906', 'Sticker Chica_Tampax', '1', '0', 'sticker_chica_tampax', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1907', 'Sticker Chica_Tampax 1', '1', '0', 'sticker_chica_tampax1', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1908', 'Sticker Chica Tampax 2', '1', '0', 'sticker_chica_tampax2', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1909', 'Sticker Chips', '1', '0', 'sticker_chips', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1910', 'Sticker Clown Anim', '1', '0', 'sticker_clown_anim', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1911', 'Sticker Coffee Stain', '1', '0', 'sticker_coffee_stain', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1912', 'Sticker Coffee Steam Blue', '1', '0', 'sticker_coffee_steam_blue', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1913', 'Sticker Coffee Steam Grey', '1', '0', 'sticker_coffee_steam_grey', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1914', 'Sticker Croco', '1', '0', 'sticker_croco', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1915', 'Sticker DA Blingclock', '1', '0', 'sticker_da_blingclock', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1916', 'Sticker Dreamer', '1', '0', 'sticker_dreamer', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1917', 'Sticker Effect Bam', '1', '0', 'sticker_effect_bam', '2', '1', '240');
INSERT INTO `cms_homes_catalouge` VALUES ('1918', 'Sticker Effect Burp', '1', '0', 'sticker_effect_burp', '2', '1', '240');
INSERT INTO `cms_homes_catalouge` VALUES ('1919', 'Sticker Effect Whoosh 2', '1', '0', 'sticker_effect_whoosh2', '2', '1', '240');
INSERT INTO `cms_homes_catalouge` VALUES ('1920', 'Sticker Effect Woosh', '1', '0', 'sticker_effect_woosh', '2', '1', '240');
INSERT INTO `cms_homes_catalouge` VALUES ('1921', 'Sticker Effect Zap', '1', '0', 'sticker_effect_zap', '2', '1', '240');
INSERT INTO `cms_homes_catalouge` VALUES ('1922', 'Sticker Eraser', '1', '0', 'sticker_eraser', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1923', 'Sticker Eye Anim', '1', '0', 'sticker_eye_anim', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1924', 'Sticker Eye Evil Anim', '1', '0', 'sticker_eye_evil_anim', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1925', 'Sticker Eyeblue', '1', '0', 'sticker_eyeblue', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1926', 'Sticker Fireworkboom 3', '1', '0', 'sticker_fireworkboom3', '2', '1', '223');
INSERT INTO `cms_homes_catalouge` VALUES ('1927', 'Sticker Flames', '1', '0', 'sticker_flames', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1928', 'Sticker Flameskull', '1', '0', 'sticker_flameskull', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1929', 'Sticker Flower', '1', '0', 'sticker_flower1', '2', '1', '210');
INSERT INTO `cms_homes_catalouge` VALUES ('1930', 'Sticker Flower Big Yellow', '1', '0', 'sticker_flower_big_yellow', '2', '1', '210');
INSERT INTO `cms_homes_catalouge` VALUES ('1931', 'Sticker Flower Pink', '1', '0', 'sticker_flower_pink', '2', '1', '210');
INSERT INTO `cms_homes_catalouge` VALUES ('1932', 'Sticker Gala', '1', '0', 'sticker_gala', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1934', 'Sticker Gentleman', '1', '0', 'sticker_gentleman', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1935', 'Sticker Glasseelton', '1', '0', 'sticker_glasseelton', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1936', 'Sticker Glassesupernerd', '1', '0', 'sticker_glassesupernerd', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1945', 'Sticker Gurubeard Brown', '1', '0', 'sticker_gurubeard_brown', '2', '1', '208');
INSERT INTO `cms_homes_catalouge` VALUES ('1946', 'Sticker Gurubeard Gray', '1', '0', 'sticker_gurubeard_gray', '2', '1', '208');
INSERT INTO `cms_homes_catalouge` VALUES ('1947', 'Sticker Heartbeat', '1', '0', 'sticker_heartbeat', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('1949', 'Sticker Hole', '1', '0', 'sticker_hole', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1950', 'Sticker Hulkhogan', '1', '0', 'sticker_hulkhogan', '2', '1', '208');
INSERT INTO `cms_homes_catalouge` VALUES ('1951', 'Sticker Iheartfools', '1', '0', 'sticker_iheartfools', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('1952', 'Sticker Koopa', '1', '0', 'sticker_koopa', '2', '1', '212');
INSERT INTO `cms_homes_catalouge` VALUES ('1953', 'Sticker Lonewolf', '1', '0', 'sticker_lonewolf', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1954', 'Sticker Luigi', '1', '0', 'sticker_luigi', '2', '1', '212');
INSERT INTO `cms_homes_catalouge` VALUES ('1955', 'Sticker Mamasboy', '1', '0', 'sticker_mamasboy', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1956', 'Sticker Maquillage', '1', '0', 'sticker_maquillage', '2', '1', '208');
INSERT INTO `cms_homes_catalouge` VALUES ('1957', 'Sticker Mario', '1', '0', 'sticker_mario', '2', '1', '212');
INSERT INTO `cms_homes_catalouge` VALUES ('1958', 'Sticker Masque', '1', '0', 'sticker_masque_01', '2', '1', '208');
INSERT INTO `cms_homes_catalouge` VALUES ('1959', 'Sticker Masque 2', '1', '0', 'sticker_masque_02', '2', '1', '208');
INSERT INTO `cms_homes_catalouge` VALUES ('1960', 'Sticker Masque 3', '1', '0', 'sticker_masque_03', '2', '1', '208');
INSERT INTO `cms_homes_catalouge` VALUES ('1961', 'sticker_Masque 4', '1', '0', 'sticker_masque_04', '2', '1', '208');
INSERT INTO `cms_homes_catalouge` VALUES ('1962', 'Sticker Masque 5', '1', '0', 'sticker_masque_05', '2', '1', '208');
INSERT INTO `cms_homes_catalouge` VALUES ('1963', 'Sticker Masque Or', '1', '0', 'sticker_masque_or', '2', '1', '208');
INSERT INTO `cms_homes_catalouge` VALUES ('1964', 'Sticker Mathoffman', '1', '0', 'sticker_mathoffman', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('1965', 'Sticker Mineur', '1', '0', 'sticker_mineur', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1966', 'Sticker Monolithe', '1', '0', 'sticker_monolithe', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1967', 'Sticker Moonpig', '1', '0', 'sticker_moonpig', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1972', 'Sticker Peach', '1', '0', 'sticker_peach', '2', '1', '212');
INSERT INTO `cms_homes_catalouge` VALUES ('1973', 'Sticker Pencil', '1', '0', 'sticker_pencil', '2', '1', '229');
INSERT INTO `cms_homes_catalouge` VALUES ('1974', 'Sticker Pencil 2', '1', '0', 'sticker_pencil_2', '2', '1', '230');
INSERT INTO `cms_homes_catalouge` VALUES ('1975', 'Sticker Pointing Hand 1', '1', '0', 'sticker_pointing_hand_1', '2', '1', '222');
INSERT INTO `cms_homes_catalouge` VALUES ('1976', 'Sticker Pointing Hand 2', '1', '0', 'sticker_pointing_hand_2', '2', '1', '222');
INSERT INTO `cms_homes_catalouge` VALUES ('1977', 'Sticker Pointing Hand 3', '1', '0', 'sticker_pointing_hand_3', '2', '1', '222');
INSERT INTO `cms_homes_catalouge` VALUES ('1978', 'Sticker Pointing Hand 4', '1', '0', 'sticker_pointing_hand_4', '2', '1', '222');
INSERT INTO `cms_homes_catalouge` VALUES ('1979', 'Sticker Prankster', '1', '0', 'sticker_prankster', '2', '1', '230');
INSERT INTO `cms_homes_catalouge` VALUES ('1980', 'Sticker Romantic', '1', '0', 'sticker_romantic', '2', '1', '230');
INSERT INTO `cms_homes_catalouge` VALUES ('2654', 'Sticker Spaceduck', '1', '0', 'sticker_spaceduck', '2', '1', '230');
INSERT INTO `cms_homes_catalouge` VALUES ('1985', 'Sticker Sboard 1', '1', '0', 'sticker_sboard1', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('1986', 'Sticker Sboard 2', '1', '0', 'sticker_sboard2', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('1987', 'Sticker Sboard 3', '1', '0', 'sticker_sboard3', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('1988', 'Sticker Skull', '1', '0', 'sticker_skull', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1989', 'Sticker Skull 2', '1', '0', 'sticker_skull2', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('1990', 'Sticker Sleeping Habbo', '1', '0', 'sticker_sleeping_habbo', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('1992', 'Sticker Spiky Wristband', '1', '0', 'sticker_spiky_wristband', '2', '1', '230');
INSERT INTO `cms_homes_catalouge` VALUES ('1997', 'Sticker Squelette', '1', '0', 'sticker_squelette', '2', '1', '220');
INSERT INTO `cms_homes_catalouge` VALUES ('1998', 'Sticker Submarine', '1', '0', 'sticker_submarine', '2', '1', '230');
INSERT INTO `cms_homes_catalouge` VALUES ('1999', 'Sticker Teensberg', '1', '0', 'sticker_teensberg', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2000', 'Sticker Themepark 2', '1', '0', 'sticker_themepark_002', '2', '1', '228');
INSERT INTO `cms_homes_catalouge` VALUES ('2001', 'Sticker Themepark 3', '1', '0', 'sticker_themepark_003', '2', '1', '234');
INSERT INTO `cms_homes_catalouge` VALUES ('2002', 'Sticker Tiki Flamesboard', '1', '0', 'sticker_tiki_flamesboard', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2006', 'Sticker Tour', '1', '0', 'sticker_tour', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('2007', 'Sticker Trax Bling', '1', '0', 'sticker_trax_bling', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2008', 'Sticker Trax Heavy', '1', '0', 'sticker_trax_heavy', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2009', 'Sticker Trax Rock', '1', '0', 'sticker_trax_rock', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2010', 'Sticker Trophy Award', '1', '0', 'sticker_trophy_award', '2', '1', '230');
INSERT INTO `cms_homes_catalouge` VALUES ('2011', 'Sticker Unclesam', '1', '0', 'sticker_unclesam', '2', '1', '223');
INSERT INTO `cms_homes_catalouge` VALUES ('2012', 'Sticker Woodboard', '1', '0', 'sticker_woodboard', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2013', 'Sticker Zipper H Bobba Lock', '1', '0', 'sticker_zipper_h_bobba_lock', '2', '1', '215');
INSERT INTO `cms_homes_catalouge` VALUES ('2014', 'Sticker Zipper H End', '1', '0', 'sticker_zipper_h_end', '2', '1', '215');
INSERT INTO `cms_homes_catalouge` VALUES ('2015', 'Sticker Zipper H Normal Lock', '1', '0', 'sticker_zipper_h_normal_lock', '2', '1', '215');
INSERT INTO `cms_homes_catalouge` VALUES ('2016', 'Sticker Zipper H Tile', '1', '0', 'sticker_zipper_h_tile', '2', '1', '215');
INSERT INTO `cms_homes_catalouge` VALUES ('2017', 'Sticker Zipper V Bobba Lock', '1', '0', 'sticker_zipper_v_bobba_lock', '2', '1', '215');
INSERT INTO `cms_homes_catalouge` VALUES ('2018', 'Sticker Zipper V End', '1', '0', 'sticker_zipper_v_end', '2', '1', '215');
INSERT INTO `cms_homes_catalouge` VALUES ('2019', 'Sticker Zipper V Normal Lock', '1', '0', 'sticker_zipper_v_normal_lock', '2', '1', '215');
INSERT INTO `cms_homes_catalouge` VALUES ('2020', 'Sticker Zipper V Tile', '1', '0', 'sticker_zipper_v_tile', '2', '1', '215');
INSERT INTO `cms_homes_catalouge` VALUES ('2021', 'Goth Border Vertical', '1', '0', 'goth_border_vertical', '2', '1', '215');
INSERT INTO `cms_homes_catalouge` VALUES ('2022', 'Stonecold', '1', '0', 'stonecold', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('2023', 'Stray Pixels Winner', '1', '0', 'straypixelswinner', '2', '1', '230');
INSERT INTO `cms_homes_catalouge` VALUES ('2024', 'Streaker', '1', '0', 'streaker', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('2025', 'Summer Flowerwreath', '1', '0', 'summerflowerwreath', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2026', 'Summer Beachballafro', '1', '0', 'summer_beachballafro', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2027', 'Summer Blueberry Left', '1', '0', 'summer_blueberry_left', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2031', 'Summer Blueberry Right', '1', '0', 'summer_blueberry_right', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2033', 'Summer Cloud 1', '1', '0', 'summer_cloud1', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2035', 'Summer Cloud 2', '1', '0', 'summer_cloud2', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2037', 'Summer Cloud 3', '1', '0', 'summer_cloud3', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2039', 'Summer Flowerwreath 2', '1', '0', 'summer_flowerwreath', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2040', 'Summer Kite', '1', '0', 'summer_kite', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2043', 'Summer Rollerblades', '1', '0', 'summer_rollerblades', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2044', 'Summer Rowingboat', '1', '0', 'summer_rowingboat', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2045', 'Summer Skateboard', '1', '0', 'summer_skateboard', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2046', 'Summer Swim Trunk', '1', '0', 'summer_swim_trunk', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2047', 'Summer sticker_Strawberry', '1', '0', 'summersticker_strawberry', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2048', 'Sunflower', '1', '0', 'sunflower', '2', '1', '210');
INSERT INTO `cms_homes_catalouge` VALUES ('2050', 'Supercrazy', '1', '0', 'supercrazy', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('2051', 'Superjatt', '1', '0', 'superjatt', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2052', 'Surflifesaver', '1', '0', 'surflifesaver', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2053', 'Sushi Ika Squid', '1', '0', 'sushi_ika_squid', '2', '1', '224');
INSERT INTO `cms_homes_catalouge` VALUES ('2054', 'Sushi Ikura Caviar', '1', '0', 'sushi_ikura_caviar', '2', '1', '224');
INSERT INTO `cms_homes_catalouge` VALUES ('2055', 'Sushi Kohada Mackerel', '1', '0', 'sushi_kohada_mackerel', '2', '1', '224');
INSERT INTO `cms_homes_catalouge` VALUES ('2056', 'Sushi Maguro', '1', '0', 'sushi_maguro', '2', '1', '224');
INSERT INTO `cms_homes_catalouge` VALUES ('2057', 'Sushi Tamago Egg', '1', '0', 'sushi_tamago_egg', '2', '1', '224');
INSERT INTO `cms_homes_catalouge` VALUES ('2058', 'Sushi Uni Sea Urchin', '1', '0', 'sushi_uni_sea_urchin', '2', '1', '224');
INSERT INTO `cms_homes_catalouge` VALUES ('2059', 'Swimming Fish', '1', '0', 'swimming_fish', '2', '1', '230');
INSERT INTO `cms_homes_catalouge` VALUES ('2060', 'T', '1', '0', 't', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('2061', 'Tahti', '1', '0', 'tahti', '2', '1', '237');
INSERT INTO `cms_homes_catalouge` VALUES ('2062', 'Tall Ship', '1', '0', 'tall_ship', '2', '1', '218');
INSERT INTO `cms_homes_catalouge` VALUES ('2064', 'Tampax Signboard', '1', '0', 'tampax_signboard', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('2068', 'Tepetepe 2', '1', '0', 'tepetepe2', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2069', 'Thanksgiving 07', '1', '0', 'thanksgiving07', '2', '1', '230');
INSERT INTO `cms_homes_catalouge` VALUES ('2070', 'The Great Khali', '1', '0', 'thegreatkhali', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('2071', 'The Miz', '1', '0', 'themiz', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('2072', 'The Safety Box', '1', '0', 'thesafetybox', '2', '1', '230');
INSERT INTO `cms_homes_catalouge` VALUES ('2073', 'Tiki Cloudtiki L', '1', '0', 'tiki_cloudtiki_l', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2074', 'Tiki Cloudtiki R', '1', '0', 'tiki_cloudtiki_r', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2075', 'Tiki Flowersboard', '1', '0', 'tiki_flowersboard', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2076', 'Tiki Greenboard', '1', '0', 'tiki_greenboard', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2077', 'Tiki Moarider F', '1', '0', 'tiki_moarider_f', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2078', 'Tiki Moarider M', '1', '0', 'tiki_moarider_m', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2079', 'Tiki Planttiki L', '1', '0', 'tiki_planttiki_l', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2080', 'Tiki Planttiki R', '1', '0', 'tiki_planttiki_r', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2081', 'Tiki Skulltiki L', '1', '0', 'tiki_skulltiki_l', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2082', 'Tiki Skulltiki R', '1', '0', 'tiki_skulltiki_r', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2083', 'Tiki Watertiki L', '1', '0', 'tiki_watertiki_l', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2084', 'Tiki Watertiki R', '1', '0', 'tiki_watertiki_r', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2085', 'Tiki Woodboard', '1', '0', 'tiki_woodboard', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('2086', 'Toffee Tarra', '1', '0', 'toffee_tarra', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('2087', 'Tokfia', '1', '0', 'tokfia', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('2088', 'Tommy Dreamer', '1', '0', 'tommydreamer', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('2089', 'Tomo', '1', '0', 'tomo', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('2090', 'Toolbar Sticker', '1', '0', 'toolbar_sticker', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2091', 'Torrie Wilson', '1', '0', 'torriewilson', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('2092', 'Tproll', '1', '0', 'tproll', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('2093', 'Tracinho', '1', '0', 'tracinho', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('2094', 'Trax 8 bit', '1', '0', 'trax_8_bit', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('2095', 'Trax Ambient', '1', '0', 'trax_ambient', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('2098', 'Trax Bling', '1', '0', 'trax_bling', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('2101', 'Trax Disco', '1', '0', 'trax_disco', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('2103', 'Trax Electro', '1', '0', 'trax_electro', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('2106', 'Trax Heavy', '1', '0', 'trax_heavy', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('2108', 'Trax Latin', '1', '0', 'trax_latin', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('2110', 'Trax Log Not for sale', '1', '0', 'trax_log_not_for_sale', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2111', 'Trax Reggae', '1', '0', 'trax_reggae', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('2112', 'Trax Rock', '1', '0', 'trax_rock', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('2114', 'Trax Sfx', '1', '0', 'trax_sfx', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('2116', 'Trax Goldrecord', '1', '0', 'traxgoldrecord', '2', '1', '230');
INSERT INTO `cms_homes_catalouge` VALUES ('2118', 'Tree Owl', '1', '0', 'tree_owl', '2', '1', '230');
INSERT INTO `cms_homes_catalouge` VALUES ('2119', 'Treestump', '1', '0', 'treestump', '2', '1', '230');
INSERT INTO `cms_homes_catalouge` VALUES ('2120', 'Tripleh', '1', '0', 'tripleh', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('2121', 'U', '1', '0', 'u', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('2122', 'UK Childline', '1', '0', 'uk_childline_sticker', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('2123', 'UK Habbo X', '1', '0', 'uk_habbo_x_sticker', '2', '1', '230');
INSERT INTO `cms_homes_catalouge` VALUES ('2124', 'UK Pixel Maze', '1', '0', 'uk_pixel_maze_sticker', '2', '1', '204');
INSERT INTO `cms_homes_catalouge` VALUES ('2125', 'UK Only Homes', '1', '0', 'ukonly_disarno_homes', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2126', 'Umaga', '1', '0', 'umaga', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('2127', 'Underscore', '1', '0', 'underscore', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('2128', 'Undertaker', '1', '0', 'undertaker', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('2130', 'uFootball Guest', '1', '0', 'ufootball_guest', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2131', 'V', '1', '0', 'v', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('2134', 'Valentine Cupido', '1', '0', 'val_cupido_anim', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('2136', 'Valentine Costume 3', '1', '0', 'val_lovecostume3', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('2139', 'Valentine Lovedice', '1', '0', 'val_lovedice', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('2140', 'Valentine Skull 360 Around', '1', '0', 'val_skull360around_anim', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('2141', 'Valentine Barbwire Horis', '1', '0', 'val_sticker_barbwire_horis', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('2143', 'Valentine Barbwire Vert', '1', '0', 'val_sticker_barbwire_vert', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('2146', 'Valentine Bartender', '1', '0', 'val_sticker_bartender', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('2147', 'Valentine Bartender2', '1', '0', 'val_sticker_bartender2', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('2148', 'Valentine Bobbadice', '1', '0', 'val_sticker_bobbadice', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('2150', 'Valentine Crew', '1', '0', 'val_sticker_crew', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('2151', 'Valentine Croco', '1', '0', 'val_sticker_croco', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('2152', 'Valentine Cupid Arrow', '1', '0', 'val_sticker_cupid_arrow', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('2153', 'Valentine Femalecaptain', '1', '0', 'val_sticker_femalecaptain', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('2157', 'Valentine Malecaptain', '1', '0', 'val_sticker_malecaptain', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('2158', 'Valentine Malecrew', '1', '0', 'val_sticker_malecrew', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('2159', 'Valentine Rosewire Corner', '1', '0', 'val_sticker_rosewire_corner', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('2162', 'Valentine Rosewire Horis', '1', '0', 'val_sticker_rosewire_horis', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('2163', 'Valentine Rosewire Vert', '1', '0', 'val_sticker_rosewire_vert', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('2165', 'Valentine Skull 360', '1', '0', 'val_sticker_skull360', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('2166', 'Valentine Skull 360 Circle', '1', '0', 'val_sticker_skull360_circle', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('2167', 'Valentine Stewardess', '1', '0', 'val_sticker_stewardess', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('2168', 'Valentine Stewardess 2', '1', '0', 'val_sticker_stewardess2', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('2169', 'Valentine Storm Costume', '1', '0', 'val_sticker_storm-costume', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('2171', 'Valentine Voodoo Suit', '1', '0', 'val_sticker_voodoo_suit', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('2172', 'Valentine Captain', '1', '0', 'valcaptain', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('2174', 'Valentine Welcome Sticker', '1', '0', 'valentine_welcome_sticker', '2', '1', '226');
INSERT INTO `cms_homes_catalouge` VALUES ('2177', 'Valvenis', '1', '0', 'valvenis', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('2178', 'Vanilja Tarra', '1', '0', 'vanilja_tarra', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('2179', 'Venezuela', '1', '0', 'venezuela', '2', '1', '232');
INSERT INTO `cms_homes_catalouge` VALUES ('2180', 'Venti', '1', '0', 'venti', '2', '1', '230');
INSERT INTO `cms_homes_catalouge` VALUES ('2181', 'Veronicas', '1', '0', 'veronicas', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('2182', 'Vertical Ink', '1', '0', 'verticalink', '2', '1', '238');
INSERT INTO `cms_homes_catalouge` VALUES ('2184', 'Victoria', '1', '0', 'victoria', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('2185', 'Vincent Viga', '1', '0', 'vincentviga', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('2186', 'Vine', '1', '0', 'vine', '2', '1', '215');
INSERT INTO `cms_homes_catalouge` VALUES ('2187', 'Vine 2', '1', '0', 'vine2', '2', '1', '215');
INSERT INTO `cms_homes_catalouge` VALUES ('2188', 'VIP Pin', '1', '0', 'vip_pin', '2', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('2189', 'Voice Articleimage', '1', '0', 'voice_articleimage', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2191', 'W', '1', '0', 'w', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('2192', 'Whimsy', '1', '0', 'whimsy', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2193', 'Wood A', '1', '0', 'wood_a', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2194', 'Wood Acircle', '1', '0', 'wood_acircle', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2195', 'Wood Acsent', '1', '0', 'wood_acsent', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2196', 'Wood Acsent 2', '1', '0', 'wood_acsent2', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2197', 'Wood Adots', '1', '0', 'wood_adots', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2198', 'Wood B', '1', '0', 'wood_b', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2199', 'Wood C', '1', '0', 'wood_c', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2200', 'Wood Comma', '1', '0', 'wood_comma', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2201', 'Wood D', '1', '0', 'wood_d', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2202', 'Wood Dot', '1', '0', 'wood_dot', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2203', 'Wood E', '1', '0', 'wood_e', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2204', 'Wood Exclamation', '1', '0', 'wood_exclamation', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2205', 'Wood F', '1', '0', 'wood_f', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2206', 'Wood G', '1', '0', 'wood_g', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2207', 'Wood H', '1', '0', 'wood_h', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2208', 'Wood I', '1', '0', 'wood_i', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2209', 'Wood J', '1', '0', 'wood_j', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2210', 'Wood K', '1', '0', 'wood_k', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2211', 'Wood L', '1', '0', 'wood_l', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2212', 'Wood M', '1', '0', 'wood_m', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2213', 'Wood N', '1', '0', 'wood_n', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2214', 'Wood O', '1', '0', 'wood_o', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2215', 'Wood Odots', '1', '0', 'wood_odots', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2216', 'Wood P', '1', '0', 'wood_p', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2217', 'Wood Q', '1', '0', 'wood_q', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2218', 'Wood Question', '1', '0', 'wood_question', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2219', 'Wood R', '1', '0', 'wood_r', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2220', 'Wood S', '1', '0', 'wood_s', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2221', 'Wood T', '1', '0', 'wood_t', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2222', 'Wood U', '1', '0', 'wood_u', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2223', 'Wood Undermark', '1', '0', 'wood_undermark', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2224', 'Wood V', '1', '0', 'wood_v', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2225', 'Wood W', '1', '0', 'wood_w', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2226', 'Wood X', '1', '0', 'wood_x', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2227', 'Wood Y', '1', '0', 'wood_y', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2228', 'Wood Z', '1', '0', 'wood_z', '2', '1', '227');
INSERT INTO `cms_homes_catalouge` VALUES ('2229', 'Wormhand', '1', '0', 'wormhand', '2', '1', '220');
INSERT INTO `cms_homes_catalouge` VALUES ('2230', 'Wunder Frank', '1', '0', 'wunderfrank', '2', '1', '230');
INSERT INTO `cms_homes_catalouge` VALUES ('2231', 'Wordwrestling', '1', '0', 'wwemvp', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('2232', 'X', '1', '0', 'x', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('2233', 'Xmas Angel Wings', '1', '0', 'xmaangel_wings', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2234', 'Xmas Angel Wing Animated', '1', '0', 'xmaangelwinganim', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2236', 'Xmas Box Darkred', '1', '0', 'xmabox_darkred', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2237', 'Xmas Box Darkred 2', '1', '0', 'xmabox_darkred2', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2238', 'Xmas Box Darkred 3', '1', '0', 'xmabox_darkred4', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2239', 'Xmas Box Green', '1', '0', 'xmabox_green', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2240', 'Xmas Box Green 2', '1', '0', 'xmabox_green_2', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2241', 'Xmas Box Lime', '1', '0', 'xmabox_lime', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2242', 'Xmas Box Lime 2', '1', '0', 'xmabox_lime_2', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2243', 'Xmas Box Orange', '1', '0', 'xmabox_orange', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2244', 'Xmas Box Red', '1', '0', 'xmabox_red', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2245', 'Xmas Box Suit Blue', '1', '0', 'xmabox_suit_blue', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2246', 'Xmas Box Suit Mint', '1', '0', 'xmabox_suit_mint', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2248', 'Xmas Box Suit Mint 2', '1', '0', 'xmabox_suit_mint2', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2249', 'Xmas Box Suit Mint 3', '1', '0', 'xmabox_suit_mint3', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2250', 'Xmas Box Suit Orange', '1', '0', 'xmabox_suit_orange', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2251', 'Xmas Box Suit Pink', '1', '0', 'xmabox_suit_pink', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2252', 'Xmas Box Violet', '1', '0', 'xmabox_violet', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2253', 'Xmas Box', '1', '0', 'xmaboxs', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2255', 'Xmas Cat Animated', '1', '0', 'xmacat_animated', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2256', 'Xmas Dog Animated', '1', '0', 'xmadogi_animated', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2257', 'Xmas Dog Reindeer Sticker', '1', '0', 'xmadograindeer_sticker', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2258', 'Xmas Dog Reindeer Thumb', '1', '0', 'xmadograindeer_sticker.thumb', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2259', 'Xmas Gift-Afro', '1', '0', 'xmagift-afro', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2260', 'Xmas Gift Afro', '1', '0', 'xmagift_afro', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2261', 'Xmas Gift Strap Corner L', '1', '0', 'xmagift_strap_corner_l', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2262', 'Xmas Gift Strap Corner Bundle', '1', '0', 'xmagift_strap_corner_l_bundle', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2263', 'Xmas Gift Strap Corner R', '1', '0', 'xmagift_strap_corner_r', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2264', 'Xmas Gift Strap H', '1', '0', 'xmagift_strap_h', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2265', 'Xmas Gift Strap V', '1', '0', 'xmagift_strap_v', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2266', 'Xmas HC Ribbon', '1', '0', 'xmahc_ribbon', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2267', 'Xmas HC Ribbon 2', '1', '0', 'xmahc_ribbon_2', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2268', 'Xmas Cicles', '1', '0', 'xmaicicles', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2269', 'Xmas Donkey Reindeer Sticker', '1', '0', 'xmajeff_donkey_reindeer_sticker', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2270', 'Xmas Sinister', '1', '0', 'xmamr_sinister', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2271', 'Xmas Rasta Santa', '1', '0', 'xmarastasanta', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2272', 'Xmas Skater Costume', '1', '0', 'xmaskater_costume', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2273', 'Xmas Smilla Snowboard', '1', '0', 'xmasmilla_snowboard', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2274', 'Xmas Snowcone Costume', '1', '0', 'xmasnowcone_costume', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2275', 'Xmas Snowlantern', '1', '0', 'xmasnowlantern_anim', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2276', 'Xmas Reindeer Dog', '1', '0', 'xmasticker_dograindeer', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2277', 'Xmas Horizontale Gold', '1', '0', 'xmastrap_horiz_gold', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2278', 'Xmas Horizontale Silver', '1', '0', 'xmastrap_horiz_silve', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2279', 'Xmas Vertical Gold', '1', '0', 'xmastrap_vertical_gold', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2280', 'Xmas Vertical Silver', '1', '0', 'xmastrap_vertical_silver', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2281', 'Xmas Animated Thumb', '1', '0', 'xmatree01_animated', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2282', 'Xmas Animated Thumb 2', '1', '0', 'xmatree01_animated_thumb2', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2283', 'Xmas Tree Costume', '1', '0', 'xmatree_costume', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2284', 'Xmas Tree Sticker', '1', '0', 'xmaxtree_sticker', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2285', 'Xmas Light Animated', '1', '0', 'xmaslightanim', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2286', 'Xmas 3000 Animated Sticker', '1', '0', 'xmassticker_anim_3000', '2', '1', '230');
INSERT INTO `cms_homes_catalouge` VALUES ('2287', 'Y', '1', '0', 'y', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('2288', 'Yearbook Ribbon Sticker', '1', '0', 'yearbook_ribbon_sticker', '2', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('2289', 'Yellow Starfish', '1', '0', 'yellowstarfish', '2', '1', '230');
INSERT INTO `cms_homes_catalouge` VALUES ('2290', 'Z', '1', '0', 'z', '2', '1', '203');
INSERT INTO `cms_homes_catalouge` VALUES ('2291', 'Zack Ryder', '1', '0', 'zackryder', '2', '1', '209');
INSERT INTO `cms_homes_catalouge` VALUES ('2292', 'Zombie Hand', '1', '0', 'zombiehand', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2293', 'Zombie Pupu', '1', '0', 'zombiepupu', '2', '1', '220');
INSERT INTO `cms_homes_catalouge` VALUES ('2294', 'Kaatissakki Tausta', '4', '0', '27368_kaatissakki_tausta', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2295', 'Appart. 723 Scene', '4', '0', '27419_appart732_scene', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2296', 'Get it Card Background', '4', '0', '27835_getitcard_bg', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2297', 'GSOK', '4', '0', '27857_gsok_928x1360', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2298', 'HMF Principale', '4', '0', '28182_hmf_917x1360_principale', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2299', 'HMF Dediee', '4', '0', '28183_hmf_917x1360_dediee', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2300', 'HMF Principale B', '4', '0', '28184_hmf_917x1360_principale_b', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2301', 'Comic Style Orange', '4', '0', '28221_comic_style_orange', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2302', 'Productboard', '4', '0', '928x1360_productboard', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2303', 'Abrinq Infobus', '4', '0', 'abrinq_infobus', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2304', 'Abrinq Infobus G', '4', '0', 'abrinq_infobusg', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2305', 'Acma Cork Background', '4', '0', 'acma_cork_bg', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2306', 'Alhambra Group', '4', '0', 'alhambragroup', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2307', 'All Habbos Group', '4', '0', 'allhabbos_group', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2308', 'Amango Background', '4', '0', 'amango_bg', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2309', 'Ametrin', '4', '0', 'ametrin', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2310', 'Armin van Buren Background', '4', '0', 'arminvanbuuren_928x1360', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2311', 'Around the World Background', '4', '0', 'aroundtheworld_bg', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2312', 'AU Rock The Schools Background', '4', '0', 'au_rocktheschools_bg', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2313', 'AU Rock The Schools Background 2', '4', '0', 'au_rocktheschools_bg_v2', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2314', 'Background Tour', '4', '0', 'background_tour', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2315', 'Group Background', '4', '0', 'bgroup', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2316', 'Beachbunny Wallpaper', '4', '0', 'beachbunny_wallpaper', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2317', 'SPI Japaneese Petit', '4', '0', 'bg_27372_spi_jap_petit_03', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2318', 'Comic Style Orange Background', '4', '0', 'bg_28221_comic_style_orange_001', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2320', 'Background EF', '4', '0', 'bg_background_ef', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2321', 'Bathroom Tile Background', '4', '0', 'bg_bathroom_tile', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2322', 'Battle Ball Background 2', '4', '0', 'bg_battle_ball2', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2323', 'Infobus White Background 2', '4', '0', 'bg_bg_infobus_white', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2324', 'BHabboeart Background', '4', '0', 'bg_bHabboeart', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2325', 'Bonsai Background', '4', '0', 'bg_bonsai', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2326', 'Broken Glass Background', '4', '0', 'bg_broken_glass', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2327', 'Bubble Background', '4', '0', 'bg_bubble', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2328', 'Christmas 2007 Background', '4', '0', 'bg_christmas2007bg', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2330', 'Colour 01 Background', '4', '0', 'bg_colour_01', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2331', 'Colour 02 Background', '4', '0', 'bg_colour_02', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2332', 'Colour 03 Background', '4', '0', 'bg_colour_03', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2333', 'Colour 04 Background', '4', '0', 'bg_colour_04', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2334', 'Colour 05 Background', '4', '0', 'bg_colour_05', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2335', 'Colour 06 Background', '4', '0', 'bg_colour_06', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2336', 'Colour 07 Background', '4', '0', 'bg_colour_07', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2337', 'Colour 08 Background', '4', '0', 'bg_colour_08', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2338', 'Colour 09 Background', '4', '0', 'bg_colour_09', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2339', 'Colour 10 Background', '4', '0', 'bg_colour_10', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2340', 'Colour 11 Background', '4', '0', 'bg_colour_11', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2341', 'Colour 12 Background', '4', '0', 'bg_colour_12', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2342', 'Colour 13 Background', '4', '0', 'bg_colour_13', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2343', 'Colour 14 Background', '4', '0', 'bg_colour_14', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2344', 'Colour 15 Background', '4', '0', 'bg_colour_15', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2345', 'Colour 16 Background', '4', '0', 'bg_colour_16', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2346', 'Colour 17 Background', '4', '0', 'bg_colour_17', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2347', 'Comic Background', '4', '0', 'bg_comic2', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2348', 'Comic Sisters Background', '4', '0', 'bg_comic_sisters', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2349', 'Cork Background', '4', '0', 'bg_cork', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2350', 'Dark Floors Background', '4', '0', 'bg_dark_floors', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2351', 'Denim Background', '4', '0', 'bg_denim', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2352', 'Easter Eggs Background', '4', '0', 'bg_easter_eggs_wallpaper', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2353', 'Fansites Background', '4', '0', 'bg_fansites', '2', '1', '127');
INSERT INTO `cms_homes_catalouge` VALUES ('2354', 'Goth Pattern Background', '4', '0', 'bg_goth_pattern', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2355', 'Grass Background', '4', '0', 'bg_grass', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2356', 'Habbo Lido Background', '4', '0', 'bg_habbolido', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2357', 'Habboween Background', '4', '0', 'bg_habboween', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2358', 'Hotel Background', '4', '0', 'bg_hotel', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2359', 'Valentine Love Background', '4', '0', 'bg_hw_val_bgpattern_love', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2360', 'Image Submarine Background', '4', '0', 'bg_image_submarine', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2361', 'Infobus Blue Background', '4', '0', 'bg_infobus_blue', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2362', 'Infobus White Background', '4', '0', 'bg_infobus_white', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2363', 'Infobus Yellow Background', '4', '0', 'bg_infobus_yellow', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2364', 'Kerrang', '4', '0', 'bg_kerrang2', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2365', 'Kingcorp Background', '4', '0', 'bg_kingcorp', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2366', 'Kingcorp Background 2', '4', '0', 'bg_kingcorp_928x1360', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2367', 'Konkur Rence Background', '4', '0', 'bg_konkurrence', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2368', 'Konkur Renceno Background', '4', '0', 'bg_konkurrenceno', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2369', 'Lace Background', '4', '0', 'bg_lace', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2370', 'Lido Flat Background', '4', '0', 'bg_lido_flat', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2371', 'Lido Background', '4', '0', 'bg_lidoo', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2372', 'Madball 2008 Background', '4', '0', 'bg_madball_2008_bg_001', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2373', 'Metal Background 2', '4', '0', 'bg_metal2', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2374', 'Natasha Bedingfield Background', '4', '0', 'bg_natashabedingfield', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2375', 'Newyear Background', '4', '0', 'bg_newyear', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2376', 'Newyear Fireworks Background', '4', '0', 'bg_newyear_bg_fireworks', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2377', 'Official Fansites Background', '4', '0', 'bg_official_fansites2', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2378', 'Pattern Abstract Background', '4', '0', 'bg_pattern_abstract1', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2379', 'Pattern Abstract Background 2', '4', '0', 'bg_pattern_abstract2', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2380', 'Pattern Bobbaskulls  Background', '4', '0', 'bg_pattern_bobbaskulls1', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2381', 'Pattern Bricks Background', '4', '0', 'bg_pattern_bricks', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2382', 'Pattern Bulb Background', '4', '0', 'bg_pattern_bulb', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2383', 'Pattern Carpants Background', '4', '0', 'bg_pattern_carpants', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2384', 'Pattern Cars Background', '4', '0', 'bg_pattern_cars', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2385', 'Pattern Clouds Background', '4', '0', 'bg_pattern_clouds', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2386', 'Pattern Deepred Background', '4', '0', 'bg_pattern_deepred', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2387', 'Pattern Fish Background', '4', '0', 'bg_pattern_fish', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2388', 'Pattern Floral Background 1', '4', '0', 'bg_pattern_floral_01', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2389', 'Pattern Floral Background 2', '4', '0', 'bg_pattern_floral_02', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2390', 'Pattern Floral Background 3', '4', '0', 'bg_pattern_floral_03', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2391', 'Pattern Floral Background', '4', '0', 'bg_pattern_floral_test', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2392', 'Pattern Hearts Background', '4', '0', 'bg_pattern_hearts', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2393', 'Pattern Plasto Background', '4', '0', 'bg_pattern_plasto', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2394', 'Pattern Space Background', '4', '0', 'bg_pattern_space', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2395', 'Pattern Tinyroom Background', '4', '0', 'bg_pattern_tinyroom', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2396', 'Poptarts CV Background', '4', '0', 'bg_poptarts_cv', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2397', 'Poptarts SB Background', '4', '0', 'bg_poptarts_sb', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2398', 'Rain Background', '4', '0', 'bg_rain', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2399', 'Rasta Background', '4', '0', 'bg_rasta_99x99', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2400', 'Ruled Paper Background', '4', '0', 'bg_ruled_paper', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2401', 'Serpentine Background', '4', '0', 'bg_serpentine_1', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2402', 'Serpentine  Background 2', '4', '0', 'bg_serpentine_2', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2403', 'Serpentine Darkblue Background', '4', '0', 'bg_serpentine_darkblue', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2404', 'Serpentine Darkred Background', '4', '0', 'bg_serpentine_darkred', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2405', 'Serpentine Darkred Background 2', '4', '0', 'bg_serpntine_darkred', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2406', 'Sexy Dance Background', '4', '0', 'bg_sexy_dance_2_group_opt2', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2407', 'Shabbolin Background', '4', '0', 'bg_shabbolin', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2408', 'Solid Black Background', '4', '0', 'bg_solid_black', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2409', 'Solid White Background', '4', '0', 'bg_solid_white', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2410', 'Starburst Raspberry Background', '4', '0', 'bg_starburst_raspberry', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2411', 'Stitched Background', '4', '0', 'bg_stitched', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2412', 'Stone Background', '4', '0', 'bg_stone', '2', '1', '128');
INSERT INTO `cms_homes_catalouge` VALUES ('2413', 'Tonga Background', '4', '0', 'bg_tonga', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2414', 'Unofficial Fansites Background', '4', '0', 'bg_unofficial_fansites', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2415', 'US Staffers Background', '4', '0', 'bg_us_staffers', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2416', 'VIP Background', '4', '0', 'bg_vip', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2417', 'Voice of Teens Background', '4', '0', 'bg_voiceofteens', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2418', 'Wobble Squabble Background', '4', '0', 'bg_wobble_squabble', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2419', 'Wood Background', '4', '0', 'bg_wood', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2420', 'Xmas Starsky Background', '4', '0', 'bg_xmasbgpatternstarsky', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2421', 'Xmas Gift Background', '4', '0', 'bg_xmas_gift', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2422', 'Starysky  Background', '4', '0', 'bgpattern_starsky', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2423', 'Bionicle 2', '4', '0', 'bionicle2', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2424', 'Bubblejuice Background', '4', '0', 'bubblejuice_bg', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2425', 'Camo Cheese Background', '4', '0', 'camo_cheese_wallpaper', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2426', 'Cheese Suit', '4', '0', 'cheese_suit', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2427', 'Cheesewedge Background', '4', '0', 'cheesewedge_wallpaper', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2429', 'Christmas Background 2 ', '4', '0', 'christmas2007bg_001', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2430', 'CN Background', '4', '0', 'cn_mgpam_bg', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2431', 'CN Background 2', '4', '0', 'cn_mgpam_bg_v2', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2432', 'CN Background 3', '4', '0', 'cn_mgpam_bg_v3', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2433', 'China Kunfu Girl', '4', '0', 'cny_kunfu_girl', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2434', 'Comic Style', '4', '0', 'comic_style', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2435', 'Dia de Internet 2008', '4', '0', 'diadeinternet2008', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2436', 'Donnas Background 2', '4', '0', 'donnaswallpaper', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2437', 'Donnas Background', '4', '0', 'donnas_wallpaper', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2438', 'Earthday Background', '4', '0', 'earthday_bk', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2439', 'Easter Birdie', '4', '0', 'easter_birdie', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2440', 'Easter Broomstick', '4', '0', 'easter_broomstick_002', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2441', 'Easter Eggs Wallpaper', '4', '0', 'easter_egg_wallpaper', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2442', 'Easter Eggs Horizontal', '4', '0', 'easter_eggs_horizontal', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2443', 'Easter Eggs Vertical', '4', '0', 'easter_eggs_vertical_001', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2446', 'Easter Egg Costume', '4', '0', 'easteregg_costume', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2447', 'E-Wallpaper', '4', '0', 'e928x1360', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2448', 'ES Calandar Background', '4', '0', 'es_calendarbg', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2449', 'ES Italia Background', '4', '0', 'es_wallpaper_italia', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2450', 'ES Obrero Background', '4', '0', 'es_wallpaper_obrero', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2451', 'ES Sralim Background', '4', '0', 'es_wallpaper_sralim', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2452', 'Executive Background', '4', '0', 'exe_background', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2453', 'Executive Wood Background', '4', '0', 'exe_wood_background', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2454', 'Executive Viilu Background', '4', '0', 'exec_bg_viilu', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2455', 'Expert Background', '4', '0', 'expert_backround', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2456', 'Fantastic Four 3', '4', '0', 'fan43', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2457', 'Fantastic Four 1', '4', '0', 'fantastic4', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2458', 'Fantastic Four 2', '4', '0', 'fantastic42', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2459', 'Fantastic Four', '4', '0', 'fantasticfour', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2460', 'Felix 3rd', '4', '0', 'felix03', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2461', 'Felix Ryhmasivutausta', '4', '0', 'felix_ryhmasivutausta', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2462', 'Felix Ryhmasivutaustakuva', '4', '0', 'felix_ryhmasivutaustakuva', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2463', 'Festade Boasvindas', '4', '0', 'festadeboasvindas', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2464', 'FI Linnamaki Background', '4', '0', 'fi_linnanmaki_bg', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2465', 'FI Puffet Background', '4', '0', 'fi_puffet_bg', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2466', 'Fondo Habbo Cibervoluntarios', '4', '0', 'fondo_habbo_cibervoluntarios', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2467', 'FR Spiderwick', '4', '0', 'fr_spiderwick', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2468', 'Global World Background', '4', '0', 'globalw_background', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2469', 'Goth Pattern', '4', '0', 'goth_pattern', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2470', 'Goth Pattern 2', '4', '0', 'goth_patternn', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2471', 'Grandi Habbo Ryhma', '4', '0', 'grandi_habbo_ryhma', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2472', 'Snowbattle Background 2', '4', '0', 'grouppage_snowbattle2', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2473', 'Grunge Background', '4', '0', 'grungewall', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2474', 'Gruposeda Teens', '4', '0', 'gruposedateens', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2475', 'Guidesgroup Background', '4', '0', 'guidesgroup_bg', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2476', 'Habbo Group Tutorial', '4', '0', 'habbo_group_tutorial', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2478', 'Habbo Ryhmatausta', '4', '0', 'habbo_ryhmatausta', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2479', 'Habbo Social Game', '4', '0', 'habbo_social_game_001_opt', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2480', 'Habbo Social Game 2', '4', '0', 'habbo_social_game_002', '2', '1', '129');
INSERT INTO `cms_homes_catalouge` VALUES ('2481', 'Habbo Toolbar Background', '4', '0', 'habbo_toolbar', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2482', 'Habbo Classifieds Background', '4', '0', 'habboclassifieds', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2483', 'Habbo Fest 2008 Background', '4', '0', 'habbofest2008_bg', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2484', 'Habbo Group Background', '4', '0', 'habbogroup', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2485', 'Habboguide Background', '4', '0', 'habboguide', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2486', 'Habborella Sea Background', '4', '0', 'habborella_sea_bg', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2487', 'Habborella Background', '4', '0', 'habborellabg', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2488', 'Habbos Background', '4', '0', 'habbos_group', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2489', 'Habboween Background', '4', '0', 'habboween_bg', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2491', 'Habbox Background', '4', '0', 'habbox', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2493', 'Hanna Montana Background', '4', '0', 'hannamontanawp', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2494', 'HC Machine Background', '4', '0', 'hc_bg_machine', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2495', 'HC Pillow Background', '4', '0', 'hc_bg_pillow', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2496', 'HC Royal Background', '4', '0', 'hc_bg_royal', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2497', 'Dediee', '4', '0', 'hmf_928x1360_dediee', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2498', 'Principale', '4', '0', 'hmf_928x1360_principale', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2499', 'Principale 2', '4', '0', 'hmf_928x1360_principale_b', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2500', 'Hundredmillion Background', '4', '0', 'hundredmillionbg', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2501', 'Hundredmillion Background', '4', '0', 'hundredmillion_bg', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2502', 'Around the World Wallpaper', '4', '0', 'hwaround_the_world_wallpaper', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2503', 'BGUS Staff', '4', '0', 'hwbgus-staff', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2504', 'Easter Egg Wallpaper', '4', '0', 'hweaster_egg_wallpaper', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2505', 'Xmas Gifts Background', '4', '0', 'hwxmas_gifts_bg', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2506', 'Bassplayer Girl', '4', '0', 'hw_bassplayer_girl', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2507', 'Valentine Love Background', '4', '0', 'hw_val_bgpattern_love', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2508', 'Infobus SEM Logo', '4', '0', 'infobus_abrinq_sem_logo', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2509', 'Inked Background', '4', '0', 'inked_bg', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2510', 'Jopelines', '4', '0', 'jopelines', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2511', 'Jordin Parks', '4', '0', 'jordinparks', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2512', 'Japaneese Valentine', '4', '0', 'jpvalentine', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2513', 'Japaneese Background', '4', '0', 'jp_prom_bg', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2514', 'Japaneese Valentine', '4', '0', 'jp_valentine', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2515', 'July Wallpaper', '4', '0', 'july408_wallpaper', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2516', 'Kerrang', '4', '0', 'kerrang2', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2517', 'KFP Background', '4', '0', 'kfp_grouppage_bg', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2518', 'Kingcorps', '4', '0', 'kingcorp928x1360', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2519', 'Kingcorps', '4', '0', 'kingcorp_928x1360', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2520', 'Kir Background', '4', '0', 'kir_grouppage_bg', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2521', 'Kir Stamp', '4', '0', 'kir_stamp4', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2522', 'Kungfu Background', '4', '0', 'kungfu_bg', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2523', 'Madball 2008 Background', '4', '0', 'madball_2008_bg', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2525', 'Makeover Background', '4', '0', 'makeover', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2526', 'Manteli Background', '4', '0', 'manteli_bg', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2527', 'Meet Dave Background', '4', '0', 'meet_dave_groupbg_01', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2528', 'Meet Dave Background 2', '4', '0', 'meet_dave_groupbg_02', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2529', 'Misshabbo Scene', '4', '0', 'misshabbo_scene', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2530', 'Mmoore Wallpaper', '4', '0', 'mmoorewallpaper', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2531', 'MMS', '4', '0', 'mms', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2532', 'Netari Background', '4', '0', 'netaribg', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2534', 'Newyear Fireworks Background', '4', '0', 'newyear_bg_fireworks', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2536', 'Newyear Fireworks Background 2', '4', '0', 'newyear_bg_fireworks2', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2537', 'Green Background', '4', '0', 'nl_green_bg', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2538', 'NS France', '4', '0', 'nsfrance', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2539', 'NS France 2', '4', '0', 'nsfrance2', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2540', 'OB', '4', '0', 'ob1', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2541', 'OB 2', '4', '0', 'ob2', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2542', 'Orca', '4', '0', 'orca', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2543', 'Penelope', '4', '0', 'penelope', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2544', 'Poptarts CV', '4', '0', 'poptarts_cv', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2546', 'Promofthedead Wallpaper', '4', '0', 'promofthedead_wallpaper', '2', '1', '130');
INSERT INTO `cms_homes_catalouge` VALUES ('2547', 'Random Habbos', '4', '0', 'random_habbos', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2548', 'Robojam Wallpaper', '4', '0', 'robojam_wallpaper', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2549', 'Room of the Week Background', '4', '0', 'room_of_the_week_bg', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2550', 'Safetyweek Background', '4', '0', 'safetyweek2008', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2551', 'Safetyweek Background 2', '4', '0', 'safetyweek2008_b', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2552', 'Safetyweek Background 3', '4', '0', 'safetyweek2008_bg', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2553', 'Samsungclouds', '4', '0', 'samsungclouds', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2554', 'Samsunlight', '4', '0', 'samsungnight', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2555', 'Sasquatch Background', '4', '0', 'sasquatch_hhbg3', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2556', 'Scarecrow Background', '4', '0', 'scarecrowbg', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2557', 'Animax Keroro', '4', '0', 'sg_animax_keroro', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2558', 'MTV Background', '4', '0', 'sg_mtv_grouppage_bg_v1', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2559', 'MTV Background 2', '4', '0', 'sg_mtv_grouppage_bg_v2', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2560', 'Shabbo Line', '4', '0', 'shabboline', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2561', 'Silver Surfer', '4', '0', 'silversurfer', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2564', 'Simpleplan Background', '4', '0', 'simpleplan_bg', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2566', 'Slamdunk', '4', '0', 'slamdunk', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2567', 'Snowstorm Background', '4', '0', 'snowstorm_bg', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2568', 'Sofresh Background', '4', '0', 'sofresh_bg', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2569', 'Solid Background Black', '4', '0', 'solid_bg_black', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2570', 'Solid Background White', '4', '0', 'solid_bg_white', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2571', 'Spiderwick Beware', '4', '0', 'spiderwick_beware', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2572', 'Spiderwick Fairy', '4', '0', 'spiderwick_fairy', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2573', 'Spiderwick Goblin', '4', '0', 'spiderwick_goblin', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2574', 'Spiderwick Main', '4', '0', 'spiderwick_main', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2575', 'SPM Background', '4', '0', 'spm_background', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2576', 'SPM Background 2', '4', '0', 'spm_bg_version_041207', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2577', 'Snowstorm Red Costume', '4', '0', 'ss_bootsitjalapaset_red', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2578', 'Snowstorm Blue Costume', '4', '0', 'ss_costume_blue', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2579', 'Raspberry', '4', '0', 'starburstraspberry', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2580', 'sticker_Cape', '4', '0', 'sticker_cape', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2581', 'sticker_Heartbeat ', '4', '0', 'sticker_heartbeat_2', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2582', 'sticker_Masque 4', '4', '0', 'sticker_masque_04', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2583', 'Stray Pixel Background', '4', '0', 'straypixelsbg', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2584', 'Streaming 1', '4', '0', 'streaming001', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2585', 'Streaming 2', '4', '0', 'streaming002', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2586', 'Streaming 3', '4', '0', 'streaming003', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2587', 'Sttrinians Blackboard', '4', '0', 'sttriniansblackboard', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2588', 'Sttrinians Group', '4', '0', 'sttriniansgroup', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2589', 'Summer Background', '4', '0', 'summer_bg', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2590', 'Summer Background Optimal', '4', '0', 'summer_bg_optimal', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2592', 'The Green', '4', '0', 'the_green', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2593', 'Themepark Background 1', '4', '0', 'themepark_bg_01', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2594', 'Themepark Background 2', '4', '0', 'themepark_bg_02', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2595', 'Tiki Firehead Background', '4', '0', 'tiki_firehead_bg', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2596', 'Tokiohotel Wallpaper', '4', '0', 'tokiohotel2', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2597', 'Tokiohotel Scream Wallpaper', '4', '0', 'tokiohotel_scream_wallpaper', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2599', 'Top Gamers Background', '4', '0', 'top_gamers_bg_64', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2600', 'Trax Bling', '4', '0', 'trax_bling', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2602', 'USA Staff Wallpaper', '4', '0', 'usastaff-wall', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2605', 'Valentine Costume 4', '4', '0', 'val_love-costume4', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2606', 'Valentine Costume 3', '4', '0', 'val_lovecostume3', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2607', 'Vale Rose Background', '4', '0', 'vale_rose_bg', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2608', 'Vale Skull Background', '4', '0', 'vale_skull_bg', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2609', 'VIP Group', '4', '0', 'vip_group', '2', '1', '50');
INSERT INTO `cms_homes_catalouge` VALUES ('2611', 'Wallpaper Voiceofteens', '4', '0', 'voiceofteens', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2612', 'Wallepixar Background', '4', '0', 'wallepixar_bg', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2613', 'Wallpaper 1', '4', '0', 'wallpaper1', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2614', 'Wallpaper 2', '4', '0', 'wallpaper2', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2616', 'Wallpaper 3', '4', '0', 'wallpaper3', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2617', 'Wallpaper 4', '4', '0', 'wallpaper4', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2618', 'Wallpaper Bonsai', '4', '0', 'wallpaper_bonsai', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2619', 'Wallpaper Cais', '4', '0', 'wallpaper_cais4', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2620', 'Wallpaper Earthday', '4', '0', 'wallpaper_earthday', '2', '1', '131');
INSERT INTO `cms_homes_catalouge` VALUES ('2621', 'Wallpaper Mshepard', '4', '0', 'wallpaper_mshepard', '2', '1', '132');
INSERT INTO `cms_homes_catalouge` VALUES ('2622', 'Wallpaper Newsiesca', '4', '0', 'wallpaper_newsiesca', '2', '1', '132');
INSERT INTO `cms_homes_catalouge` VALUES ('2623', 'Wallpaper Veronicas', '4', '0', 'wallpaper_veronicas', '2', '1', '132');
INSERT INTO `cms_homes_catalouge` VALUES ('2624', 'Wallpaper Victoriaday', '4', '0', 'wallpaper_victoriaday', '2', '1', '132');
INSERT INTO `cms_homes_catalouge` VALUES ('2625', 'Welcoming Party', '4', '0', 'welcoming_party', '2', '1', '132');
INSERT INTO `cms_homes_catalouge` VALUES ('2626', 'Wrestlemania Nowayout Wallpaper', '4', '0', 'wwe_nowayout_wallpaper', '2', '1', '132');
INSERT INTO `cms_homes_catalouge` VALUES ('2627', 'Wrestlemania Wallpaper', '4', '0', 'wwe_wallpaper', '2', '1', '132');
INSERT INTO `cms_homes_catalouge` VALUES ('2628', 'Wrestlemania Big Battle', '4', '0', 'wwe_wrestlemania_big_battle', '2', '1', '132');
INSERT INTO `cms_homes_catalouge` VALUES ('2629', 'Wrestlemania Bunnymania', '4', '0', 'wwe_wrestlemania_bunnymania', '2', '1', '132');
INSERT INTO `cms_homes_catalouge` VALUES ('2630', 'Wrestlemania Ladder Match', '4', '0', 'wwe_wrestlemania_ladder_match', '2', '1', '132');
INSERT INTO `cms_homes_catalouge` VALUES ('2631', 'Wrestlemania Main', '4', '0', 'wwe_wrestlemania_main', '2', '1', '132');
INSERT INTO `cms_homes_catalouge` VALUES ('2632', 'Wrestlemania Triple Champ', '4', '0', 'wwe_wrestlemania_triple_champ', '2', '1', '132');
INSERT INTO `cms_homes_catalouge` VALUES ('2633', 'Wrestlemania World Champ', '4', '0', 'wwe_wrestlemania_world_champ', '2', '1', '132');
INSERT INTO `cms_homes_catalouge` VALUES ('2634', 'Xmas 2006 Background', '4', '0', 'xmas2006backgrounds', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2635', 'Xmas Background Starsky', '4', '0', 'xmasbgpatternstarsky', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2636', 'Xmas Background Starsky', '4', '0', 'xmas_bgpattern_starsky', '2', '1', '132');
INSERT INTO `cms_homes_catalouge` VALUES ('2637', 'Xmas Box Darkred', '4', '0', 'xmas_box_darkred', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2638', 'Xmas Box Darkred 2', '4', '0', 'xmas_box_darkred2', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2639', 'Xmas Box Darkred 3', '4', '0', 'xmas_box_darkred3', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2640', 'Xmas Box Green', '4', '0', 'xmas_box_green', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2641', 'Xmas Box Lime', '4', '0', 'xmas_box_lime', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2642', 'Xmas Gifts Background', '4', '0', 'xmas_gifts_bg', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2645', 'Xmas Gifts Background 2', '4', '0', 'xmas_gifts_gb', '2', '1', '132');
INSERT INTO `cms_homes_catalouge` VALUES ('2646', 'Xmas HC Ribbon', '4', '0', 'xmas_hc_ribbon', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2647', 'Xmas Reindeer Sticker', '4', '0', 'xmas_jeff_donkey_reindeer_sticker', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2648', 'Xmas Tree Animated', '4', '0', 'xmas_tree01_animated', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2649', 'Yearbook Wallpaper', '4', '0', 'yearbook_wallpaper', '2', '1', '132');
INSERT INTO `cms_homes_catalouge` VALUES ('1', 'Genie Fire Head', '1', '0', 'geniefirehead', '2', '1', '19');
INSERT INTO `cms_homes_catalouge` VALUES ('2781', 'blue_diner_a', '1', '0', 'blue_diner_a', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2782', 'blue_diner_a_umlaut', '1', '0', 'blue_diner_a_umlaut', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2783', 'blue_diner_ae', '1', '0', 'blue_diner_ae', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2784', 'blue_diner_b', '1', '0', 'blue_diner_b', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2785', 'blue_diner_c', '1', '0', 'blue_diner_c', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2786', 'blue_diner_c_cedilla', '1', '0', 'blue_diner_c_cedilla', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2787', 'blue_diner_d', '1', '0', 'blue_diner_d', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2788', 'blue_diner_e', '1', '0', 'blue_diner_e', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2789', 'blue_diner_e_acc', '1', '0', 'blue_diner_e_acc', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2790', 'blue_diner_e_acc_grave', '1', '0', 'blue_diner_e_acc_grave', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2791', 'blue_diner_eight', '1', '0', 'blue_diner_eight', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2792', 'blue_diner_exclamation', '1', '0', 'blue_diner_exclamation', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2793', 'blue_diner_f', '1', '0', 'blue_diner_f', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2794', 'blue_diner_five', '1', '0', 'blue_diner_five', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2795', 'blue_diner_four', '1', '0', 'blue_diner_four', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2796', 'blue_diner_g', '1', '0', 'blue_diner_g', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2797', 'blue_diner_h', '1', '0', 'blue_diner_h', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2798', 'blue_diner_i', '1', '0', 'blue_diner_i', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2799', 'blue_diner_j', '1', '0', 'blue_diner_j', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2800', 'blue_diner_k', '1', '0', 'blue_diner_k', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2801', 'blue_diner_l', '1', '0', 'blue_diner_l', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2802', 'blue_diner_m', '1', '0', 'blue_diner_m', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2803', 'blue_diner_n', '1', '0', 'blue_diner_n', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2804', 'blue_diner_nine', '1', '0', 'blue_diner_nine', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2805', 'blue_diner_o', '1', '0', 'blue_diner_o', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2806', 'blue_diner_o_accute', '1', '0', 'blue_diner_o_accute', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2807', 'blue_diner_o_cc_grave', '1', '0', 'blue_diner_o_cc_grave', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2808', 'blue_diner_o_umlaut', '1', '0', 'blue_diner_o_umlaut', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2809', 'blue_diner_one', '1', '0', 'blue_diner_one', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2810', 'blue_diner_p', '1', '0', 'blue_diner_p', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2811', 'blue_diner_q', '1', '0', 'blue_diner_q', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2812', 'blue_diner_question', '1', '0', 'blue_diner_question', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2813', 'blue_diner_r', '1', '0', 'blue_diner_r', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2814', 'blue_diner_s', '1', '0', 'blue_diner_s', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2815', 'blue_diner_seven', '1', '0', 'blue_diner_seven', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2816', 'blue_diner_six', '1', '0', 'blue_diner_six', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2817', 'blue_diner_t', '1', '0', 'blue_diner_t', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2818', 'blue_diner_three', '1', '0', 'blue_diner_three', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2819', 'blue_diner_two', '1', '0', 'blue_diner_two', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2820', 'blue_diner_u', '1', '0', 'blue_diner_u', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2821', 'blue_diner_u_acc', '1', '0', 'blue_diner_u_acc', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2822', 'blue_diner_u_acc_grave', '1', '0', 'blue_diner_u_acc_grave', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2823', 'blue_diner_u_umlaut', '1', '0', 'blue_diner_u_umlaut', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2824', 'blue_diner_upsidedown', '1', '0', 'blue_diner_upsidedown', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2825', 'blue_diner_v', '1', '0', 'blue_diner_v', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2826', 'blue_diner_w', '1', '0', 'blue_diner_w', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2827', 'blue_diner_x', '1', '0', 'blue_diner_x', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2828', 'blue_diner_y', '1', '0', 'blue_diner_y', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2829', 'blue_diner_z', '1', '0', 'blue_diner_z', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2830', 'blue_diner_zero', '1', '0', 'blue_diner_zero', '2', '1', '242');
INSERT INTO `cms_homes_catalouge` VALUES ('2930', 'diner_belair', '1', '0', 'diner_belair', '2', '1', '243');
INSERT INTO `cms_homes_catalouge` VALUES ('2933', 'diner_gaspump_blue', '1', '0', 'diner_gaspump_blue', '2', '1', '243');
INSERT INTO `cms_homes_catalouge` VALUES ('2934', 'diner_gaspump_green', '1', '0', 'diner_gaspump_green', '2', '1', '243');
INSERT INTO `cms_homes_catalouge` VALUES ('2935', 'diner_gaspump_red', '1', '0', 'diner_gaspump_red', '2', '1', '243');
INSERT INTO `cms_homes_catalouge` VALUES ('2941', 'diner_hotrod', '1', '0', 'diner_hotrod', '2', '1', '243');
INSERT INTO `cms_homes_catalouge` VALUES ('2943', 'diner_plymouth', '1', '0', 'diner_plymouth', '2', '1', '243');
INSERT INTO `cms_homes_catalouge` VALUES ('2944', 'diner_poster', '1', '0', 'diner_poster', '2', '1', '243');
INSERT INTO `cms_homes_catalouge` VALUES ('2945', 'diner_sign', '1', '0', 'diner_sign', '2', '1', '243');
INSERT INTO `cms_homes_catalouge` VALUES ('2947', 'diner_trophy_bronze', '1', '0', 'diner_trophy_bronze', '2', '1', '243');
INSERT INTO `cms_homes_catalouge` VALUES ('2948', 'diner_trophy_gold', '1', '0', 'diner_trophy_gold', '2', '1', '243');
INSERT INTO `cms_homes_catalouge` VALUES ('2949', 'diner_trophy_silver', '1', '0', 'diner_trophy_silver', '2', '1', '243');
INSERT INTO `cms_homes_catalouge` VALUES ('3080', 'green_diner_a', '1', '0', 'green_diner_a', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3081', 'green_diner_a_umlaut', '1', '0', 'green_diner_a_umlaut', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3082', 'green_diner_ae', '1', '0', 'green_diner_ae', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3083', 'green_diner_b', '1', '0', 'green_diner_b', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3084', 'green_diner_c', '1', '0', 'green_diner_c', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3085', 'green_diner_c_cedilla', '1', '0', 'green_diner_c_cedilla', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3086', 'green_diner_d', '1', '0', 'green_diner_d', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3087', 'green_diner_e', '1', '0', 'green_diner_e', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3088', 'green_diner_e_acc', '1', '0', 'green_diner_e_acc', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3089', 'green_diner_e_cc_grave', '1', '0', 'green_diner_e_cc_grave', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3090', 'green_diner_eight', '1', '0', 'green_diner_eight', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3091', 'green_diner_exclamation', '1', '0', 'green_diner_exclamation', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3092', 'green_diner_f', '1', '0', 'green_diner_f', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3093', 'green_diner_five', '1', '0', 'green_diner_five', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3094', 'green_diner_four', '1', '0', 'green_diner_four', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3095', 'green_diner_g', '1', '0', 'green_diner_g', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3096', 'green_diner_h', '1', '0', 'green_diner_h', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3097', 'green_diner_i', '1', '0', 'green_diner_i', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3098', 'green_diner_j', '1', '0', 'green_diner_j', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3099', 'green_diner_k', '1', '0', 'green_diner_k', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3100', 'green_diner_l', '1', '0', 'green_diner_l', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3101', 'green_diner_m', '1', '0', 'green_diner_m', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3102', 'green_diner_n', '1', '0', 'green_diner_n', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3103', 'green_diner_nine', '1', '0', 'green_diner_nine', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3104', 'green_diner_o', '1', '0', 'green_diner_o', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3105', 'green_diner_o_accute', '1', '0', 'green_diner_o_accute', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3106', 'green_diner_o_cc_grave', '1', '0', 'green_diner_o_cc_grave', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3107', 'green_diner_o_umlaut', '1', '0', 'green_diner_o_umlaut', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3108', 'green_diner_one', '1', '0', 'green_diner_one', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3109', 'green_diner_p', '1', '0', 'green_diner_p', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3110', 'green_diner_q', '1', '0', 'green_diner_q', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3111', 'green_diner_question', '1', '0', 'green_diner_question', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3112', 'green_diner_r', '1', '0', 'green_diner_r', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3113', 'green_diner_s', '1', '0', 'green_diner_s', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3114', 'green_diner_seven', '1', '0', 'green_diner_seven', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3115', 'green_diner_six', '1', '0', 'green_diner_six', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3116', 'green_diner_t', '1', '0', 'green_diner_t', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3117', 'green_diner_three', '1', '0', 'green_diner_three', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3118', 'green_diner_two', '1', '0', 'green_diner_two', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3119', 'green_diner_u', '1', '0', 'green_diner_u', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3120', 'green_diner_u_acc', '1', '0', 'green_diner_u_acc', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3121', 'green_diner_u_acc_grave', '1', '0', 'green_diner_u_acc_grave', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3122', 'green_diner_u_umlaut', '1', '0', 'green_diner_u_umlaut', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3123', 'green_diner_upsidedown', '1', '0', 'green_diner_upsidedown', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3124', 'green_diner_v', '1', '0', 'green_diner_v', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3125', 'green_diner_w', '1', '0', 'green_diner_w', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3126', 'green_diner_x', '1', '0', 'green_diner_x', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3127', 'green_diner_y', '1', '0', 'green_diner_y', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('3128', 'green_diner_z', '1', '0', 'green_diner_z', '2', '1', '244');
INSERT INTO `cms_homes_catalouge` VALUES ('2911', 'darkknight_batman_suit', '1', '0', 'darkknight_batman_suit', '2', '1', '245');
INSERT INTO `cms_homes_catalouge` VALUES ('2912', 'darkknight_clownface', '1', '0', 'darkknight_clownface', '2', '1', '245');
INSERT INTO `cms_homes_catalouge` VALUES ('2913', 'darkknight_dentbutton', '1', '0', 'darkknight_dentbutton', '2', '1', '245');
INSERT INTO `cms_homes_catalouge` VALUES ('2914', 'darkknight_jokercard', '1', '0', 'darkknight_jokercard', '2', '1', '245');
INSERT INTO `cms_homes_catalouge` VALUES ('2915', 'darkknight_jokerface', '1', '0', 'darkknight_jokerface', '2', '1', '245');
INSERT INTO `cms_homes_catalouge` VALUES ('2916', 'darkknight_logo', '1', '0', 'darkknight_logo', '2', '1', '245');
INSERT INTO `cms_homes_catalouge` VALUES ('4324', 'darkknight_wallpaper', '4', '0', 'darkknight_wallpaper', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('3530', 'red_diner_a', '1', '0', 'red_diner_a', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3531', 'red_diner_a_umlaut', '1', '0', 'red_diner_a_umlaut', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3532', 'red_diner_ae', '1', '0', 'red_diner_ae', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3533', 'red_diner_b', '1', '0', 'red_diner_b', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3534', 'red_diner_c', '1', '0', 'red_diner_c', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3535', 'red_diner_c_cedilla', '1', '0', 'red_diner_c_cedilla', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3536', 'red_diner_d', '1', '0', 'red_diner_d', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3537', 'red_diner_e', '1', '0', 'red_diner_e', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3538', 'red_diner_e_acc', '1', '0', 'red_diner_e_acc', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3539', 'red_diner_e_cc_grave', '1', '0', 'red_diner_e_cc_grave', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3540', 'red_diner_eight', '1', '0', 'red_diner_eight', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3541', 'red_diner_exclamation', '1', '0', 'red_diner_exclamation', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3542', 'red_diner_f', '1', '0', 'red_diner_f', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3543', 'red_diner_five', '1', '0', 'red_diner_five', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3544', 'red_diner_four', '1', '0', 'red_diner_four', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3545', 'red_diner_g', '1', '0', 'red_diner_g', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3546', 'red_diner_h', '1', '0', 'red_diner_h', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3547', 'red_diner_i', '1', '0', 'red_diner_i', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3548', 'red_diner_j', '1', '0', 'red_diner_j', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3549', 'red_diner_k', '1', '0', 'red_diner_k', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3550', 'red_diner_l', '1', '0', 'red_diner_l', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3551', 'red_diner_m', '1', '0', 'red_diner_m', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3552', 'red_diner_n', '1', '0', 'red_diner_n', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3553', 'red_diner_nine', '1', '0', 'red_diner_nine', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3554', 'red_diner_o', '1', '0', 'red_diner_o', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3555', 'red_diner_o_accute', '1', '0', 'red_diner_o_accute', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3556', 'red_diner_o_cc_grave', '1', '0', 'red_diner_o_cc_grave', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3557', 'red_diner_o_umlaut', '1', '0', 'red_diner_o_umlaut', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3558', 'red_diner_one', '1', '0', 'red_diner_one', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3559', 'red_diner_p', '1', '0', 'red_diner_p', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3560', 'red_diner_q', '1', '0', 'red_diner_q', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3561', 'red_diner_question', '1', '0', 'red_diner_question', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3562', 'red_diner_r', '1', '0', 'red_diner_r', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3563', 'red_diner_s', '1', '0', 'red_diner_s', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3564', 'red_diner_seven', '1', '0', 'red_diner_seven', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3565', 'red_diner_six', '1', '0', 'red_diner_six', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3566', 'red_diner_t', '1', '0', 'red_diner_t', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3567', 'red_diner_three', '1', '0', 'red_diner_three', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3568', 'red_diner_two', '1', '0', 'red_diner_two', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3569', 'red_diner_u', '1', '0', 'red_diner_u', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3570', 'red_diner_u_acc', '1', '0', 'red_diner_u_acc', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3571', 'red_diner_u_acc_grave', '1', '0', 'red_diner_u_acc_grave', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3572', 'red_diner_u_umlaut', '1', '0', 'red_diner_u_umlaut', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3573', 'red_diner_upsidedown', '1', '0', 'red_diner_upsidedown', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3574', 'red_diner_v', '1', '0', 'red_diner_v', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3575', 'red_diner_w', '1', '0', 'red_diner_w', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3576', 'red_diner_x', '1', '0', 'red_diner_x', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3577', 'red_diner_y', '1', '0', 'red_diner_y', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3578', 'red_diner_z', '1', '0', 'red_diner_z', '2', '1', '246');
INSERT INTO `cms_homes_catalouge` VALUES ('3464', 'olym_carson', '1', '0', 'olym_carson', '2', '1', '247');
INSERT INTO `cms_homes_catalouge` VALUES ('3465', 'olym_cresthawk', '1', '0', 'olym_cresthawk', '2', '1', '247');
INSERT INTO `cms_homes_catalouge` VALUES ('3466', 'olym_inari', '1', '0', 'olym_inari', '2', '1', '247');
INSERT INTO `cms_homes_catalouge` VALUES ('3467', 'olym_jandelee', '1', '0', 'olym_jandelee', '2', '1', '247');
INSERT INTO `cms_homes_catalouge` VALUES ('3468', 'olym_lady', '1', '0', 'olym_lady', '2', '1', '247');
INSERT INTO `cms_homes_catalouge` VALUES ('3469', 'olym_loderse', '1', '0', 'olym_loderse', '2', '1', '247');
INSERT INTO `cms_homes_catalouge` VALUES ('3470', 'olym_moiraine', '1', '0', 'olym_moiraine', '2', '1', '247');
INSERT INTO `cms_homes_catalouge` VALUES ('3471', 'olym_nme', '1', '0', 'olym_nme', '2', '1', '247');
INSERT INTO `cms_homes_catalouge` VALUES ('3472', 'olym_smoothcriminal', '1', '0', 'olym_smoothcriminal', '2', '1', '247');
INSERT INTO `cms_homes_catalouge` VALUES ('3473', 'olym_spartan', '1', '0', 'olym_spartan', '2', '1', '247');
INSERT INTO `cms_homes_catalouge` VALUES ('3474', 'olym_squib', '1', '0', 'olym_squib', '2', '1', '247');
INSERT INTO `cms_homes_catalouge` VALUES ('3475', 'olym_thegirls', '1', '0', 'olym_thegirls', '2', '1', '247');
INSERT INTO `cms_homes_catalouge` VALUES ('2655', '25_146x146_habbo_sticker_fi', '1', '0', '25_146x146_habbo_sticker_fi', '2', '1', '214');
INSERT INTO `cms_homes_catalouge` VALUES ('2713', 'backtoschool_badapple', '1', '0', 'backtoschool_badapple', '2', '1', '230');
INSERT INTO `cms_homes_catalouge` VALUES ('2714', 'backtoschool_goldapple', '1', '0', 'backtoschool_goldapple', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2715', 'backtoschool_trophy', '1', '0', 'backtoschool_trophy', '2', '1', '230');
INSERT INTO `cms_homes_catalouge` VALUES ('2740', 'bg_stickie', '4', '0', 'bg_stickie', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2868', 'cat_animated', '1', '0', 'cat_animated', '2', '1', '1000');
INSERT INTO `cms_homes_catalouge` VALUES ('2873', 'checker_border_h', '1', '0', 'checker_border_h', '2', '1', '243');
INSERT INTO `cms_homes_catalouge` VALUES ('2874', 'checker_border_v', '1', '0', 'checker_border_v', '2', '1', '243');
INSERT INTO `cms_homes_catalouge` VALUES ('3148', 'habbo_x_home_sticker', '1', '0', 'habbo_x_home_sticker', '2', '1', '230');
INSERT INTO `cms_homes_catalouge` VALUES ('3180', 'hw_bassplayer_girl2', '1', '0', 'hw_bassplayer_girl2', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('3210', 'hw_keyboards1', '1', '0', 'hw_keyboards1', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('3211', 'hw_keyboards2', '1', '0', 'hw_keyboards2', '2', '1', '221');
INSERT INTO `cms_homes_catalouge` VALUES ('3512', 'pretzel', '1', '0', 'pretzel', '2', '1', '216');
INSERT INTO `cms_homes_catalouge` VALUES ('3927', 'teensbear', '1', '0', 'teensbear', '2', '1', '235');
INSERT INTO `cms_homes_catalouge` VALUES ('3945', 'tiki_volcano', '1', '0', 'tiki_volcano', '2', '1', '207');
INSERT INTO `cms_homes_catalouge` VALUES ('4160', '10kbc_habbo_grouppage_bg_2b', '4', '0', '10kbc_habbo_grouppage_bg_2b', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4161', '10kbc_habbo_grouppage_bg_3b', '4', '0', '10kbc_habbo_grouppage_bg_3b', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4162', '26_habbo_background_fi', '4', '0', '26_habbo_background_fi', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4182', 'au_iflyvampwasp_bg_v1', '4', '0', 'au_iflyvampwasp_bg_v1', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4183', 'au_iflyvampwasp_bg_v2', '4', '0', 'au_iflyvampwasp_bg_v2', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4186', 'au_spiderwick_bg', '4', '0', 'au_spiderwick_bg', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4187', 'background_momie3_928x1360', '4', '0', 'background_momie3_928x1360', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4189', 'backtoschool_wallpaper', '4', '0', 'backtoschool_wallpaper', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4194', 'bg_alhambra', '4', '0', 'bg_alhambra', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4195', 'bg_animegroup', '4', '0', 'bg_animegroup', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4251', 'bg_moviesgroup', '4', '0', 'bg_moviesgroup', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4252', 'bg_musicgroup', '4', '0', 'bg_musicgroup', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4287', 'bg_solid_bg_white', '4', '0', 'bg_solid_bg_white', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4296', 'bg_videogamesgroup', '4', '0', 'bg_videogamesgroup', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4306', 'bmx_tailwhip', '4', '0', 'bmx_tailwhip', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4309', 'camocheese_wallpaper', '4', '0', 'camocheese_wallpaper', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4319', 'cw_group_1', '4', '0', 'cw_group_1', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4321', 'cw_group_2', '4', '0', 'cw_group_2', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4323', 'cw_poster', '4', '0', 'cw_poster', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4328', 'diner_global', '4', '0', 'diner_global', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4337', 'easter_eggs_wallpaper', '4', '0', 'easter_eggs_wallpaper', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4338', 'easter_eggs_wallpaper2', '4', '0', 'easter_eggs_wallpaper2', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4361', 'geeks_wallpaper', '4', '0', 'geeks_wallpaper', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4366', 'greasers_wallpaper', '4', '0', 'greasers_wallpaper', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4369', 'gruposedateens.jpg', '4', '0', 'gruposedateens.jpg', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4371', 'guitarhero', '4', '0', 'guitarhero', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4372', 'gyroscope_grouppage_bg_v1', '4', '0', 'gyroscope_grouppage_bg_v1', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4374', 'habbo_bmx_foot_jam_tailwhip_bg', '4', '0', 'habbo_bmx_foot_jam_tailwhip_bg', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4376', 'habbo_group_tutorial_bg', '4', '0', 'habbo_group_tutorial_bg', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4385', 'habbolympics_bg_final', '4', '0', 'habbolympics_bg_final', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4390', 'habboween_bg_bg', '4', '0', 'habboween_bg_bg', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4392', 'hannah_montana_background', '4', '0', 'hannah_montana_background', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4397', 'head_guides_germany', '4', '0', 'head_guides_germany', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4398', 'head_guides_switserland', '4', '0', 'head_guides_switserland', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4412', 'jocks_wallpaper', '4', '0', 'jocks_wallpaper', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4419', 'kerli_wallpaper', '4', '0', 'kerli_wallpaper', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4427', 'landing_page_comp', '4', '0', 'landing_page_comp', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4429', 'madball_2008_bg_001', '4', '0', 'madball_2008_bg_001', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4430', 'madball_wallpaper', '4', '0', 'madball_wallpaper', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4438', 'myspy_group', '4', '0', 'myspy_group', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4444', 'nicolajrasted_vip', '4', '0', 'nicolajrasted_vip', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4453', 'poptarts_cv_002.jpg', '4', '0', 'poptarts_cv_002.jpg', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4456', 'rexona_bg', '4', '0', 'rexona_bg', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4471', 'silversurfer2', '4', '0', 'silversurfer2', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4472', 'silversurfer3', '4', '0', 'silversurfer3', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4475', 'skateollie_bg', '4', '0', 'skateollie_bg', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4476', 'skateollie_bg2', '4', '0', 'skateollie_bg2', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4477', 'skulls_wallpaper', '4', '0', 'skulls_wallpaper', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4505', 'test', '4', '0', 'test', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4512', 'toolbar_bg', '4', '0', 'toolbar_bg', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4513', 'toolbar_bg2', '4', '0', 'toolbar_bg2', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4514', 'top_gamers_bg', '4', '0', 'top_gamers_bg', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4517', 'tutorial_bg', '4', '0', 'tutorial_bg', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4519', 'val_bgpattern_love', '4', '0', 'val_bgpattern_love', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4520', 'val_bgpattern_skull', '4', '0', 'val_bgpattern_skull', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4526', 'vip_group.png', '4', '0', 'vip_group.png', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4529', 'wallpaper_alkalinetrio', '4', '0', 'wallpaper_alkalinetrio', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4532', 'wallpaper_dinerduck', '4', '0', 'wallpaper_dinerduck', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4533', 'wallpaper_dinergeeks', '4', '0', 'wallpaper_dinergeeks', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4534', 'wallpaper_dinergreasers', '4', '0', 'wallpaper_dinergreasers', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4535', 'wallpaper_dinerjocks', '4', '0', 'wallpaper_dinerjocks', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4536', 'wallpaper_dinermapusa', '4', '0', 'wallpaper_dinermapusa', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4538', 'wallpaper_dinerus', '4', '0', 'wallpaper_dinerus', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4539', 'wallpaper_droney', '4', '0', 'wallpaper_droney', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4541', 'wallpaper_katiestill', '4', '0', 'wallpaper_katiestill', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4542', 'wallpaper_lauraduncan', '4', '0', 'wallpaper_lauraduncan', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4543', 'wallpaper_lenka', '4', '0', 'wallpaper_lenka', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4544', 'wallpaper_monet', '4', '0', 'wallpaper_monet', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4547', 'wallpaper_sprousebrothers', '4', '0', 'wallpaper_sprousebrothers', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4548', 'wallpaper_submarines', '4', '0', 'wallpaper_submarines', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4549', 'wallpaper_tmobile', '4', '0', 'wallpaper_tmobile', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4554', 'wallpaper2_001', '4', '0', 'wallpaper2_001', '2', '1', '248');
INSERT INTO `cms_homes_catalouge` VALUES ('4574', 'xmas_gifts_bg2', '4', '0', 'xmas_gifts_bg2', '2', '1', '248');");
		mysql_query("ALTER TABLE `rooms` CHANGE `name` `name` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL");
		mysql_query("ALTER TABLE `users` ADD `visibility` INT( 1 ) NOT NULL DEFAULT '1' AFTER `lastgift` ;");
		mysql_query("ALTER TABLE `users` ADD `screen` VARCHAR( 4 ) NULL DEFAULT 'wide' AFTER `online` ;");
		mysql_query("ALTER TABLE `users` ADD `rea` VARCHAR( 8 ) NULL DEFAULT 'enabled' AFTER `screen` ;");
		mysql_query("ALTER TABLE `cms_homes_stickers` ADD `var` INT( 100 ) NULL AFTER `groupid` ;");

		echo "<strong>done!</strong></p>\n";
		echo "<p>The tables have been created, truncated and modified where needed, and required data has been inserted. Your site is technically ready for use now, but let's take a moment to set up an administrator account for you first.</p>";
		echo "<p><a href=\"install.php?step=3\" class=\"button\">Proceed</a>\n";

///////////////////////////////////////////////////////////////////////////////////////////////

} elseif($step == 3){

	// We've written the config, so let's use that
	require_once('config.php');
	require_once('./includes/mysql.php');

	if(isset($_POST['username'])){
		$name = $_POST['username'];
		$password = $_POST['password'];
		$password2 = $_POST['password-retyped'];
		$email = $_POST['email'];

		$filter = preg_replace("/[^a-z\d]/i", "", $name);
		$email_check = preg_match("/^[a-z0-9_\.-]+@([a-z0-9]+([\-]+[a-z0-9]+)*\.)+[a-z]{2,7}$/i", $email);

		if($password !== $password2){
			$fail = true;
			$error = "The passwords do not match.";
		} elseif($email_check !== 1){
			$fail = true;
			$error = "Invalid e-mail address.";
		} elseif($filter !== $name){
			$fail = true;
			$error = "Invalid username.";
		} else {
			$sql = mysql_query("SELECT id FROM users WHERE name = '".addslashes($name)."' LIMIT 1") or die(mysql_error());
			$already_exists = mysql_num_rows($sql);
			if($already_exists > 0){
				$fail = true;
				$error = "Username already in use!";
			} else {
				$password = sha1($password.strtolower($name));
				$scredits = 5000;
				$dob = "1-1-1980";
				$figure = "hr-802-61.hd-190-1.ch-260-62.lg-280-110.sh-295-91.fa-1207-103";
				$gender = "M";

				mysql_query("INSERT INTO users (name,password,email,birth,figure,sex,rank,hbirth,ipaddress_last,postcount,tickets,credits,lastvisit) VALUES ('".$name."','".$password."','".$email."','".$dob."','".$figure."','".$gender."','7','".$date_normal."','".$remote_ip."','0','0','".$scredits."','".$date_full."')") or die(mysql_error());

				$check = mysql_query("SELECT id FROM users WHERE name = '".addslashes($name)."' ORDER BY id ASC LIMIT 1") or die(mysql_error());
				$row = mysql_fetch_assoc($check);
				$userid = $row['id'];

				$fail = false;
			}
		}
	} elseif(isset($_GET['do']) && $_GET['do'] == "skip"){
		$fail = false;
	}

	if(!isset($_POST['username'])){ $_POST['username'] = "admin"; }
	if(!isset($_POST['email'])){ $_POST['email'] = "defaultuser@meth0d.org"; }

	if(!isset($fail) || $fail == true || isset($error)){
		if(isset($error)){ echo "<p><strong>".$error."</strong></p>\n"; }
		echo "<p>Alright, we've finished the installation. Now let's take a moment to set up an administrator account for you. Fill in the form below, and an administrator account will automaticly be created for you.</p>";
		echo "<p><a href='install.php?step=3&do=skip'>Skip this step</a></p>";
		echo "<form method='post' action='install.php?step=3&do=submit'>";
		echo "	<table class=\"form-table\">
		<tr>
			<th scope=\"row\">Username</th>
			<td><input name=\"username\" type=\"text\" size=\"25\" value=\"".$_POST['username']."\" /></td>
			<td>The username you will use to log in.</td>
		</tr>
		<tr>
			<th scope=\"row\">Password</th>
			<td><input name=\"password\" type=\"password\" size=\"25\" value=\"\" /></td>
			<td>The password you will use to log in with your username.</td>
		</tr>
		<tr>
			<th scope=\"row\">Confirm Password</th>
			<td><input name=\"password-retyped\" type=\"password\" size=\"25\" value=\"\" /></td>
			<td>Please re-type your password here.</td>
		</tr>
		<tr>
			<th scope=\"row\">E-Mail Address</th>
			<td><input name=\"email\" type=\"text\" size=\"25\" value=\"".$_POST['email']."\" /></td>
			<td>Your e-mail address.</td>
		</tr>
	</table>
	<h2 class=\"step\">
	<input name=\"submit\" type=\"submit\" value=\"Submit\" class=\"button\" />
	</h2>";
		echo "</form>";
	} elseif($fail == false){
		echo "<p><h1>Installation Complete</h1></p>";
		echo "<p>HoloCMS was installed successfully, and an admin user was created! You can now proceed to <a href='index.php'>your new site</a> or the <a href='./housekeeping/index.php?p=login&x=installer'>Admin CP (Housekeeping)</a>.</p>";
		echo "<p><strong>Remember to delete install.php and upgrade.php, or you will not be able to use your site.</strong></p>";
		exit;
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////

echo "\n</body>
</html>";

?>