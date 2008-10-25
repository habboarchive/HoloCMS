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

$allow_guests = true;

include('core.php');
include('includes/session.php');

if(HoloText(getContent('forum-enabled'), true) !== "1"){ header("Location: index.php"); exit; }

$pagename = "Discussion Board";
$pageid = "forum";
$body_id = "viewmode";

if(isset($_POST['searchString'])){
	$searchString = FilterText($_POST['searchString']);
	$check = mysql_query("SELECT id FROM groups_details WHERE name LIKE '".$searchString."' LIMIT 1") or die(mysql_error());
	$found = mysql_num_rows($check);
	if($found > 0){
		$tmp = mysql_fetch_assoc($check);
		header("Location: group_profile.php?id=" . $tmp['id']);
		exit;
	}
}


if(isset($_GET['id']) && is_numeric($_GET['id'])){

	$check = mysql_query("SELECT * FROM groups_details WHERE id = '".$_GET['id']."' LIMIT 1");
	$exists = mysql_num_rows($check);

	if($exists > 0){

		$groupid = $_GET['id'];

		$error = false;
		$groupdata = mysql_fetch_assoc($check);

		$pagename = $groupdata['name'];
		$ownerid = $groupdata['ownerid'];

		$members = mysql_evaluate("SELECT COUNT(*) FROM groups_memberships WHERE groupid = '".$groupid."' AND is_pending = '0'");

		$check = mysql_query("SELECT * FROM groups_memberships WHERE userid = '".$my_id."' AND groupid = '".$groupid."' AND is_pending = '0' LIMIT 1");
		$is_member = mysql_num_rows($check);

		if($is_member > 0 && $logged_in){

			$is_member = true;
			$my_membership = mysql_fetch_assoc($check);
			$member_rank = $my_membership['member_rank'];

		} else {

			$is_member = false;

		}

	} else {

		$error = true;

	}

} else {

	$error = true;

}

