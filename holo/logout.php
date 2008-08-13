<?php
/*---------------------------------------------------+
| HoloCMS - Website and Content Management System
+----------------------------------------------------+
| Copyright © 2008 Meth0d
+----------------------------------------------------+
| HoloCMS is provided "as is" and comes without
| warrenty of any kind. 
+---------------------------------------------------*/

if(isset($login_error) && $is_banned > 0){

	session_start();
	include("locale/".$language."/login.php");

} else {

	include('core.php');
	include("locale/".$language."/login.php");

	session_start();
}

        // Destroy the 'remember me' cookie by making it expire 100 days ago
	setcookie("remember", "", time()-60*60*24*100, "/"); setcookie("cookpass", "", time()-60*60*24*100, "/");
	setcookie("rusername", "", time()-60*60*24*100, "/"); setcookie("cookpass", "", time()-60*60*24*100, "/");
	setcookie("rpassword", "", time()-60*60*24*100, "/"); setcookie("cookpass", "", time()-60*60*24*100, "/");
        
        mysql_query("UPDATE users SET online = '0' WHERE id = '".$my_id."' LIMIT 1") or die(mysql_error());

if(isset($login_error) && $is_banned > 0){

include('templates/login/subheader.php');
include('templates/login/header.php');
?>

<div id="process-content">
	        	<div class="action-error flash-message">
	<div class="rounded">
		<b><?php echo $login_error; ?></b>
	</div>
</div>

<div style="text-align: center">
	
	<div style="width:100px; margin: 10px auto"><a href="index.php" id="logout-ok" class="new-button fill"><b><?php echo $locale['ok']; ?></b><i></i></a></div>

<div id="column1" class="column">              
</div>
<div id="column2" class="column">
</div>

</div>

<?php
} elseif(session_is_registered(username)){

session_destroy();

		if($_COOKIE['remember'] == "remember"){
		setcookie("remember", "", time()-60*60*24*100, "/"); setcookie("cookpass", "", time()-60*60*24*100, "/");
		setcookie("rusername", "", time()-60*60*24*100, "/"); setcookie("cookpass", "", time()-60*60*24*100, "/");
		setcookie("rpassword", "", time()-60*60*24*100, "/"); setcookie("cookpass", "", time()-60*60*24*100, "/");
		}

include('templates/login/subheader.php');
include('templates/login/header.php');

?>

<div id="process-content">
	        	<div class="action-confirmation flash-message">
	<div class="rounded">
		<b><?php echo $locale['logged_out']; ?></b>
	</div>
</div>

<div style="text-align: center">
	
	<div style="width:100px; margin: 10px auto"><a href="index.php" id="logout-ok" class="new-button fill"><b><?php echo $locale['ok']; ?></b><i></i></a></div>

<div id="column1" class="column">              
</div>
<div id="column2" class="column">
</div>

</div>

<?php

} else {

include('templates/login/subheader.php');
include('templates/login/header.php');

?>

<div id="process-content">
	        	<div class="action-error flash-message">
	<div class="rounded">
		<b><?php echo $locale['error_not_logged_in']; ?></b>
	</div>
</div>

<div style="text-align: center">
	
	<div style="width:100px; margin: 10px auto"><a href="index.php" id="logout-ok" class="new-button fill"><b><?php echo $locale['ok']; ?></b><i></i></a></div>

<div id="column1" class="column">              
</div>
<div id="column2" class="column">
</div>

</div>

<?php

}

include('templates/login/footer.php');

?>
