<?php
include('../core.php');



// Gift check (noob/welcome stuff)
$sql = mysql_query("SELECT noob,gift,sort,roomid,lastgift FROM users WHERE id='".$my_id."' LIMIT 1");
$row = mysql_fetch_assoc($sql);
if($row['gift'] < 3) {
if($row['noob'] == 1) {
	if($row['lastgift'] < date("d-m-Y")) {
		mysql_query("INSERT INTO cms_noobgifts (userid,gift,read) VALUES ('".$my_id."','".$row['gift']."','0')");
		mysql_query("UPDATE users SET lastgift='".date("d-m-Y")."',gift=gift+'1' WHERE id='".$my_id."' LIMIT 1");
	}
}
}


// Now we gonna show again the "countdown" to the new gift.  ?>
<div class="gift-img"><?php if($row['gift'] == 0) { ?><img src="http://images.habbohotel.co.uk/habboweb/<?php echo $habboversion; ?>/web-gallery/v2/images/welcome/newbie_furni/noob_stool_<?php echo $row['sort']; ?>.png" alt="My first Habbo stool" /><?php }elseif($row['gift'] == 1) { ?><img src="http://images.habbohotel.co.uk/habboweb/23_deebb3529e0d9d4e847a31e5f6fb4c5b/9/web-gallery/v2/images/welcome/newbie_furni/noob_plant.png"><?php } ?></div>
<div class="gift-content-container">
<?php if($row['gift'] < 3) { ?>
<p class="gift-content">
Your next piece of free furniture will be <strong><?php if($row['gift'] == 0) { echo "My first Habbo stool"; }elseif($row['gift'] == 1) { echo "plant"; } ?></strong>
</p>

<p>
<b>Time left:</b> <span id="gift-countdown"></span>
</p>

<p class="last">
<a class="new-button green-button" href="client.php?forwardId=2&roomId=<?php echo $row['roomid']; ?>" target="client" onclick="HabboClient.roomForward(this, '<?php echo $row['roomid']; ?>', 'private'); return false;"><b>Go to your room &gt;&gt;</b><i></i></a>
</p>
<br style="clear: both" />
</div>



<?php
// calculate time

$time = time();
$day = date("j");
$month = date("n");
$year = date("y");
$date = mktime(0,0,0, $month, $day, $year);
$timeleft = $date-$time; ?>

<script type="text/javascript">
L10N.put("time.hours", "{0}h");
L10N.put("time.minutes", "{0}min");
L10N.put("time.seconds", "{0}s");
GiftQueueHabblet.init(<?php echo $timeleft; ?>);
</script>

<?php // End calculate time 
}else{
// End free gifts, we're now going to say that?>
<p>
How do you get more furniture into Your room?
</p>

<p>
You could buy a set of furniture for just 3 credits including a lamp, mat, and two armchairs. How do you do that?
</p>

<ul><li>1. Buy some credits from the <a href="/credits">credits</a> section</li><li>2. Open the catalogue from the Hotel toolbar (Chair icon)</li><li>3. Open the deals section</li><li>4. Pick up the furni set You want</li><li>5. Thank You for shopping!</li></ul>

<p class="aftergift-img">
  <img src="http://images.habbohotel.co.uk/habboweb/23_deebb3529e0d9d4e847a31e5f6fb4c5b/9/web-gallery/v2/images/giftqueue/aftergifts.png" alt="" width="381" height="63"/>
</p>

<p class="last">
<a class="new-button green-button" href="client.php?forwardId=2&roomId=<?php echo $row['roomid']; ?>" target="client" onclick="HabboClient.roomForward(this, '<?php echo $row['roomid']; ?>', 'private'); return false;"><b>Go to your room &gt;&gt;</b><i></i></a>
</p>

<script type="text/javascript">
HabboView.add(GiftQueueHabblet.initClosableHabblet);
<?php mysql_query("UPDATE users SET noob='0'"); ?>
</script>
<?php } ?>