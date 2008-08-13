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

if(getContent('forum-enabled') !== "1"){ header("Location: index.php"); exit; }

$pagename = "Discussion Board";
$pageid = "forum";
$body_id = "viewmode";

include('templates/community/subheader.php');
include('templates/community/header.php');

$page = $_GET['page'];

$threads = mysql_evaluate("SELECT COUNT(*) FROM cms_forum_threads WHERE forumid='0'");
$pages = ceil(($threads + 0) / 10);

if($page > $pages || $page < 1){
	$page = 1;
}

$key = 0;

?>

<div id="container">
	<div id="content" style="position: relative" class="clearfix">
    <div id="mypage-wrapper" class="cbb blue">
<div class="box-tabs-container box-tabs-left clearfix">
	<div class="myhabbo-view-tools">
	</div>
    <h2 class="page-owner">
    	Discussion Board
    </h2>
    <ul class="box-tabs">
        <li class="selected"><a href="forum.php">Discussion Board</a><span class="tab-spacer"></span></li>
    </ul>
</div>
	<div id="mypage-content">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="content-1col">
            <tr>
                <td valign="top" style="width: 750px;" class="habboPage-col rightmost">
                    <div id="discussionbox">
<div id="group-topiclist-container">
<div class="topiclist-header clearfix">
        <input type="hidden" id="email-verfication-ok" value="1"/>
        <?php if($logged_in){ ?><a href="#" id="newtopic-upper" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>New Thread</b><i></i></a><?php } ?>
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

$sql = mysql_query("SELECT * FROM cms_forum_threads WHERE type > 2 AND forumid='0' ORDER BY unix DESC") or die(mysql_error());
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
			<a class=\"topiclist-link icon icon-sticky\" href=\"viewthread.php?thread=".$row['id']."\">".stripslashes($row['title'])."</a>";

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

$sql = mysql_query("SELECT * FROM cms_forum_threads WHERE type < 3 AND forumid='0' ORDER BY unix DESC LIMIT ".$query_min.", ".$query_max."") or die(mysql_error());

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
			<a class=\"topiclist-link \" href=\"viewthread.php?thread=".$row['id']."\">".stripslashes($row['title'])."</a>";

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
        <?php if($logged_in){ ?><a href="#" id="newtopic-lower" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>New Thread</b><i></i></a><?php } else { echo "You must be logged in to reply or post new threads."; } ?>
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
</div>

<script type="text/javascript" language="JavaScript">
L10N.put("myhabbo.discussion.error.topic_name_empty", "Topic title may not be empty");
Discussions.initialize("0", "forum.php", null);
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