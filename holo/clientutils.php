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

include('core.php');

if($logged_in){ $key = $_GET['key']; } else { $key = "LogInPlease"; }

if($key == "cacheCheck" || $key == "cache"){
echo "true";
} elseif($key == "connection_failed"){
include('./templates/client/subheader.php');
?>
<body id="popup" class="process-template client_error">
<div id="container">
    <div id="content">
	    <div id="process-content" class="centered-client-error">
	       	<div id="column1" class="column">
				<div class="habblet-container ">		
						<div class="cbb clearfix orange ">
	
							<h2 class="title">Connection to <?php echo $sitename; ?> failed.
							</h2>
						<div class="box-content">
    <p>Unfortunately we are unable to connect you to <?php echo $sitename; ?>. This could be because your computer is blocking the connections via a firewall. 
Please verify with the person responsible for your Internet connection that the following addresses are permitted by the firewall:
IP address: <?php echo $ip; ?>, TCP port: <?php echo $port; ?></p>   
</div>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
			 
</div>
<script type="text/javascript">
HabboView.run();
</script>
<div id="column2" class="column">
</div>

<!--[if lt IE 7]>
<script type="text/javascript">
Pngfix.doPngImageFix();
</script>
<![endif]-->
		</div>
    </div>
</div>

</body>
</html>
<?php } elseif($key == "error"){
include('./templates/client/subheader.php');
?>
<body id="popup" class="process-template client_error">
<div id="container">
    <div id="content">
	    <div id="process-content" class="centered-client-error">
	       	<div id="column1" class="column">
				<div class="habblet-container ">		
						<div class="cbb clearfix orange ">
	
							<h2 class="title">Fatal Error
							</h2>
						<div class="box-content">
    <p>An unknown error occured. Please try again later in a couple of minutes. If this error is persistant, use the Help Tool to request support.</p>   
</div>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
			 
</div>
<script type="text/javascript">
HabboView.run();
</script>
<div id="column2" class="column">
</div>

<!--[if lt IE 7]>
<script type="text/javascript">
Pngfix.doPngImageFix();
</script>
<![endif]-->
		</div>
    </div>
</div>

</body>
</html>
<?php } elseif($key == "LogInPlease" && !$logged_in){
include('./templates/client/subheader.php');
?>
<body id="popup" class="process-template client_error">
<div id="container">
    <div id="content">
	    <div id="process-content" class="centered-client-error">
	       	<div id="column1" class="column">
				<div class="habblet-container ">		
						<div class="cbb clearfix orange ">
	
							<h2 class="title">Sign in
							</h2>
						<div class="box-content">
    <p align='center'>In order to use the game client and the complete website, please <a href='index.php' target='_self'><strong>sign in</strong></a>! If you forgot your username or password, please click <a href='forgot.php'>here</a>.</p>   
</div>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
			 
</div>
<script type="text/javascript">
HabboView.run();
</script>
<div id="column2" class="column">
</div>

<!--[if lt IE 7]>
<script type="text/javascript">
Pngfix.doPngImageFix();
</script>
<![endif]-->
		</div>
    </div>
</div>

</body>
</html>
<?php } ?>