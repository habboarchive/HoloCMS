<?php
include('core.php');

if(isset($_GET['id'])) {


	$sql = mysql_query("SELECT name FROM users WHERE id='".htmlspecialchars($_GET['id'])."'");
	$row = mysql_fetch_assoc($sql); ?>
	
<html>
<head>
  <title>Redirecting...</title>
  <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
  <style type="text/css">body { background-color: #e3e3db; text-align: center; font: 11px Verdana, Arial, Helvetica, sans-serif; } a { color: #fc6204; }</style>
</head>
<body>

<script type="text/javascript">window.location.replace('user_profile.php?tag=<?php echo $row['name']; ?>');</script><noscript><meta http-equiv="Refresh" content="0;URL=user_profile.php?tag=<?php echo $row['name']; ?>"></noscript>

<p class="btn">If you are not automatically redirected, please <a href="user_profile.php?tag=<?php echo $row['name']; ?>" id="manual_redirect_link">click here</a></p>

</body>
</html>
<?php }else{ ?>
<html>
<head>
  <title>Redirecting...</title>
  <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
  <style type="text/css">body { background-color: #e3e3db; text-align: center; font: 11px Verdana, Arial, Helvetica, sans-serif; } a { color: #fc6204; }</style>
</head>
<body>

<script type="text/javascript">window.location.replace('index.php');</script><noscript><meta http-equiv="Refresh" content="0;URL=index.php"></noscript>

<p class="btn">If you are not automatically redirected, please <a href="index.php" id="manual_redirect_link">click here</a></p>

</body>
</html>
<?php } ?>