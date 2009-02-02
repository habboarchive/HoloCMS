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

if(isset($_GET['key'])){
	$key = FilterText($_GET['key']);
	$keysql = mysql_query("SELECT * FROM cms_verify WHERE key_hash = '".$key."' LIMIT 1");
	if(isset($_GET['verify'])){ $reward = false; }else{ $reward = true;}
	if(mysql_num_rows($keysql) > 0){
		$keyrow = mysql_fetch_assoc($keysql);
		mysql_query("UPDATE users SET email_verified = '1', email = '".$keyrow['email']."' WHERE id = '".$keyrow['id']."' LIMIT 1");
		if($reward == true){ mysql_query("UPDATE users SET credits = credits + ".$email_verify_reward." WHERE id = '".$keyrow['id']."' LIMIT 1"); }
		mysql_query("DELETE FROM cms_verify WHERE key_hash = '".$key."' LIMIT 1");
		$sucess = "1";
	}else{
		$sucess = "0";
	}
}else{
	$sucess = "0";
}
if(isset($_GET['remove'])){
	$key = FilterText($_GET['remove']);
	$keysql = mysql_query("SELECT * FROM cms_verify WHERE key_hash = '".$key."' LIMIT 1");
	if(mysql_num_rows($keysql) > 0){
		$keyrow = mysql_fetch_assoc($keysql);
		mysql_query("UPDATE users SET email = '', email_verified = '0', newsletter = '0' WHERE id = '".$keyrow['id']."' LIMIT 1");
		mysql_query("DELETE FROM cms_verify WHERE key_hash = '".$key."' LIMIT 1");
		$sucess = "2";
	}else{
		$sucess = "0";
	}
}
include("locale/".$language."/login.php");
include('templates/login/subheader.php');
include('templates/login/header.php');
if($sucess == "1"){
?>
			<div id="process-content">
	        	<div id ="email-verified-container">
    <div class="cbb clearfix green">
        <h2 class="title heading">Email link handled successfully</h2>
    	<div class="box-content">

            <ul>
	            <li>Your email address is now verified.</li>
            </ul>
            <a href="<?php echo $path; ?>">Continue to <?php echo $shortname; ?> front page.</a>
    	</div>
    </div>
</div>
<?php }elseif($sucess == "2"){ ?>
			<div id="process-content">
	        	<div id ="email-verified-container">
    <div class="cbb clearfix green">
        <h2 class="title heading">Email link handled successfully</h2>
    	<div class="box-content">

            <ul>
	            <li>You should no longer receive emails from <?php echo $sitename; ?>. Your email address has been deleted.</li>
            </ul>
            <a href="<?php echo $path; ?>">Continue to <?php echo $shortname; ?> front page.</a>
    	</div>
    </div>
</div>
<?php }else{ ?>
			<div id="process-content">
	        	<div id ="email-verified-container">
    <div class="cbb clearfix red">
        <h2 class="title heading">Error handling email link</h2>

    	<div class="box-content">
            <ul>
	            <li>The verification code is invalid or the action has already been done.</li>
            </ul>
            <a href="<?php echo $path; ?>">Continue to <?php echo $shortname; ?> front page.</a>
    	</div>
    </div>
</div>
<?php }
include('templates/login/footer.php');
?>