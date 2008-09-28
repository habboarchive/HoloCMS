<?php
if($bypass1 == true){
$page = "1";
}else{
include('../core.php');
$page = $_POST['pageNumber'];
$widgetid = $_POST['widgetId'];
}

$sql = mysql_query("SELECT userid FROM cms_homes_stickers WHERE id = '".$widgetid."' LIMIT 1");
$rrow1 = mysql_fetch_assoc($sql);
$user = $rrow1['userid'];
$offset = $page - 1;
$offset = $offset * 16;
$sql = mysql_query("SELECT * FROM users_badges WHERE userid = '".$user."' ORDER BY iscurrent DESC LIMIT 16 OFFSET ".$offset);
?>
    <ul class="clearfix">
	<?php while($rrow = mysql_fetch_assoc($sql)){ ?>
            <li style="background-image: url(<?php echo $cimagesurl.$badgesurl.$rrow['badgeid'].".gif"; ?>)"></li>
    <?php } ?>
    </ul>

<div id="badge-list-paging">
<?php
$sql = mysql_query("SELECT * FROM users_badges WHERE userid = '".$user."' ORDER BY iscurrent DESC");
$count = mysql_num_rows($sql);
$offset = $offset * 2;
$at = $page - 1;
$at = $at * 16;
$at = $at + 1;
$to = $offset + 16;
if($to > $count){ $to = $count; }
$totalpages = ceil($count / 16);
?>
    <?php echo $at; ?> - <?php echo $to; ?> / <?php echo $count; ?>
    <br/>
	<?php if($page != 1){ ?>
    <a href="#" id="badge-list-search-first" >First</a> |
    <a href="#" id="badge-list-search-previous" >&lt;&lt;</a> |
	<?php }else{ ?>
	First |
    &lt;&lt; |
	<?php } ?>
	<?php if($page != $totalpages){ ?>
    <a href="#" id="badge-list-search-next" >&gt;&gt;</a> |
    <a href="#" id="badge-list-search-last" >Last</a>
	<?php }else{ ?>
	&gt;&gt; |
    Last
	<?php } ?>
<input type="hidden" id="badgeListPageNumber" value="<?php echo $page; ?>"/>
<input type="hidden" id="badgeListTotalPages" value="<?php echo $totalpages; ?>"/>
</div>