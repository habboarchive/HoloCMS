<?php
if($bypass == true){
$page = "1";
$search = "";
}else{
include('../core.php');
include('../includes/session.php');
$page = $_POST['pageNumber'];
$search = $_POST['searchString'];
$widgetid = $_POST['widgetId'];
}

if($search == ""){
$sql = mysql_query("SELECT userid FROM cms_homes_stickers WHERE id = '".$widgetid."' LIMIT 1");
$row1 = mysql_fetch_assoc($sql);
$user = $row1['userid'];
$offset = $page - 1;
$offset = $offset * 20;
$sql = mysql_query("SELECT * FROM messenger_friendships WHERE userid = '".$user."' OR friendid = '".$user."' LIMIT 20 OFFSET ".$offset);
?>
<div class="avatar-widget-list-container">
<ul id="avatar-list-list" class="avatar-widget-list">
<?php
while($friendrow = mysql_fetch_assoc($sql)){
	if($friendrow['userid'] == $user){
	$friendid = $friendrow['friendid'];
	}else{
	$friendid = $friendrow['userid'];
	}
	$friend = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE id = '".$friendid."' LIMIT 1"));
	?>
	<li id="avatar-list-<?php echo $widgetid; ?>-<?php echo $friendid; ?>" title="<?php echo $friend['name']; ?>"><div class="avatar-list-open"><a href="#" id="avatar-list-open-link-<?php echo $widgetid; ?>-<?php echo $friendid; ?>" class="avatar-list-open-link"></a></div>
<div class="avatar-list-avatar"><img src="http://www.habbo.co.uk/habbo-imaging/avatarimage?figure=<?php echo $friend['figure']; ?>&size=s&direction=2&head_direction=2&gesture=sml" alt="" /></div>
<h4><a href="./user_profile.php?name=<?php echo $friend['name']; ?>"><?php echo $friend['name']; ?></a></h4>
<p class="avatar-list-birthday"><?php echo $friend['hbirth']; ?></p>
<p>

</p></li>

<?php } ?>
</ul>

<div id="avatar-list-info" class="avatar-list-info">
<div class="avatar-list-info-close-container"><a href="#" class="avatar-list-info-close"></a></div>
<div class="avatar-list-info-container"></div>
</div>

</div>

<div id="avatar-list-paging">
<?php
$sql = mysql_query("SELECT * FROM messenger_friendships WHERE userid = '".$user."' OR friendid = '".$user."'");
$count = mysql_num_rows($sql);
$at = $page - 1;
$at = $at * 20;
$at = $at + 1;
$to = $offset + 20;
if($to > $count){ $to = $count; }
$totalpages = ceil($count / 20);
?>
    <?php echo $at; ?> - <?php echo $to; ?> / <?php echo $count; ?>
    <br/>
	<?php if($page != 1){ ?>
    <a href="#" class="avatar-list-paging-link" id="avatarlist-search-first" >First</a> |
    <a href="#" class="avatar-list-paging-link" id="avatarlist-search-previous" >&lt;&lt;</a> |
	<?php }else{ ?>
	First |
    &lt;&lt; |
	<?php } ?>
	<?php if($page != $totalpages){ ?>
    <a href="#" class="avatar-list-paging-link" id="avatarlist-search-next" >&gt;&gt;</a> |
    <a href="#" class="avatar-list-paging-link" id="avatarlist-search-last" >Last</a>
	<?php }else{ ?>
	&gt;&gt; |
    Last
	<?php } ?>
<input type="hidden" id="pageNumber" value="<?php echo $page; ?>"/>
<input type="hidden" id="totalPages" value="<?php echo $totalpages; ?>"/>
</div>
<?php }else{
$sql = mysql_query("SELECT userid FROM cms_homes_stickers WHERE id = '".$widgetid."' LIMIT 1");
$row1 = mysql_fetch_assoc($sql);
$user = $row1['userid'];
$offset = $page - 1;
$offset = $offset * 10;
$sql = mysql_query("SELECT users.id,users.name,users.figure,users.hbirth FROM users,messenger_friendships WHERE messenger_friendships.userid = users.id AND messenger_friendships.friendid = '".$user."' AND users.name LIKE '%".$search."%' LIMIT 10 OFFSET ".$offset);
$sql2 = mysql_query("SELECT users.id,users.name,users.figure,users.hbirth FROM users,messenger_friendships WHERE messenger_friendships.friendid = users.id AND messenger_friendships.userid = '".$user."' AND users.name LIKE '%".$search."%' LIMIT 10 OFFSET ".$offset);
?>
<div class="avatar-widget-list-container">
<ul id="avatar-list-list" class="avatar-widget-list">
<?php
while($friendrow = mysql_fetch_assoc($sql)){
	?>
	<li id="avatar-list-<?php echo $widgetid; ?>-<?php echo $friendrow['id']; ?>" title="<?php echo $friendrow['name']; ?>"><div class="avatar-list-open"><a href="#" id="avatar-list-open-link-<?php echo $widgetid; ?>-<?php echo $friendrow; ?>" class="avatar-list-open-link"></a></div>
<div class="avatar-list-avatar"><img src="http://www.habbo.co.uk/habbo-imaging/avatarimage?figure=<?php echo $friendrow['figure']; ?>&size=s&direction=2&head_direction=2&gesture=sml" alt="" /></div>
<h4><a href="./user_profile.php?name=<?php echo $friendrow['name']; ?>"><?php echo $friendrow['name']; ?></a></h4>
<p class="avatar-list-birthday"><?php echo $friendrow['hbirth']; ?></p>
<p>

</p></li>

<?php }
while($friendrow = mysql_fetch_assoc($sql2)){
	?>
	<li id="avatar-list-<?php echo $widgetid; ?>-<?php echo $friendrow['id']; ?>" title="<?php echo $friendrow['name']; ?>"><div class="avatar-list-open"><a href="#" id="avatar-list-open-link-<?php echo $widgetid; ?>-<?php echo $friendrow; ?>" class="avatar-list-open-link"></a></div>
<div class="avatar-list-avatar"><img src="http://www.habbo.co.uk/habbo-imaging/avatarimage?figure=<?php echo $friendrow['figure']; ?>&size=s&direction=2&head_direction=2&gesture=sml" alt="" /></div>
<h4><a href="./user_profile.php?name=<?php echo $friendrow['name']; ?>"><?php echo $friendrow['name']; ?></a></h4>
<p class="avatar-list-birthday"><?php echo $friendrow['hbirth']; ?></p>
<p>

</p></li>

<?php } ?>
</ul>

<div id="avatar-list-info" class="avatar-list-info">
<div class="avatar-list-info-close-container"><a href="#" class="avatar-list-info-close"></a></div>
<div class="avatar-list-info-container"></div>
</div>

</div>

<div id="avatar-list-paging">
<?php
$count = mysql_num_rows($sql) + mysql_num_rows($sql2);
$offset = $offset * 2;
$at = $page - 1;
$at = $at * 20;
$at = $at + 1;
$to = $offset + 20;
if($to > $count){ $to = $count; }
$totalpages = ceil($count / 20);
?>
    <?php echo $at; ?> - <?php echo $to; ?> / <?php echo $count; ?>
    <br/>
	<?php if($page != 1){ ?>
    <a href="#" class="avatar-list-paging-link" id="avatarlist-search-first" >First</a> |
    <a href="#" class="avatar-list-paging-link" id="avatarlist-search-previous" >&lt;&lt;</a> |
	<?php }else{ ?>
	First |
    &lt;&lt; |
	<?php } ?>
	<?php if($page != $totalpages){ ?>
    <a href="#" class="avatar-list-paging-link" id="avatarlist-search-next" >&gt;&gt;</a> |
    <a href="#" class="avatar-list-paging-link" id="avatarlist-search-last" >Last</a>
	<?php }else{ ?>
	&gt;&gt; |
    Last
	<?php } ?>
<input type="hidden" id="pageNumber" value="<?php echo $page; ?>"/>
<input type="hidden" id="totalPages" value="<?php echo $totalpages; ?>"/>
</div>
<?php } ?>