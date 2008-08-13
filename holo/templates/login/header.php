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
if(!isset($body_id)){ $body_id = "landing"; }

?>

<body id="<?php echo $body_id; ?>" class="process-template">

<div id="overlay"></div>

<div id="container">
	<div class="cbb process-template-box clearfix">
		<div id="content">
			<div id="header" class="clearfix">
				<h1><a href="index.php"></a></h1>
				<ul class="stats">
					    <li class="stats-online"><span class="stats-fig"><?php echo $online_count; ?></span> <?php echo $locale['users_online_now']; ?></li>
					    <li class="stats-visited"><img src='./web-gallery/v2/images/<?php echo $online; ?>.gif' alt='Server Status' border='0'></li>
				</ul>
			</div>