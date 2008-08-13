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

if($hkzone !== true){ header("Location: index.php?throwBack=true"); exit; }

?>
<br />
 <div class='copy' align='center'>HoloCMS v<?php echo $holocms['version']; ?> [ <?php echo $holocms['title']; ?> ] <?php echo $holocms['stable']; ?><br /><font size='1'>Build <?php echo $holocms['date']; ?> </font></div>
</div>
</body>
</html>