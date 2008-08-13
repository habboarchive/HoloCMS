<?php
include('../core.php');
if(function_exists(SendMUSData) !== true){ include('../includes/mus.php'); }

$sql = mysql_query("SELECT credits FROM users WHERE id='".$my_id."' LIMIT 1");
$row = mysql_fetch_assoc($sql);

// Has the user enough credits?
$credits = ($row['credits'] - 25);

if($credits < 0) {
// user hasn't enough credits ?>
<p>
Purchasing the collectable failed.
</p>

<p>
	You don't have enough credits.<br />
</p>

<p>
<a href="#" class="new-button" id="collectibles-close"><b>Cancel</b><i></i></a>
</p>
<?php }else{
// It seems like the user has enough credits, we're now going to 'buy' the collectable
$sql = mysql_query("SELECT * FROM cms_collectables WHERE month='".date('n')."' OR month='".date('m')."' AND year='".date('Y')."' LIMIT 1") or die(mysql_error());
$row = mysql_fetch_assoc($sql);
mysql_query("UPDATE users SET credits = credits - 25 WHERE id='".$my_id."' LIMIT 1") or die(mysql_error());
mysql_query("INSERT INTO furniture (ownerid,roomid,tid) VALUES ('".$my_id."','0','".$row['tid']."')") or die(mysql_error());
mysql_query("INSERT INTO cms_transactions (userid,amount,date,descr) VALUES ('".$my_id."','25','".$date_full."','Bought a collectable')") or die(mysql_error());
// katsjing sound
@SendMUSData('UPRC' . $my_id);
// reload hand
@SendMUSData('UPRH' . $my_id);
// Now we say in a message he has the furniture! ?>
<p>
You've succesfully bought a <?php echo stripslashes(htmlspecialchars($row['title'])); ?>.
</p>


<p>
<a href="#" class="new-button" id="collectibles-close"><b>OK</b><i></i></a>
</p>
<?php }
// finished ?>