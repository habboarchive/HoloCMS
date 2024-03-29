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

$allow_guests = true;

include('core.php');
include('includes/session.php');

$pagename = "Credits";
$pageid = "6";
$body_id = "home";

include('templates/community/subheader.php');
include('templates/community/header.php');

?>

<div id='container'>
<div id='content'>
<div id='column1' class='column'><div class='habblet-container '>		
<div class='cbb clearfix green '>
	
<h2 class='title'><?php echo HoloText(getContent('credits2-heading'), true); ?></h2>
<p class='credits-countries-select'><?php echo HoloText(getContent('credits2'), true); ?></p>
</div>
</div>
				<script type='text/javascript'>if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
			 
</div>
<div id="column2" class="column">
				<div class="habblet-container ">		
						<div class="cbb clearfix brown ">
	
							<h2 class="title">Your purse
							</h2>
						<div id="purse-habblet">
                                                    <?php if($logged_in){ ?>
	<form method="post" action="credits.php" id="voucher-form">
<ul>
    <li class="even icon-purse">
        <div>You Currently Have:</div>
        <span class="purse-balance-amount"><?php echo $myrow['credits']; ?> Credits</span>
        <div class="purse-tx"><a href="transactions.php">Account transactions</a></div>
    </li>

    <li class="odd">
        <div class="box-content">
            <div>Enter redemption code:</div>
            <input type="text" name="voucherCode" value="" id="purse-habblet-redeemcode-string" class="redeemcode" />
            <a href="#" id="purse-redeemcode-button" class="new-button purse-icon" style="float:left"><b><span></span>Submit</b><i></i></a>
        </div>
    </li>
</ul>
<div id="purse-redeem-result">
</div>	</form>
        <?php } else { ?>
        <div class="box-content">You need to be logged in to see the purse.</div>
        <?php } ?>
</div>

<script type="text/javascript">
	new PurseHabblet();
</script>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
			 
				<div class="habblet-container ">		
	
						
	
						
					
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>			 

			 
				<div class='habblet-container '>		
						<div class='cbb clearfix blue '>
	
							<h2 class='title'><?php echo HoloText(getContent('credits1-heading'), true); ?></h2>
						<div id='credits-promo' class='box-content credits-info'>
    <div class='credit-info-text clearfix'>
    <p><img class="credits-image" src="./web-gallery/v2/images/credits_permission.png" align="left" width="114" height="136"/>
    <?php echo HoloText(getContent('credits1'), true); ?></p>
</div>
	
						
					</div>
				</div>
				<script type='text/javascript'>if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
			 
							 
</div>

<script type="text/javascript">
HabboView.run();
</script>	
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
<?php

include('templates/community/footer.php');

?>