if($error != true){
include('templates/community/subheader.php');
include('templates/community/header.php');
mysql_query("UPDATE groups_details SET views = views+'1' WHERE id='".$groupid."' LIMIT 1");

$viewtools = "	<div class=\"myhabbo-view-tools\">\n";

$page = $_GET['page'];

$threads = mysql_evaluate("SELECT COUNT(*) FROM cms_forum_threads WHERE forumid='".$groupid."'");
$pages = ceil(($threads + 0) / 10);

if($page > $pages || $page < 1){
	$page = 1;
}

$key = 0;

mysql_query("UPDATE groups_details SET views = views+'1' WHERE id='".$groupid."' LIMIT 1");

?>

<div id="container">
	<div id="content" style="position: relative" class="clearfix">
    <div id="mypage-wrapper" class="cbb blue">
<div class="box-tabs-container box-tabs-left clearfix">
	<?php if($member_rank > 1 && !$edit_mode){ ?><a href="group_profile.php?id=<?php echo $groupid; ?>&do=edit" class="new-button dark-button edit-icon" style="float:left"><b><span></span>Edit</b><i></i></a><?php } ?>
    <h2 class="page-owner">
<?php echo HoloText($groupdata['name']); ?>&nbsp;
<?php if($groupdata['type'] == "2"){ ?><img src='./web-gallery/images/status_closed_big.gif' alt='Closed Group' title='Closed Group'><?php } ?>
<?php if($groupdata['type'] == "1"){ ?><img src='./web-gallery/images/status_exclusive_big.gif' alt='Moderated Group' title='Moderated Group'><?php } ?></h2>
</h2>
    <ul class="box-tabs">
        <li><a href="group_profile.php?id=<?php echo $groupid; ?>">Front Page</a><span class="tab-spacer"></span></li>
        <li class="selected"><a href="group_forum.php?id=<?php echo $groupid; ?>">Discussion Forum <?php if($groupdata['pane'] > 0) { ?><img src="http://images.habbohotel.nl/habboweb/23_deebb3529e0d9d4e847a31e5f6fb4c5b/9/web-gallery/images/grouptabs/privatekey.png"><?php } ?></a><span class="tab-spacer"></span></li>
<?php $viewtools = "	<div class=\"myhabbo-view-tools\">\n";

if($logged_in && !$is_member && $groupdata['type'] !== "2" && $my_membership['is_pending'] !== "1"){ $viewtools = $viewtools . "<a href=\"joingroup.php?groupId=".$groupid."\" id=\"join-group-button\">"; if($groupdata['type'] == "0" || $groupdata['type'] == "3"){ $viewtools = $viewtools . "Join"; } else { $viewtools = $viewtools . "Request membership"; } $viewtools = $viewtools . "</a>"; }
if($logged_in && $my_membership['is_current'] !== "1" && $is_member){ $viewtools = $viewtools . "<a href=\"#\" id=\"select-favorite-button\">Make favourite</a>\n"; }
if($logged_in && $my_membership['is_current'] == "1" && $is_member){ $viewtools = $viewtools . "<a href=\"#\" id=\"deselect-favorite-button\">Remove favourite</a>"; }
if($logged_in && $is_member && $my_id !== $ownerid){ $viewtools = $viewtools . "<a href=\"leavegroup.php?groupId=".$groupid."\" id=\"leave-group-button\">Leave group</a>\n"; }

$viewtools = $viewtools . "	</div>\n"; ?>
    </ul>
</div>	
	<div id="mypage-content">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="content-1col">
            <tr>
                <td valign="top" style="width: 750px;" class="habboPage-col rightmost">
                    <div id="discussionbox">
					<?php
					if($groupdata['pane'] > 0) {
					$sql = mysql_query("SELECT * FROM groups_memberships WHERE userid = '".$my_id."' AND is_pending <> '1' AND groupid='".$_GET['id']."'");
					$member = mysql_fetch_assoc($sql);
					if(mysql_num_rows($sql) > 0) { ?>
<div id="group-topiclist-container">
<div class="topiclist-header clearfix">
		
		<?php
		$sql = mysql_query("SELECT * FROM groups_details WHERE id='".$_GET['id']."' LIMIT 1");
		$row = mysql_fetch_assoc($sql);

		if($row['topics'] == 0) { ?>
		
        <input type="hidden" id="email-verfication-ok" value="1"/>
        <?php if($logged_in){ ?><a href="#" id="newtopic-upper" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>New Thread</b><i></i></a><?php } else { echo "You must be logged in to reply or post new threads."; }
		}elseif($row['topics'] == 1) {
		$check = mysql_query("SELECT * FROM groups_memberships WHERE userid='".$my_id."' AND groupid='".$_GET['id']."' AND is_pending <> '1' LIMIT 1");
		if(mysql_num_rows($check) > 0) { ?>
        <input type="hidden" id="email-verfication-ok" value="1"/>
        <?php if($logged_in){ ?><a href="#" id="newtopic-upper" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>New Thread</b><i></i></a><?php } else { echo "You must be logged in to reply or post new threads."; }
		}
	}elseif($row['topics'] == 2) {
	$check = mysql_query("SELECT * FROM groups_memberships WHERE userid='".$my_id."' AND groupid='".$_GET['id']."' AND member_rank='2' AND is_pending <> '1' LIMIT 1");
		if(mysql_num_rows($check) > 0) { ?>
        <input type="hidden" id="email-verfication-ok" value="1"/>
        <?php if($logged_in){ ?><a href="#" id="newtopic-upper" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>New Thread</b><i></i></a><?php } else { echo "You must be logged in to reply or post new threads."; }
	}
}
?>
		
    <div class="page-num-list">
    View page:
<?php
	for ($i = 1; $i <= $pages; $i++){
		if($page == $i){
			echo $i . "\n";
		} else {
			echo "<a href=\"forum.php?page=" . $i . "\" class=\"topiclist-page-link\">" . $i . "</a>\n";
		}
	} 
?>
    </div>
</div>
<table class="group-topiclist" border="0" cellpadding="0" cellspacing="0" id="group-topiclist-list">
	<tr class="topiclist-columncaption">
		<td class="topiclist-columncaption-topic">Topic</td>
		<td class="topiclist-columncaption-lastpost">Last post</td>
		<td class="topiclist-columncaption-replies">Replies</td>
		<td class="topiclist-columncaption-views">Views</td>
	</tr>
	
<?php

if($threads == 0){
echo "	<tr class=\"topiclist-row-1\">
		<td class=\"topiclist-rowtopic\" valign=\"top\">
			No threads to display.
		</td>
		</tr>";
}

$sql = mysql_query("SELECT * FROM cms_forum_threads WHERE type > 2 AND forumid='".$groupid."' ORDER BY unix DESC") or die(mysql_error());
$stickies = mysql_num_rows($sql);

$query_min = ($page * 10) - 10;
$query_max = 10;

$query_max = $query_max - $stickies;
$query_min = $query_min - $stickies;

if($query_min < 0){ // Page 1
$query_min = 0;
}

while($row = mysql_fetch_assoc($sql)){

	$key++;

	if(IsEven($key)){
		$x = "odd";
	} else {
		$x = "even";
	}

	echo "<tr class=\"topiclist-row-" . $x . "\">
		<td class=\"topiclist-rowtopic\" valign=\"top\">
			<div class=\"topiclist-row-content\">
			<a class=\"topiclist-link icon icon-sticky\" href=\"viewthread.php?thread=".$row['id']."\">".HoloText($row['title'])."</a>";

			if($row['type'] == 4){
			echo "&nbsp;<span class=\"topiclist-row-topicsticky\"><img src=\"./web-gallery/images/groups/status_closed.gif\" title=\"Closed Thread\" alt=\"Closed Thread\"></span>";
			}

			echo "&nbsp;(page ";

			$thread_pages = ceil(($row['posts'] + 1) / 10);

			for ($i = 1; $i <= $thread_pages; $i++){
				echo "<a href=\"viewthread.php?thread=" . $row['id'] . "&page=" . $i . "\" class=\"topiclist-page-link\">" . $i . "</a>\n";
			} 

            echo ")
			<br />
			<span><a class=\"topiclist-row-openername\" href=\"user_profile.php?name=" . $row['author'] . "\">" . $row['author'] . "</a></span>";

			$date_bits = explode(" ", $row['date']);
			$date = $date_bits[0];
			$time = $date_bits[1];
			
				echo "&nbsp;<span class=\"latestpost\">" . $date . "</span>
			<span class=\"latestpost\">(" . $time . ")</span>
			</div>
		</td>
		<td class=\"topiclist-lastpost\" valign=\"top\">
		    <a class=\"lastpost-page-link\" href=\"viewthread.php?thread=" . $row['id'] . "&sp=JumpToLast\">";

			$date_bits = explode(" ", $row['lastpost_date']);
			$date = $date_bits[0];
			$time = $date_bits[1];

				echo "<span class=\"lastpost\">" . $date . "</span>
            <span class=\"lastpost\">(" . $time . ")</span></a><br />
			<span class=\"topiclist-row-writtenby\">by:</span> <a class=\"topiclist-row-openername\" href=\"user_profile.php?name=" . $row['lastpost_author'] . "\">" . $row['lastpost_author'] . "</a>&nbsp;
		</td>
 		<td class=\"topiclist-replies\" valign=\"top\">" . $row['posts'] . "</td>
 		<td class=\"topiclist-views\" valign=\"top\">" . $row['views'] . "</td>
	</tr>";

}

$sql = mysql_query("SELECT * FROM cms_forum_threads WHERE type < 3 AND forumid='".$groupid."' ORDER BY unix DESC LIMIT ".$query_min.", ".$query_max."") or die(mysql_error());

while($row = mysql_fetch_assoc($sql)){

	$key++;

	if(IsEven($key)){
		$x = "odd";
	} else {
		$x = "even";
	}

	echo "<tr class=\"topiclist-row-" . $x . "\">
		<td class=\"topiclist-rowtopic\" valign=\"top\">
			<div class=\"topiclist-row-content\">
			<a class=\"topiclist-link \" href=\"viewthread.php?thread=".$row['id']."\">".HoloText($row['title'])."</a>";

			if($row['type'] == 2){
			echo "&nbsp;<span class=\"topiclist-row-topicsticky\"><img src=\"./web-gallery/images/groups/status_closed.gif\" title=\"Closed Thread\" alt=\"Closed Thread\"></span>";
			}

			echo "&nbsp;(page ";

			$thread_pages = ceil(($row['posts'] + 1) / 10);

			for ($i = 1; $i <= $thread_pages; $i++){
				echo "<a href=\"viewthread.php?thread=" . $row['id'] . "&page=" . $i . "\" class=\"topiclist-page-link\">" . $i . "</a>\n";
			} 

            echo ")
			<br />
			<span><a class=\"topiclist-row-openername\" href=\"user_profile.php?name=" . $row['author'] . "\">" . $row['author'] . "</a></span>";

			$date_bits = explode(" ", $row['date']);
			$date = $date_bits[0];
			$time = $date_bits[1];
			
				echo "&nbsp;<span class=\"latestpost\">" . $date . "</span>
			<span class=\"latestpost\">(" . $time . ")</span>
			</div>
		</td>
		<td class=\"topiclist-lastpost\" valign=\"top\">
		    <a class=\"lastpost-page-link\" href=\"viewthread.php?thread=" . $row['id'] . "&sp=JumpToLast\">";

			$date_bits = explode(" ", $row['lastpost_date']);
			$date = $date_bits[0];
			$time = $date_bits[1];

				echo "<span class=\"lastpost\">" . $date . "</span>
            <span class=\"lastpost\">(" . $time . ")</span></a><br />
			<span class=\"topiclist-row-writtenby\">by:</span> <a class=\"topiclist-row-openername\" href=\"user_profile.php?name=" . $row['lastpost_author'] . "\">" . $row['lastpost_author'] . "</a>&nbsp;
		</td>
 		<td class=\"topiclist-replies\" valign=\"top\">" . $row['posts'] . "</td>
 		<td class=\"topiclist-views\" valign=\"top\">" . $row['views'] . "</td>
	</tr>";

}

?>	

	</table>
<div class="topiclist-footer clearfix">
		<?php
		$sql = mysql_query("SELECT * FROM groups_details WHERE id='".$_GET['id']."' LIMIT 1");
		$row = mysql_fetch_assoc($sql);

		if($row['topics'] == 0) { ?>
		
        <input type="hidden" id="email-verfication-ok" value="1"/>
        <?php if($logged_in){ ?><a href="#" id="newtopic-upper" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>New Thread</b><i></i></a><?php } else { echo "You must be logged in to reply or post new threads."; }
		}elseif($row['topics'] == 1) {
		$check = mysql_query("SELECT * FROM groups_memberships WHERE userid='".$my_id."' AND groupid='".$_GET['id']."' LIMIT 1");
		if(mysql_num_rows($check) > 0) { ?>
        <input type="hidden" id="email-verfication-ok" value="1"/>
        <?php if($logged_in){ ?><a href="#" id="newtopic-upper" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>New Thread</b><i></i></a><?php } else { echo "You must be logged in to reply or post new threads."; }
		}
	}elseif($row['topics'] == 2) {
	$check = mysql_query("SELECT * FROM groups_memberships WHERE userid='".$my_id."' AND groupid='".$_GET['id']."' AND member_rank='2' LIMIT 1");
		if(mysql_num_rows($check) > 0) { ?>
        <input type="hidden" id="email-verfication-ok" value="1"/>
        <?php if($logged_in){ ?><a href="#" id="newtopic-upper" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>New Thread</b><i></i></a><?php } else { echo "You must be logged in to reply or post new threads."; }
	}
}
?>
    <div class="page-num-list">
    View page:
<?php
	for ($i = 1; $i <= $pages; $i++){
		if($page == $i){
			echo $i . "\n";
		} else {
			echo "<a href=\"forum.php?page=" . $i . "\" class=\"topiclist-page-link\">" . $i . "</a>\n";
		}
	}
?>
    </div>
<?php }else{ ?>
<h1>Oops...</h1>

<p>
        You can't acces this discussion forum, you need to be a member! <br />

</p>
<?php }
	}else{ ?>
<div id="group-topiclist-container">
<div class="topiclist-header clearfix">
        <input type="hidden" id="email-verfication-ok" value="1"/>
		<?php
		$sql = mysql_query("SELECT * FROM groups_details WHERE id='".$_GET['id']."' LIMIT 1");
		$row = mysql_fetch_assoc($sql);

		if($row['topics'] == 0) { ?>
		
        <input type="hidden" id="email-verfication-ok" value="1"/>
        <?php if($logged_in){ ?><a href="#" id="newtopic-upper" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>New Thread</b><i></i></a><?php } else { echo "You must be logged in to reply or post new threads."; }
		}elseif($row['topics'] == 1) {
		$check = mysql_query("SELECT * FROM groups_memberships WHERE userid='".$my_id."' AND groupid='".$_GET['id']."' LIMIT 1");
		if(mysql_num_rows($check) > 0) { ?>
        <input type="hidden" id="email-verfication-ok" value="1"/>
        <?php if($logged_in){ ?><a href="#" id="newtopic-upper" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>New Thread</b><i></i></a><?php } else { echo "You must be logged in to reply or post new threads."; }
		}
	}elseif($row['topics'] == 2) {
	$check = mysql_query("SELECT * FROM groups_memberships WHERE userid='".$my_id."' AND groupid='".$_GET['id']."' AND member_rank='2' LIMIT 1");
		if(mysql_num_rows($check) > 0) { ?>
        <input type="hidden" id="email-verfication-ok" value="1"/>
        <?php if($logged_in){ ?><a href="#" id="newtopic-upper" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>New Thread</b><i></i></a><?php } else { echo "You must be logged in to reply or post new threads."; }
	}
}
?>
    <div class="page-num-list">
    View page:
<?php
	for ($i = 1; $i <= $pages; $i++){
		if($page == $i){
			echo $i . "\n";
		} else {
			echo "<a href=\"forum.php?page=" . $i . "\" class=\"topiclist-page-link\">" . $i . "</a>\n";
		}
	} 
?>
    </div>
</div>
<table class="group-topiclist" border="0" cellpadding="0" cellspacing="0" id="group-topiclist-list">
	<tr class="topiclist-columncaption">
		<td class="topiclist-columncaption-topic">Topic</td>
		<td class="topiclist-columncaption-lastpost">Last post</td>
		<td class="topiclist-columncaption-replies">Replies</td>
		<td class="topiclist-columncaption-views">Views</td>
	</tr>
	
<?php

if($threads == 0){
echo "	<tr class=\"topiclist-row-1\">
		<td class=\"topiclist-rowtopic\" valign=\"top\">
			No threads to display.
		</td>
		</tr>";
}

$sql = mysql_query("SELECT * FROM cms_forum_threads WHERE type > 2 AND forumid='".$groupid."' ORDER BY unix DESC") or die(mysql_error());
$stickies = mysql_num_rows($sql);

$query_min = ($page * 10) - 10;
$query_max = 10;

$query_max = $query_max - $stickies;
$query_min = $query_min - $stickies;

if($query_min < 0){ // Page 1
$query_min = 0;
}

while($row = mysql_fetch_assoc($sql)){

	$key++;

	if(IsEven($key)){
		$x = "odd";
	} else {
		$x = "even";
	}

	echo "<tr class=\"topiclist-row-" . $x . "\">
		<td class=\"topiclist-rowtopic\" valign=\"top\">
			<div class=\"topiclist-row-content\">
			<a class=\"topiclist-link icon icon-sticky\" href=\"viewthread.php?thread=".$row['id']."\">".HoloText($row['title'])."</a>";

			if($row['type'] == 4){
			echo "&nbsp;<span class=\"topiclist-row-topicsticky\"><img src=\"./web-gallery/images/groups/status_closed.gif\" title=\"Closed Thread\" alt=\"Closed Thread\"></span>";
			}

			echo "&nbsp;(page ";

			$thread_pages = ceil(($row['posts'] + 1) / 10);

			for ($i = 1; $i <= $thread_pages; $i++){
				echo "<a href=\"viewthread.php?thread=" . $row['id'] . "&page=" . $i . "\" class=\"topiclist-page-link\">" . $i . "</a>\n";
			} 

            echo ")
			<br />
			<span><a class=\"topiclist-row-openername\" href=\"user_profile.php?name=" . $row['author'] . "\">" . $row['author'] . "</a></span>";

			$date_bits = explode(" ", $row['date']);
			$date = $date_bits[0];
			$time = $date_bits[1];
			
				echo "&nbsp;<span class=\"latestpost\">" . $date . "</span>
			<span class=\"latestpost\">(" . $time . ")</span>
			</div>
		</td>
		<td class=\"topiclist-lastpost\" valign=\"top\">
		    <a class=\"lastpost-page-link\" href=\"viewthread.php?thread=" . $row['id'] . "&sp=JumpToLast\">";

			$date_bits = explode(" ", $row['lastpost_date']);
			$date = $date_bits[0];
			$time = $date_bits[1];

				echo "<span class=\"lastpost\">" . $date . "</span>
            <span class=\"lastpost\">(" . $time . ")</span></a><br />
			<span class=\"topiclist-row-writtenby\">by:</span> <a class=\"topiclist-row-openername\" href=\"user_profile.php?name=" . $row['lastpost_author'] . "\">" . $row['lastpost_author'] . "</a>&nbsp;
		</td>
 		<td class=\"topiclist-replies\" valign=\"top\">" . $row['posts'] . "</td>
 		<td class=\"topiclist-views\" valign=\"top\">" . $row['views'] . "</td>
	</tr>";

}

$sql = mysql_query("SELECT * FROM cms_forum_threads WHERE type < 3 AND forumid='".$groupid."' ORDER BY unix DESC LIMIT ".$query_min.", ".$query_max."") or die(mysql_error());

while($row = mysql_fetch_assoc($sql)){

	$key++;

	if(IsEven($key)){
		$x = "odd";
	} else {
		$x = "even";
	}

	echo "<tr class=\"topiclist-row-" . $x . "\">
		<td class=\"topiclist-rowtopic\" valign=\"top\">
			<div class=\"topiclist-row-content\">
			<a class=\"topiclist-link \" href=\"viewthread.php?thread=".$row['id']."\">".HoloText($row['title'])."</a>";

			if($row['type'] == 2){
			echo "&nbsp;<span class=\"topiclist-row-topicsticky\"><img src=\"./web-gallery/images/groups/status_closed.gif\" title=\"Closed Thread\" alt=\"Closed Thread\"></span>";
			}

			echo "&nbsp;(page ";

			$thread_pages = ceil(($row['posts'] + 1) / 10);

			for ($i = 1; $i <= $thread_pages; $i++){
				echo "<a href=\"viewthread.php?thread=" . $row['id'] . "&page=" . $i . "\" class=\"topiclist-page-link\">" . $i . "</a>\n";
			} 

            echo ")
			<br />
			<span><a class=\"topiclist-row-openername\" href=\"user_profile.php?name=" . $row['author'] . "\">" . $row['author'] . "</a></span>";

			$date_bits = explode(" ", $row['date']);
			$date = $date_bits[0];
			$time = $date_bits[1];
			
				echo "&nbsp;<span class=\"latestpost\">" . $date . "</span>
			<span class=\"latestpost\">(" . $time . ")</span>
			</div>
		</td>
		<td class=\"topiclist-lastpost\" valign=\"top\">
		    <a class=\"lastpost-page-link\" href=\"viewthread.php?thread=" . $row['id'] . "&sp=JumpToLast\">";

			$date_bits = explode(" ", $row['lastpost_date']);
			$date = $date_bits[0];
			$time = $date_bits[1];

				echo "<span class=\"lastpost\">" . $date . "</span>
            <span class=\"lastpost\">(" . $time . ")</span></a><br />
			<span class=\"topiclist-row-writtenby\">by:</span> <a class=\"topiclist-row-openername\" href=\"user_profile.php?name=" . $row['lastpost_author'] . "\">" . $row['lastpost_author'] . "</a>&nbsp;
		</td>
 		<td class=\"topiclist-replies\" valign=\"top\">" . $row['posts'] . "</td>
 		<td class=\"topiclist-views\" valign=\"top\">" . $row['views'] . "</td>
	</tr>";

}

?>	

	</table>
<div class="topiclist-footer clearfix">
		<?php
		$sql = mysql_query("SELECT * FROM groups_details WHERE id='".$_GET['id']."' LIMIT 1");
		$row = mysql_fetch_assoc($sql);

		if($row['topics'] == 0) { ?>
		
        <input type="hidden" id="email-verfication-ok" value="1"/>
        <?php if($logged_in){ ?><a href="#" id="newtopic-upper" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>New Thread</b><i></i></a><?php } else { echo "You must be logged in to reply or post new threads."; }
		}elseif($row['topics'] == 1) {
		$check = mysql_query("SELECT * FROM groups_memberships WHERE userid='".$my_id."' AND groupid='".$_GET['id']."' AND is_pending <> '1' LIMIT 1");
		if(mysql_num_rows($check) > 0) { ?>
        <input type="hidden" id="email-verfication-ok" value="1"/>
        <?php if($logged_in){ ?><a href="#" id="newtopic-upper" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>New Thread</b><i></i></a><?php } else { echo "You must be logged in to reply or post new threads."; }
		}
	}elseif($row['topics'] == 2) {
	$check = mysql_query("SELECT * FROM groups_memberships WHERE userid='".$my_id."' AND groupid='".$_GET['id']."' AND member_rank='2' AND is_pending <> '1' LIMIT 1");
		if(mysql_num_rows($check) > 0) { ?>
        <input type="hidden" id="email-verfication-ok" value="1"/>
        <?php if($logged_in){ ?><a href="#" id="newtopic-upper" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>New Thread</b><i></i></a><?php } else { echo "You must be logged in to reply or post new threads."; }
	}
}
?>
    <div class="page-num-list">
    View page:
<?php
	for ($i = 1; $i <= $pages; $i++){
		if($page == $i){
			echo $i . "\n";
		} else {
			echo "<a href=\"forum.php?page=" . $i . "\" class=\"topiclist-page-link\">" . $i . "</a>\n";
		}
	}
?>
    </div>
<?php }
?>
</div>
</div>

<script type="text/javascript" language="JavaScript">
L10N.put("myhabbo.discussion.error.topic_name_empty", "Topic title may not be empty");
Discussions.initialize("<?php echo $_GET['id']; ?>", "forum.php", null);
</script>
                    </div>
					
                </td>
                <td style="width: 4px;"></td>
                <td valign="top" style="width: 164px;">
    <div class="habblet ">
    
    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

<script type="text/javascript">
	Event.observe(window, "load", observeAnim);
	document.observe("dom:loaded", initDraggableDialogs);
</script>
    </div>
<div id="footer">
	<p><a href="index.php" target="_self">Homepage</a> | <a href="./disclaimer.php" target="_self">Terms of Service</a> | <a href="./privacy.php" target="_self">Privacy Policy</a></p>
	<?php /*@@* DO NOT EDIT OR REMOVE THE LINE BELOW WHATSOEVER! *@@*/ ?>
	<p>Powered by HoloCMS &copy 2008 Meth0d.<br />HABBO is a registered trademark of Sulake Corporation. All rights reserved to their respective owner(s).</p>
	<?php /*@@* DO NOT EDIT OR REMOVE THE LINE ABOVE WHATSOEVER! *@@*/ ?>
</div></div>

</div>

<div class="cbb topdialog black" id="dialog-group-settings">
	
	<div class="box-tabs-container">
<ul class="box-tabs">
	<li class="selected" id="group-settings-link-group"><a href="#">Groepsinstellingen</a><span class="tab-spacer"></span></li>
	<li id="group-settings-link-forum"><a href="#">Foruminstellingen</a><span class="tab-spacer"></span></li>
	<li id="group-settings-link-room"><a href="#">Kamersettings</a><span class="tab-spacer"></span></li>
</ul>
</div>

	<a class="topdialog-exit" href="#" id="dialog-group-settings-exit">X</a>
	<div class="topdialog-body" id="dialog-group-settings-body">
<p style="text-align:center"><img src="http://images.habbohotel.nl/habboweb/21_5527e6590eba8f3fb66348bdf271b5a2/17/web-gallery/images/progress_bubbles.gif" alt="" width="29" height="6" /></p>
	</div>
</div>	

<script language="JavaScript" type="text/javascript">
Event.observe("dialog-group-settings-exit", "click", function(e) {
    Event.stop(e);
    closeGroupSettings();
}, false);
</script><div class="cbb topdialog" id="postentry-verifyemail-dialog">
	<h2 class="title dialog-handle">Bevestig e-mailadres</h2>
	
	<a class="topdialog-exit" href="#" id="postentry-verifyemail-dialog-exit">X</a>
	<div class="topdialog-body" id="postentry-verifyemail-dialog-body">
	<p>Je moet je mailadres invullen voor je een reactie kunt plaatsen.</p>
	<p><a href="/profile?tab=3">Activeer je mailadres</a></p>
	<p class="clearfix">
		<a href="#" id="postentry-verifyemail-ok" class="new-button"><b>OK</b><i></i></a>
	</p>
	</div>
</div>	
					
<script type="text/javascript">
HabboView.run();
</script>

</body>
</html>
<?php
} else {
$pagename = "Page not found";
include('templates/community/subheader.php');
include('templates/community/header.php');
?>



<div id="container">
	<div id="content" style="position: relative" class="clearfix">
    <div id="column1" class="column">
				<div class="habblet-container ">		
						<div class="cbb clearfix red ">
	
							<h2 class="title">Page not found!
							</h2>
						<div id="notfound-content" class="box-content">
    <p class="error-text">Sorry, but the page you were looking for was not found.</p> <img id="error-image" src="./web-gallery/v2/images/error.gif" />
    <p class="error-text">Please use the 'Back' button to get back to where you started.</p>
    <p class="error-text"><b>Search for group</b></p>
    <?php if(isset($searchString)){ echo "<p class=\"error-text\">Sorry, but no results were found for <strong>'".$searchString."'.</strong></p>"; } ?>
    <p class="error-text">
	<form method='post'>
		Group Name:<br />
		<input type='text' name='searchString' maxlength='25' size='25' value='<?php echo $_POST['searchString']; ?>'>
		<input type='submit' class='submit' value='Submit'>
	</form>
    </p>
</div>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
			 
</div>
<div id="column2" class="column">
				<div class="habblet-container ">		
						<div class="cbb clearfix green ">
	
							<h2 class="title">Were you looking for...
							</h2>
						<div id="notfound-looking-for" class="box-content">
    <p><b>A friend's group or personal page?</b><br/>
    See if it is listed on the <a href="community.php">Community</a> page.</p>

    <p><b>Rooms that rock?</b><br/>
    Browse the <a href="community.php">Recommended Rooms</a> list.</p>

    <p><b>What other users are in to?</b><br/>
    Check out the <a href="tags.php">Top Tags</a> list.</p>

     <p><b>How to get Credits?</b><br/>
    Have a look at the <a href="credits.php">Credits</a> page.</p>
</div>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
			 
</div>



<?php
include('templates/community/footer.php');
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>