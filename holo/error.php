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

$allow_guests = true;

if($cored != true){
include('./core.php');
include('./includes/session.php');
}
$pageid = "profile";
$pagename = "Page not found";
include('templates/community/subheader.php');
include('templates/community/header.php');
?>

<div id="container">
	<div id="content" style="position: relative" class="clearfix">
    <div id="column1" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix red ">

							<h2 class="title">Page not found!
							</h2>
						<div id="notfound-content" class="box-content">
    <p class="error-text">Sorry, but the page you were looking for was not found.</p> <img id="error-image" src="./web-gallery/v2/images/error.gif" />
    <p class="error-text">Please use the 'Back' button to get back to where you started.</p>
</div>


					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

</div>
<div id="column2" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix green ">

							<h2 class="title">Were you looking for...
							</h2>
						<div id="notfound-looking-for" class="box-content">
    <p><b>A friend's group or personal page?</b><br/>
    See if it is listed on the <a href="community.php">Community</a> page.</p>

    <p><b>Rooms that rock?</b><br/>
    Browse the <a href="community.php">Recommended Rooms</a> list.</p>

    <p><b>What other users are in to?</b><br/>
    Check out the <a href="tags.php">Top Tags</a> list.</p>

     <p><b>How to get Credits?</b><br/>
    Have a look at the <a href="credits.php">Credits</a> page.</p>
</div>


					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

</div>

<?php
include('templates/community/footer.php');
?>