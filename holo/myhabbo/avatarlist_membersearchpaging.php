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
$sql = mysql_query("SELECT groupid FROM cms_homes_stickers WHERE id = '".$widgetid."' LIMIT 1");
$row1 = mysql_fetch_assoc($sql);
$groupid = $row1['groupid'];
$offset = $page - 1;
$offset = $offset * 20;
$sql = mysql_query("SELECT userid,is_current,member_rank FROM groups_memberships WHERE groupid = '".$groupid."' AND is_pending = '0' ORDER BY member_rank ASC LIMIT 20 OFFSET ".$offset);
?>
<div class="avatar-widget-list-container">
<ul id="avatar-list-list" class="avatar-widget-list">
<?php
while($membership = mysql_fetch_assoc($sql)){

	$userrow = mysql_query("SELECT id,name,figure,hbirth FROM users WHERE id = '".$membership['userid']."' LIMIT 1") or die(mysql_error());
	$found = mysql_num_rows($userrow);
	
	$groupdetails = mysql_fetch_assoc(mysql_query("SELECT * FROM groups_details WHERE id = '".$groupid."' LIMIT 1"));
	$ownerid = $groupdetails['ownerid'];

	if($found > 0){
		$userrow = mysql_fetch_assoc($userrow);

		echo "<li id=\"avatar-list-".$groupid."-".$userrow['id']."\" title=\"".$userrow['name']."\">
<div class=\"avatar-list-open\">
	<a href=\"#\" id=\"avatar-list-open-link-".$groupid."-".$userrow['id']."\" class=\"avatar-list-open-link\"></a>
</div>
<div class=\"avatar-list-avatar\">
	<img src=\"http://www.habbo.co.uk/habbo-imaging/avatarimage?figure=".$userrow['figure']."&size=s&direction=2&head_direction=2&gesture=sml\" alt=\"\" />
</div>
<h4>
	<a href=\"user_profile.php?name=".$userrow['name']."\">".$userrow['name']."</a>
</h4>
<p class=\"avatar-list-birthday\">
	".$userrow['hbirth']."
</p>
<p>";
if($userrow['id'] == $ownerid){
echo "<img src=\"./web-gallery/images/groups/owner_icon.gif\" alt=\"\" class=\"avatar-list-groupstatus\" />";
} elseif($membership['member_rank'] == "2") {
echo "<img src=\"./web-gallery/images/groups/administrator_icon.gif\" alt=\"\" class=\"avatar-list-groupstatus\" />";
}
if($membership['is_current'] == "1"){
echo "<img src=\"./web-gallery/images/groups/favourite_group_icon.gif\" alt=\"Favorite\" class=\"avatar-list-groupstatus\" />";
}
echo "</p>
</li>";
	}
} ?>
</ul>

<div id="avatar-list-info" class="avatar-list-info">
<div class="avatar-list-info-close-container"><a href="#" class="avatar-list-info-close"></a></div>
<div class="avatar-list-info-container"></div>
</div>

</div>

<div id="avatar-list-paging">
<?php
$sql = mysql_query("SELECT * FROM groups_memberships WHERE groupid = '".$groupid."' AND is_pending = '0'");
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
$sql = mysql_query("SELECT groupid FROM cms_homes_stickers WHERE id = '".$widgetid."' LIMIT 1");
$row1 = mysql_fetch_assoc($sql);
$groupid = $row1['groupid'];
$offset = $page - 1;
$offset = $offset * 20;
$sql = mysql_query("SELECT groups_memberships.userid,groups_memberships.is_current,groups_memberships.member_rank,users.name FROM groups_memberships,users WHERE groups_memberships.userid = users.id AND groupid = '44' AND is_pending = '0' AND name LIKE '%".$search."%' ORDER BY member_rank ASC LIMIT 20 OFFSET ".$offset);
?>
<div class="avatar-widget-list-container">
<ul id="avatar-list-list" class="avatar-widget-list">
<?php
while($membership = mysql_fetch_assoc($sql)){

	$userrow = mysql_query("SELECT id,name,figure,hbirth FROM users WHERE id = '".$membership['userid']."' LIMIT 1") or die(mysql_error());
	$found = mysql_num_rows($userrow);
	
	$groupdetails = mysql_fetch_assoc(mysql_query("SELECT * FROM groups_details WHERE id = '".$groupid."' LIMIT 1"));
	$ownerid = $groupdetails['ownerid'];

	if($found > 0){
		$userrow = mysql_fetch_assoc($userrow);

		echo "<li id=\"avatar-list-".$groupid."-".$userrow['id']."\" title=\"".$userrow['name']."\">
<div class=\"avatar-list-open\">
	<a href=\"#\" id=\"avatar-list-open-link-".$groupid."-".$userrow['id']."\" class=\"avatar-list-open-link\"></a>
</div>
<div class=\"avatar-list-avatar\">
	<img src=\"http://www.habbo.co.uk/habbo-imaging/avatarimage?figure=".$userrow['figure']."&size=s&direction=2&head_direction=2&gesture=sml\" alt=\"\" />
</div>
<h4>
	<a href=\"user_profile.php?name=".$userrow['name']."\">".$userrow['name']."</a>
</h4>
<p class=\"avatar-list-birthday\">
	".$userrow['hbirth']."
</p>
<p>";
if($userrow['id'] == $ownerid){
echo "<img src=\"./web-gallery/images/groups/owner_icon.gif\" alt=\"\" class=\"avatar-list-groupstatus\" />";
} elseif($membership['member_rank'] == "2") {
echo "<img src=\"./web-gallery/images/groups/administrator_icon.gif\" alt=\"\" class=\"avatar-list-groupstatus\" />";
}
if($membership['is_current'] == "1"){
echo "<img src=\"./web-gallery/images/groups/favourite_group_icon.gif\" alt=\"Favorite\" class=\"avatar-list-groupstatus\" />";
}
echo "</p>
</li>";
	}
} ?>
</ul>

<div id="avatar-list-info" class="avatar-list-info">
<div class="avatar-list-info-close-container"><a href="#" class="avatar-list-info-close"></a></div>
<div class="avatar-list-info-container"></div>
</div>

</div>

<div id="avatar-list-paging">
<?php
$sql = mysql_query("SELECT groups_memberships.userid,groups_memberships.is_current,groups_memberships.member_rank,users.name FROM groups_memberships,users WHERE groups_memberships.userid = users.id AND groupid = '44' AND is_pending = '0' AND name LIKE '%".$search."%'");
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
<?php } ?>