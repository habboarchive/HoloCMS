<?php
include('../core.php');
$sql = mysql_query("SELECT title FROM cms_collectables WHERE month='".date('n')."' OR month='".date('m')."' LIMIT 1");
$row = mysql_fetch_assoc($sql); ?>

<p>
Are you sure you want to purchase <?php echo HoloText($row['title']); ?>? It will cost 25 credits.
</p>

<p>
<a href="#" class="new-button" id="collectibles-purchase"><b>Purchase</b><i></i></a>
<a href="#" class="new-button" id="collectibles-close"><b>Cancel</b><i></i></a>
</p>