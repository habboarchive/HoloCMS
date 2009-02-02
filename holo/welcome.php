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
include('includes/session.php');

$check = mysql_query("SELECT noob FROM users WHERE id='".$my_id."'");
$row = mysql_fetch_assoc($check);

if($row['noob'] != 0) {
header("location:../zindex.php");

?>

<html>
<head>
  <title>Redirecting...</title>
  <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
  <style type="text/css">body { background-color: #e3e3db; text-align: center; font: 11px Verdana, Arial, Helvetica, sans-serif; } a { color: #fc6204; }</style>
</head>
<body>
</body>
</html>
<?php
}else{


$pagename = $name;
$pageid = "3";

include('templates/community/subheader.php');
include('templates/community/header.php');
?>

<div id="container">
	<div id="content" style="position: relative" class="clearfix">
    <div id="column1" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix lightgreen ">

							<h2 class="title">Choose a pre-decorated room and get free furniture!
							</h2>
						<div id="roomselection-welcome-intro" class="box-content">
    Select the room you like best to get a new piece of furniture every day during your first week in Obbah!
</div>
<ul class="roomselection-welcome clearfix">
	<li class="odd">
	<a class="roomselection-select new-button" href="client.php?createRoom=0" target="client" onclick="return RoomSelectionHabblet.create(this, 0);"><b>Select</b><i></i></a>
	</li>
	<li class="even">
	<a class="roomselection-select new-button" href="client.php?createRoom=1" target="client" onclick="return RoomSelectionHabblet.create(this, 1);"><b>Select</b><i></i></a>
	</li>
	<li class="odd">
	<a class="roomselection-select new-button" href="client.php?createRoom=2" target="client" onclick="return RoomSelectionHabblet.create(this, 2);"><b>Select</b><i></i></a>
	</li>
	<li class="even">
	<a class="roomselection-select new-button" href="client.php?createRoom=3" target="client" onclick="return RoomSelectionHabblet.create(this, 3);"><b>Select</b><i></i></a>
	</li>
	<li class="odd">
	<a class="roomselection-select new-button" href="client.php?createRoom=4" target="client" onclick="return RoomSelectionHabblet.create(this, 4);"><b>Select</b><i></i></a>
	</li>
	<li class="even">
	<a class="roomselection-select new-button" href="client.php?createRoom=5" target="client" onclick="return RoomSelectionHabblet.create(this, 5);"><b>Select</b><i></i></a>
	</li>
</ul>



					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

</div>
<div id="column2" class="column">
				<div class="habblet-container ">

						<div class="cbb clearfix lightgreen">

<div class="welcome-intro clearfix">
	<img alt="Prince-Cutie9" src="http://www.habbo.co.uk/habbo-imaging/avatarimage?figure=<?php echo $myrow['figure']; ?>&size=b&action=crr=667&direction=3&head_direction=3&gesture=srp
" width="64" height="110" class="welcome-habbo" />
    <div id="welcome-intro-welcome-user"  >Welcome <?php echo $name; ?>!</div>
    <div id="welcome-intro-welcome-party" class="box-content">When you've choosen a room you will automaticly go to it! Than you get 8 days after each other free furnitures! Enjoy <?php echo $sitename; ?>!</div>
    </div>
</div>

</div>	
					
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
			 

	<?php
	if($_GET['referral'] != ""){
	$referrow = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE id = '".FilterText($_GET['referral'])."' LIMIT 1"));
	if(IsUserOnline($referrow['id']) == true){ $online = "online"; }else{ $online = "offline"; }
	?>
				<div class="habblet-container ">		
						<div class="cbb clearfix white ">
	
							<h2 class="title">Welcome
							</h2>
						<div id="inviter-info-habblet">
        <p><span class="text">Your friend <?php echo $referrow['name']; ?> is waiting for you in <?php echo $shortname; ?>!<em class="<?php echo $online; ?>"></em></span><span class="bottom"></span></p>
        <img alt="<?php echo $referrow['name']; ?>" title="<?php echo $referrow['name']; ?>" src="http://www.habbo.co.uk/habbo-imaging/avatarimage?figure=<?php echo $referrow['figure']; ?>&size=b&direction=4&head_direction=4&gesture=sml" />

        <div style="clear: left; text-align: center; padding-top: 10px; font-size: 10px">You can find him/her on your friendlist</div>    
    </div>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
			 
	<?php } ?>
			     		
				<div class="habblet-container ">		
						<div class="cbb clearfix green ">
	
							<h2 class="title">Got Shockwave?
							</h2>
						<div class="welcome-shockwave clearfix box-content">
    <div id="welcome-shockwave-text">When you open <?php echo $sitename; ?> for the first time you might need to install Shockwave. But don't worry, it's as easy as 1-2-3!</div>
    <div id="welcome-shockwave-logo"><img src="web-gallery/v2/images/welcome/shockwave.gif" alt="shockwave" /></div>
</div>


					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

</div>
<script type="text/javascript">
HabboView.run();
</script>

<!--[if lt IE 7]>
<script type="text/javascript">
Pngfix.doPngImageFix();
</script>
<![endif]-->
<?php

include('templates/community/footer.php');
}
?>