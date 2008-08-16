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
|| # Copyright © 2008 Meth0d. All rights reserved.
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
"."$"."enable_status_image = \"0\";

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
  `userid` int(10) default '0',
  `message` varchar(1000) default NULL,
  `time` varchar(1000) default NULL,
  `type` varchar(10) default 'home',
  `type_id` int(10) default NULL,
  `groupid` int(10) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;");
		
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
		
		echo "Building homes catalouge (303 records)..";
		mysql_query("INSERT INTO `cms_homes_catalouge` (`id`, `name`, `type`, `subtype`, `data`, `price`, `amount`, `category`) VALUES
(1, 'Genie Fire Head', '1', '0', 'geniefirehead', 2, 1, '19'),
(2, 'Trax SFX', '1', '0', 'trax_sfx', 1, 1, '3'),
(3, 'Trax Disco', '1', '0', 'trax_disco', 1, 1, '3'),
(4, 'Trax 8 Bit', '1', '0', 'trax_8_bit', 1, 1, '3'),
(5, 'Trax Electro', '1', '0', 'trax_electro', 1, 1, '3'),
(6, 'Trax Reggae', '1', '0', 'trax_reggae', 1, 1, '3'),
(7, 'Trax Ambient', '1', '0', 'trax_ambient', 1, 1, '3'),
(8, 'Trax Bling', '1', '0', 'trax_bling', 1, 1, '3'),
(9, 'Trax Heavy', '1', '0', 'trax_heavy', 1, 1, '3'),
(10, 'Trax Latin', '1', '0', 'trax_latin', 1, 1, '3'),
(11, 'Trax Rock', '1', '0', 'trax_rock', 1, 1, '3'),
(12, 'Animated Falling Rain', '4', '0', 'bg_rain', 3, 1, '27'),
(13, 'Notes', '3', '0', 'stickienote', 2, 5, '29'),
(14, 'Animated Blue Serpentine', '4', '0', 'bg_serpentine_darkblue', 3, 1, '27'),
(15, 'Animated Red Serpentine', '4', '0', 'bg_serpntine_darkred', 3, 1, '27'),
(16, 'Animated Brown Serpentine', '4', '0', 'bg_serpentine_1', 3, 1, '27'),
(17, 'Animated Pink Serpentine', '4', '0', 'bg_serpentine_2', 3, 1, '27'),
(18, 'Denim', '4', '0', 'bg_denim', 3, 1, '27'),
(19, 'Lace', '4', '0', 'bg_lace', 3, 1, '27'),
(20, 'Stiched', '4', '0', 'bg_stitched', 3, 1, '27'),
(21, 'Wood', '4', '0', 'bg_wood', 3, 1, '27'),
(22, 'Cork', '4', '0', 'bg_cork', 3, 1, '27'),
(23, 'Stone', '4', '0', 'bg_stone', 3, 1, '27'),
(24, 'Bricks', '4', '0', 'bg_pattern_bricks', 3, 1, '27'),
(25, 'Ruled Paper', '4', '0', 'bg_ruled_paper', 3, 1, '27'),
(26, 'Grass', '4', '0', 'bg_grass', 3, 1, '27'),
(27, 'Hotel', '4', '0', 'bg_hotel', 3, 1, '27'),
(28, 'Bubble', '4', '0', 'bg_bubble', 3, 1, '27'),
(29, 'Bobba Skulls', '4', '0', 'bg_pattern_bobbaskulls1', 3, 1, '27'),
(30, 'Deep Space', '4', '0', 'bg_pattern_space', 3, 1, '27'),
(31, 'Submarine', '4', '0', 'bg_image_submarine', 3, 1, '27'),
(32, 'Metal II', '4', '0', 'bg_metal2', 3, 1, '27'),
(33, 'Broken Glass', '4', '0', 'bg_broken_glass', 3, 1, '27'),
(34, 'Clouds', '4', '0', 'bg_pattern_clouds', 3, 1, '27'),
(35, 'Comic', '4', '0', 'bg_comic2', 3, 1, '27'),
(36, 'Floral 1', '4', '0', 'bg_pattern_floral_01', 3, 1, '27'),
(37, 'A', '1', '0', 'a', 1, 1, '5'),
(38, 'B', '1', '0', 'b_2', 1, 1, '5'),
(39, 'C', '1', '0', 'c', 1, 1, '5'),
(40, 'D', '1', '0', 'd', 1, 1, '5'),
(41, 'E', '1', '0', 'e', 1, 1, '5'),
(42, 'F', '1', '0', 'f', 1, 1, '5'),
(43, 'G', '1', '0', 'g', 1, 1, '5'),
(44, 'H', '1', '0', 'h', 1, 1, '5'),
(45, 'I', '1', '0', 'i', 1, 1, '5'),
(46, 'J', '1', '0', 'j', 1, 1, '5'),
(47, 'K', '1', '0', 'k', 1, 1, '5'),
(48, 'L', '1', '0', 'l', 1, 1, '5'),
(49, 'M', '1', '0', 'm', 1, 1, '5'),
(50, 'N', '1', '0', 'n', 1, 1, '5'),
(51, 'O', '1', '0', 'o', 1, 1, '5'),
(52, 'P', '1', '0', 'p', 1, 1, '5'),
(53, 'Q', '1', '0', 'q', 1, 1, '5'),
(54, 'R', '1', '0', 'r', 1, 1, '5'),
(55, 'S', '1', '0', 's', 1, 1, '5'),
(56, 'T', '1', '0', 't', 1, 1, '5'),
(57, 'U', '1', '0', 'u', 1, 1, '5'),
(58, 'V', '1', '0', 'v', 1, 1, '5'),
(59, 'W', '1', '0', 'w', 1, 1, '5'),
(60, 'X', '1', '0', 'x', 1, 1, '5'),
(61, 'Y', '1', '0', 'y', 1, 1, '5'),
(62, 'Z', '1', '0', 'z', 1, 1, '5'),
(63, 'Bling Star', '1', '0', 'bling_star', 1, 1, '6'),
(64, 'Bling A', '1', '0', 'bling_a', 1, 1, '6'),
(65, 'Bling B', '1', '0', 'bling_b', 1, 1, '6'),
(66, 'Bling C', '1', '0', 'bling_c', 1, 1, '6'),
(67, 'Bling D', '1', '0', 'bling_d', 1, 1, '6'),
(68, 'Bling E', '1', '0', 'bling_e', 1, 1, '6'),
(69, 'Bling F', '1', '0', 'bling_f', 1, 1, '6'),
(70, 'Bling G', '1', '0', 'bling_g', 1, 1, '6'),
(71, 'Bling H', '1', '0', 'bling_h', 1, 1, '6'),
(72, 'Bling I', '1', '0', 'bling_i', 1, 1, '6'),
(73, 'Bling J', '1', '0', 'bling_j', 1, 1, '6'),
(74, 'Bling K', '1', '0', 'bling_k', 1, 1, '6'),
(75, 'Bling L', '1', '0', 'bling_l', 1, 1, '6'),
(76, 'Bling M', '1', '0', 'bling_m', 1, 1, '6'),
(77, 'Bling N', '1', '0', 'bling_n', 1, 1, '6'),
(78, 'Bling O', '1', '0', 'bling_o', 1, 1, '6'),
(79, 'Bling P', '1', '0', 'bling_p', 1, 1, '6'),
(80, 'Bling Q', '1', '0', 'bling_q', 1, 1, '6'),
(81, 'Bling R', '1', '0', 'bling_r', 1, 1, '6'),
(82, 'Bling S', '1', '0', 'bling_s', 1, 1, '6'),
(83, 'Bling T', '1', '0', 'bling_t', 1, 1, '6'),
(84, 'Bling U', '1', '0', 'bling_u', 1, 1, '6'),
(85, 'Bling V', '1', '0', 'bling_v', 1, 1, '6'),
(86, 'Bling W', '1', '0', 'bling_w', 1, 1, '6'),
(87, 'Bling X', '1', '0', 'bling_x', 1, 1, '6'),
(88, 'Bling Y', '1', '0', 'bling_y', 1, 1, '6'),
(89, 'Bling Z', '1', '0', 'bling_z', 1, 1, '6'),
(90, 'Bling Underscore', '1', '0', 'bling_underscore', 1, 1, '6'),
(91, 'Bling Comma', '1', '0', 'bling_comma', 1, 1, '6'),
(92, 'Bling Dot', '1', '0', 'bling_dot', 1, 1, '6'),
(93, 'Bling Exclamation', '1', '0', 'bling_exclamation', 1, 1, '6'),
(94, 'Bling Question', '1', '0', 'bling_question', 1, 1, '6'),
(95, 'European Letter 3', '1', '0', 'a_with_circle', 1, 1, '5'),
(96, 'European Letter 1', '1', '0', 'a_with_dots', 1, 1, '5'),
(97, 'European Letter 2', '1', '0', 'o_with_dots', 1, 1, '5'),
(98, 'Dot', '1', '0', 'dot', 1, 1, '5'),
(99, 'Acsent 1', '1', '0', 'acsent1', 1, 1, '5'),
(100, 'Acsent 2', '1', '0', 'acsent2', 1, 1, '5'),
(101, 'Underscore', '1', '0', 'underscore', 1, 1, '5'),
(102, 'Holograph Emulator', '1', '0', 'sticker_holograph', 5, 1, '19'),
(103, 'Chain 1', '1', '0', 'chain_horizontal', 1, 1, '7'),
(104, 'Chain 2', '1', '0', 'chain_vertical', 1, 1, '7'),
(105, 'Ruler 1', '1', '0', 'ruler_horizontal', 1, 1, '7'),
(106, 'Ruler 2', '1', '0', 'ruler_vertical', 1, 1, '7'),
(107, 'Vine 1', '1', '0', 'vine', 1, 1, '7'),
(108, 'Vine 2', '1', '0', 'vine2', 1, 1, '7'),
(109, 'Leaves 1', '1', '0', 'leafs1', 1, 1, '7'),
(110, 'Leaves 2', '1', '0', 'leafs2', 1, 1, '7'),
(111, 'Zipper', '1', '0', 'sticker_zipper_v_tile', 1, 1, '7'),
(112, 'Zipper', '1', '0', 'sticker_zipper_h_tile', 1, 1, '7'),
(113, 'Zipper', '1', '0', 'sticker_zipper_h_normal_lock', 1, 1, '7'),
(114, 'Zipper', '1', '0', 'sticker_zipper_h_bobba_lock', 1, 1, '7'),
(115, 'Zipper', '1', '0', 'sticker_zipper_h_end', 1, 1, '7'),
(116, 'Zipper', '1', '0', 'sticker_zipper_v_end', 1, 1, '7'),
(117, 'Zipper', '1', '0', 'sticker_zipper_v_bobba_lock', 1, 1, '7'),
(118, 'Zipper', '1', '0', 'sticker_zipper_v_normal_lock', 1, 1, '7'),
(119, 'Wormhand', '1', '0', 'wormhand', 5, 1, '8'),
(120, 'Gentleman', '1', '0', 'sticker_gentleman', 2, 1, '8'),
(121, 'Chewed Bubblegum', '1', '0', 'chewed_bubblegum', 1, 1, '8'),
(122, 'Cactus', '1', '0', 'sticker_cactus_anim', 2, 1, '8'),
(123, 'Spaceduck', '1', '0', 'sticker_spaceduck', 1, 1, '8'),
(124, 'Moonpig', '1', '0', 'sticker_moonpig', 2, 1, '8'),
(125, 'Swimming fish', '1', '0', 'swimming_fish', 2, 1, '8'),
(126, 'Boxer', '1', '0', 'sticker_boxer', 2, 1, '8'),
(127, 'Wunder Frank', '1', '0', 'wunderfrank', 1, 1, '8'),
(128, 'Submarine', '1', '0', 'sticker_submarine', 2, 1, '8'),
(129, 'Clown', '1', '0', 'sticker_clown_anim', 2, 1, '8'),
(130, 'Stars', '1', '0', 'blingblingstars', 2, 1, '9'),
(131, 'Hearts', '1', '0', 'blinghearts', 2, 1, '9'),
(132, 'Heartbeat', '1', '0', 'sticker_heartbeat', 2, 1, '9'),
(133, 'Cat in a box', '1', '0', 'sticker_catinabox', 2, 1, '9'),
(134, 'Bear', '1', '0', 'bear', 2, 1, '9'),
(135, 'Vampire', '1', '0', 'gothic_draculacape ', 3, 1, '10'),
(136, 'Giant Evil Bunny', '1', '0', 'evil_giant_bunny', 2, 1, '10'),
(137, 'Zombie Bunny!', '1', '0', 'zombiepupu', 2, 1, '10'),
(138, 'Skelly', '1', '0', 'skeletor_001', 2, 1, '10'),
(139, 'Skull 1', '1', '0', 'skull', 2, 1, '10'),
(140, 'Skull 2', '1', '0', 'skull2', 2, 1, '10'),
(141, 'Scuba capsule', '1', '0', 'scubacapsule_anim', 2, 1, '10'),
(142, 'Bobbaskull', '1', '0', 'sticker_bobbaskull', 2, 1, '10'),
(143, 'Pack 1', '1', '0', 'sticker_flower1', 3, 5, '11'),
(144, 'Pack 2', '1', '0', 'icecube_big', 3, 10, '11'),
(145, 'Pack 3', '1', '0', 'leafs2', 5, 7, '11'),
(146, 'Pack 4', '1', '0', 'vine2', 3, 5, '11'),
(147, 'Pack 5', '1', '0', 'chain_horizontal', 3, 5, '11'),
(148, 'Pack 6', '1', '0', 'icecube_small', 3, 10, '11'),
(149, 'Arrow up', '1', '0', 'sticker_arrow_up', 2, 1, '12'),
(150, 'Arrow down', '1', '0', 'sticker_arrow_down', 2, 1, '12'),
(151, 'Arrow left', '1', '0', 'sticker_arrow_left', 2, 1, '12'),
(152, 'Arrow right', '1', '0', 'sticker_arrow_right', 2, 1, '12'),
(153, 'Ponting hand right', '1', '0', 'sticker_pointing_hand_1', 2, 1, '12'),
(154, 'Ponting hand up', '1', '0', 'sticker_pointing_hand_2', 2, 1, '12'),
(155, 'Ponting hand down', '1', '0', 'sticker_pointing_hand_3', 2, 1, '12'),
(156, 'Pointing hand left', '1', '0', 'sticker_pointing_hand_4', 2, 1, '12'),
(157, 'Nail 1', '1', '0', 'nail2', 2, 1, '12'),
(158, 'Nail 2', '1', '0', 'nail3', 2, 1, '12'),
(159, 'Red pin', '1', '0', 'needle_1', 1, 1, '12'),
(160, 'Green pin', '1', '0', 'needle_2', 1, 1, '12'),
(161, 'Blue pin', '1', '0', 'needle_3', 1, 1, '12'),
(162, 'Purple pin', '1', '0', 'needle_4', 1, 1, '12'),
(163, 'Yellow pin', '1', '0', 'needle_5', 1, 1, '12'),
(164, 'Grass Meadow', '1', '0', 'grass', 2, 1, '13'),
(165, 'Flower', '1', '0', 'sticker_flower1', 1, 1, '13'),
(166, 'Yellow Flower', '1', '0', 'sticker_flower_big_yellow', 1, 1, '13'),
(167, 'Pink Flower', '1', '0', 'sticker_flower_pink', 1, 1, '13'),
(168, 'Bobba badge', '1', '0', 'sticker_bobbaskull', 1, 1, '14'),
(169, 'Coffee badge', '1', '0', 'i_love_coffee', 1, 1, '14'),
(170, 'Bam!', '1', '0', 'sticker_effect_bam', 1, 1, '14'),
(171, 'Burp!', '1', '0', 'sticker_effect_burp', 1, 1, '14'),
(172, 'Whoosh!', '1', '0', 'sticker_effect_woosh', 1, 1, '14'),
(173, 'Zap!', '1', '0', 'sticker_effect_zap', 1, 1, '14'),
(174, 'Whoosh 2', '1', '0', 'sticker_effect_whoosh2', 1, 1, '14'),
(175, 'Small Ice Cube', '1', '0', 'icecube_small', 1, 1, '15'),
(176, 'Snowball Machine', '1', '0', 'ss_snowballmachine', 1, 1, '15'),
(177, 'Big Ice Cube', '1', '0', 'icecube_big', 1, 1, '15'),
(178, 'Boots and Gloves', '1', '0', 'bootsitjalapaset_red', 2, 1, '15'),
(179, 'Boots and Gloves', '1', '0', 'ss_bootsitjalapaset_blue', 2, 1, '15'),
(180, 'Red SnowStorm Costume', '1', '0', 'ss_costume_red', 2, 1, '15'),
(181, 'Blue SnowStorm Costume', '1', '0', 'ss_costume_blue', 2, 1, '15'),
(182, 'Splash!', '1', '0', 'ss_hits_by_snowball', 1, 1, '15'),
(183, 'SnowStorm Duck!', '1', '0', 'extra_ss_duck_left', 1, 1, '15'),
(184, 'Snowtree', '1', '0', 'ss_snowtree', 2, 1, '15'),
(185, 'SnowStorm Duck!', '1', '0', 'extra_ss_duck_right', 1, 1, '15'),
(186, 'Snowman', '1', '0', 'ss_snowman', 2, 1, '15'),
(187, 'Lumihiutale', '1', '0', 'ss_snowflake2', 1, 1, '15'),
(188, 'Snow Queen', '1', '0', 'ss_snowqueen', 2, 1, '15'),
(189, 'Battle 1', '1', '0', 'battle1', 1, 1, '16'),
(190, 'Battle 2', '1', '0', 'battle3', 1, 1, '16'),
(191, 'HC Hat', '1', '0', 'hc_hat', 5, 1, '18'),
(192, 'Left Eye', '1', '0', 'eyeleft', 2, 1, '18'),
(193, 'Right Eye', '1', '0', 'eyeright', 2, 1, '18'),
(194, 'Angel Wings', '1', '0', 'angelwings_anim', 3, 1, '18'),
(195, 'Gray Beard', '1', '0', 'sticker_gurubeard_gray', 1, 1, '18'),
(196, 'Brown Beard', '1', '0', 'sticker_gurubeard_brown', 1, 1, '18'),
(197, 'Supernerd', '1', '0', 'sticker_glasses_supernerd', 1, 1, '18'),
(198, 'Goofy Glasses', '1', '0', 'sticker_glasses_elton', 1, 1, '18'),
(199, 'Blue Eyes', '1', '0', 'sticker_eyes_blue', 1, 1, '18'),
(200, 'Animated Eyes', '1', '0', 'sticker_eye_anim', 2, 1, '18'),
(201, 'Evil Eye', '1', '0', 'sticker_eye_evil_anim', 2, 1, '18'),
(202, 'Eraser', '1', '0', 'sticker_eraser', 1, 1, '20'),
(203, 'star', '1', '0', 'star', 1, 1, '20'),
(204, 'Pencil', '1', '0', 'sticker_pencil', 1, 1, '20'),
(205, 'Dreamer', '1', '0', 'sticker_dreamer', 3, 1, '20'),
(206, 'Pencil 2', '1', '0', 'sticker_pencil_2', 1, 1, '20'),
(207, 'Lone Wolf', '1', '0', 'sticker_lonewolf', 3, 1, '20'),
(208, 'Prankster', '1', '0', 'sticker_prankster', 3, 1, '20'),
(209, 'Prankster', '1', '0', 'sticker_prankster', 3, 1, '20'),
(210, 'Romantic', '1', '0', 'sticker_romantic', 3, 1, '20'),
(211, 'Red lamp', '1', '0', 'redlamp', 2, 1, '20'),
(212, 'Lightbulb', '1', '0', 'lightbulb', 2, 1, '20'),
(213, 'Bullet hole', '1', '0', 'bullet1', 2, 1, '20'),
(214, 'Spill 1', '1', '0', 'spill1', 1, 1, '20'),
(215, 'Spill 2', '1', '0', 'spill2', 1, 1, '20'),
(216, 'Spill 3', '1', '0', 'spill3', 1, 1, '20'),
(217, 'Coffee stain', '1', '0', 'sticker_coffee_stain', 1, 1, '20'),
(218, 'Hole', '1', '0', 'sticker_hole', 1, 1, '20'),
(219, 'Flames', '1', '0', 'sticker_flames', 2, 1, '20'),
(220, 'Paperclip 1', '1', '0', 'paper_clip_1', 1, 1, '20'),
(221, 'Paperclip 2', '1', '0', 'paper_clip_2', 1, 1, '20'),
(222, 'Paperclip 3', '1', '0', 'paper_clip_3', 1, 1, '20'),
(223, 'Highlight 1', '1', '0', 'highlighter_1', 1, 1, '20'),
(224, 'Highlight', '1', '0', 'highlighter_mark5', 1, 1, '20'),
(225, 'Highlight', '1', '0', 'highlighter_mark6', 1, 1, '20'),
(226, 'Highlight', '1', '0', 'highlighter_mark4b', 1, 1, '20'),
(227, 'Highlight 2', '1', '0', 'highlighter_2', 1, 1, '20'),
(228, 'Highlight', '1', '0', 'highlighter_mark1', 1, 1, '20'),
(229, 'Highlight', '1', '0', 'highlighter_mark2', 1, 1, '20'),
(230, 'Highlight', '1', '0', 'highlighter_mark3', 1, 1, '20'),
(231, 'Plaster', '1', '0', 'plaster1', 1, 1, '20'),
(232, 'Plaster', '1', '0', 'plaster2', 1, 1, '20'),
(233, 'Croc', '1', '0', 'sticker_croco', 1, 1, '20'),
(234, 'Fish', '1', '0', 'fish', 1, 1, '20'),
(235, 'Parrot', '1', '0', 'parrot', 1, 1, '20'),
(236, 'Sleeping Holo', '1', '0', 'sticker_sleeping_habbo', 2, 1, '20'),
(237, 'Burger', '1', '0', 'burger', 1, 1, '20'),
(238, 'Juice', '1', '0', 'juice', 1, 1, '20'),
(239, 'Coffee Stain Blue', '1', '0', 'sticker_coffee_steam_blue', 1, 1, '20'),
(240, 'Coffee Stain Gray', '1', '0', 'sticker_coffee_steam_grey', 1, 1, '20'),
(241, 'Cassette 1', '1', '0', 'cassette1', 1, 1, '20'),
(242, 'Cassette 2', '1', '0', 'cassette2', 1, 1, '20'),
(243, 'Cassette 3', '1', '0', 'cassette3', 1, 1, '20'),
(244, 'Cassette 4', '1', '0', 'cassette4', 1, 1, '20'),
(245, 'Football', '1', '0', 'football', 1, 1, '20'),
(246, 'Floral 2', '4', '0', 'bg_pattern_floral_02', 2, 1, '27'),
(247, 'Floral 3', '4', '0', 'bg_pattern_floral_03', 2, 1, '27'),
(248, 'Cars', '4', '0', 'bg_pattern_cars', 2, 1, '27'),
(249, 'Carpants', '4', '0', 'bg_pattern_carpants', 2, 1, '27'),
(250, 'Plasto', '4', '0', 'bg_pattern_plasto', 2, 1, '27'),
(251, 'Tinny room', '4', '0', 'bg_pattern_tinyroom', 2, 1, '27'),
(252, 'Hearts', '4', '0', 'bg_pattern_hearts', 2, 1, '27'),
(253, 'Abstract 1', '4', '0', 'bg_pattern_abstract1', 2, 1, '27'),
(254, 'Bathroom tile', '4', '0', 'bg_bathroom_tile', 2, 1, '27'),
(255, 'Fish', '4', '0', 'bg_pattern_fish', 2, 1, '27'),
(256, 'Deepred', '4', '0', 'bg_pattern_deepred', 2, 1, '27'),
(257, 'Background', '4', '0', 'bg_colour_02', 2, 1, '27'),
(258, 'Background', '4', '0', 'bg_colour_03', 2, 1, '27'),
(259, 'Background', '4', '0', 'bg_colour_04', 2, 1, '27'),
(260, 'Background', '4', '0', 'bg_colour_05', 2, 1, '27'),
(261, 'Background', '4', '0', 'bg_colour_06', 2, 1, '27'),
(262, 'Background', '4', '0', 'bg_colour_07', 2, 1, '27'),
(263, 'Background', '4', '0', 'bg_colour_08', 2, 1, '27'),
(264, 'Background', '4', '0', 'bg_colour_09', 2, 1, '27'),
(265, 'Background', '4', '0', 'bg_colour_10', 2, 1, '27'),
(266, 'Background', '4', '0', 'bg_colour_11', 2, 1, '27'),
(267, 'Background', '4', '0', 'bg_colour_12', 2, 1, '27'),
(268, 'Background', '4', '0', 'bg_colour_13', 2, 1, '27'),
(269, 'Background', '4', '0', 'bg_colour_14', 2, 1, '27'),
(270, 'Background', '4', '0', 'bg_colour_15', 2, 1, '27'),
(271, 'Background', '4', '0', 'bg_colour_17', 2, 1, '27'),
(272, 'Tonga', '4', '0', 'bg_tonga', 2, 1, '27'),
(273, 'HoloCMS', '1', '0', 'sticker_holocms', 5, 1, '19'),
(274, 'Pedobear Seal of Approval', '1', '0', 'sticker_pedo', 50, 1, '19'),
(275, 'Alhambra Group Background', '4', '0', 'alhambragroup', 0, 1, '50'),
(276, 'Themepark Background 1', '4', '0', 'themepark_bg_01', 0, 1, '50'),
(277, 'Themepark Background 2', '4', '0', 'themepark_bg_02', 0, 1, '50'),
(278, 'Unofficial Fansites Background', '4', '0', 'bg_unofficial_fansites', 0, 1, '50'),
(279, 'Unofficial Fansites Background 2', '4', '0', 'bg_official_fansites2', 0, 1, '50'),
(280, 'Welcoming Party Background', '4', '0', 'welcoming_party', 0, 1, '50'),
(281, 'Random Habbos Background', '4', '0', 'random_habbos', 0, 1, '50'),
(282, 'Habborella Sea Background', '4', '0', 'habborella_sea_bg', 0, 1, '50'),
(283, 'Penelope Background', '4', '0', 'penelope', 0, 1, '50'),
(284, 'Orca Background', '4', '0', 'orca', 0, 1, '50'),
(285, 'sttriniansgroup Background', '4', '0', 'sttriniansgroup', 0, 1, '50'),
(286, 'sttriniansblackboard Background', '4', '0', 'sttriniansblackboard', 0, 1, '50'),
(287, 'Habbo X Background', '4', '0', 'habbox', 0, 1, '50'),
(288, 'Christmas 2007 BG 1', '4', '0', 'christmas2007bg_001', 0, 1, '50'),
(289, 'Kerrang Background 2', '4', '0', 'bg_kerrang2', 0, 1, '50'),
(291, 'VoiceOfTeens Background', '4', '0', 'bg_voiceofteens', 0, 1, '50'),
(292, 'Makeover Background', '4', '0', 'makeover', 0, 1, '50'),
(293, 'SnowStorm Background', '4', '0', 'snowstorm_bg', 0, 1, '50'),
(294, 'Habbo Group Background', '4', '0', 'habbogroup', 0, 1, '50'),
(295, 'Wobble Squabble Background', '4', '0', 'bg_wobble_squabble', 0, 1, '50'),
(296, 'VIP Background', '4', '0', 'bg_vip', 0, 1, '50'),
(297, 'Lido Background', '4', '0', 'bg_lidoo', 0, 1, '50'),
(298, 'Lido (Flat) Background', '4', '0', 'bg_lido_flat', 0, 1, '50'),
(299, 'Infobus (Yellow) Background', '4', '0', 'bg_infobus_yellow', 0, 1, '50'),
(300, 'Infobus (White) Background', '4', '0', 'bg_infobus_white', 0, 1, '50'),
(301, 'Infobus (Blue) Background', '4', '0', 'bg_infobus_blue', 0, 1, '50'),
(302, 'Battle Ball (2) Background', '4', '0', 'bg_battle_ball2', 0, 1, '50'),
(303, 'Grunge Wall Background', '4', '0', 'grungewall', 0, 1, '50');") or die(mysql_error());
		mysql_query("ALTER TABLE `rooms` CHANGE `name` `name` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL");
		mysql_query("ALTER TABLE `users` ADD `visibility` INT( 1 ) NOT NULL DEFAULT '1' AFTER `lastgift` ;");
		mysql_query("ALTER TABLE `users` ADD `screen` VARCHAR( 4 ) NULL DEFAULT 'wide' AFTER `online` ;");
		mysql_query("ALTER TABLE `users` ADD `rea` VARCHAR( 8 ) NULL DEFAULT 'enabled' AFTER `screen` ;");
		mysql_query("ALTER TABLE `soundmachine_songs` ADD `cms_current` INT( 1 ) NULL DEFAULT '0' AFTER `burnt` ;");
		mysql_query("ALTER TABLE `soundmachine_songs` ADD `cms_owner` INT( 100 ) NULL AFTER `cms_current` ;");

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
				$password = HoloHash($password);
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