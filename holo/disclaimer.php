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

include("locale/".$language."/login.php");
include("locale/".$language."/policies.php");

include('templates/login/subheader.php');
include('templates/login/header.php');

?>

<div id="process-content">
	        	<div id="terms" class="box-content">
<div class="tos-header"><b><?php echo $locale['terms_of_service_title']; ?></b></div>
<div class="tos-item"><?php echo $locale['terms_of_service']; ?></div>
</div>



<?php

include('templates/login/footer.php');

?>