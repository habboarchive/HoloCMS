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
 <div class='menuouterwrap'>
  <div class='menucatwrap'><img src='./images/menu_title_bullet.gif' style='vertical-align:bottom' border='0' /> Server Configuration</div>

<div class='menulinkwrap'>&nbsp;
<img src='./images/item_bullet.gif' border='0' alt='' valign='absmiddle'>&nbsp;
<a href='./index.php?p=server' style='text-decoration:none'>General Configuration</a>
</div>

<div class='menulinkwrap'>&nbsp;
<img src='./images/item_bullet.gif' border='0' alt='' valign='absmiddle'>&nbsp;
<a href='./index.php?p=recycler' style='text-decoration:none'>Recycler Options</a>
</div>

<div class='menulinkwrap'>&nbsp;
<img src='./images/item_bullet.gif' border='0' alt='' valign='absmiddle'>&nbsp;
<a href='./index.php?p=wordfilter' style='text-decoration:none'>Wordfilter Options</a>
</div>

<div class='menulinkwrap'>&nbsp;
<img src='./images/item_bullet.gif' border='0' alt='' valign='absmiddle'>&nbsp;
<a href='./index.php?p=welcomemsg' style='text-decoration:none'>Welcome Message Options</a>
</div>

</div>
<br />