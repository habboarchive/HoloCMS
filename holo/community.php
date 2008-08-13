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
include('includes/news_headlines.php');

$pagename = "Community";
$pageid = "com";

include('templates/community/subheader.php');
include('templates/community/header.php');

?>


<div id="container">
	<div id="content" style="position: relative" class="clearfix">
    <div id="column1" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix green ">
<div class="box-tabs-container clearfix">
    <h2>Popular Rooms</h2>
    <ul class="box-tabs">
    </ul>
</div>
    <div id="tab-0-2-content" >

<div id="rooms-habblet-list-container-h105" class="recommendedrooms-lite-habblet-list-container">
        <ul class="habblet-list">

<?php
$i = 0;
$getem = mysql_query("SELECT * FROM rooms WHERE owner IS NOT NULL ORDER BY visitors_now DESC LIMIT 5") or die(mysql_error());

while ($row = mysql_fetch_assoc($getem)) {
    if($row['owner'] !== ""){ // Public Rooms (and possibly bugged rooms) have no owner, thus do not display them
        $i++;

        if(IsEven($i)){
            $even = "odd";
        } else {
            $even = "even";
        }

        // Calculate percentage
        if($row['visitors_now'] == 0){ $row['visitors_now'] = 1; }
        $data[$i] = ($row['visitors_now'] / $row['visitors_max']) * 100;

        // Base room icon based on this - percantage levels may not be habbolike
        if($data[$i] == 99 || $data[$i] > 99){
            $room_fill = 5;
        } elseif($data[$i] > 65){
            $room_fill = 4;
        } elseif($data[$i] > 32){
            $room_fill = 3;
        } elseif($data[$i] > 0){
            $room_fill = 2;
        } elseif($data[$i] < 1){
            $room_fill = 1;
        }

        printf("<li class=\"%s\">
    <span class=\"clearfix enter-room-link room-occupancy-%s\" title=\"Go to room\" roomid=\"%s\">
	    <span class=\"room-enter\">Enter</span>
	    <span class=\"room-name\">%s</span>
	    <span class=\"room-description\">%s</span>
		<span class=\"room-owner\">Visitors Now: <b>%s</b></span>
		<span class=\"room-owner\">Owner: <a href=\"user_profile.php?name=%s\">%s</a></span>
    </span>
</li>", $even, $room_fill, $row['id'], stripslashes($row['name']), stripslashes($row['descr']), $row['visitors_now'], $row['owner'], $row['owner']);
    }
}
?>

        </ul>
            <div class="clearfix"></div>
</div>
<script type="text/javascript">
L10N.put("show.more", "Show more rooms");
L10N.put("show.less", "Show fewer rooms");
var roomListHabblet_h105 = new RoomListHabblet("rooms-habblet-list-container-h105", "room-toggle-more-data-h105", "room-more-data-h105");
</script>
    </div>

					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>


				<div class="habblet-container ">
						<div class="cbb clearfix activehomes ">

							<h2 class="title">Random <?php echo $shortname; ?>s - Click Us!
							</h2>
						<div id="homes-habblet-list-container" class="habblet-list-container">
	<img class="active-habbo-imagemap" src="./web-gallery/v2/images/activehomes/transparent_area.gif" width="435px" height="230px" usemap="#habbomap" />

<?php
$i = 0;
$getem = mysql_query("SELECT * FROM users ORDER BY RAND() LIMIT 18") or die(mysql_error());

while ($row = mysql_fetch_assoc($getem)) {
$i++;
$list_id = $i - 1;

if(IsUserOnline($row['id']) == true){
	$status = "online";
} else {
	$status = "offline";
}

printf("        <div id=\"active-habbo-data-%s\" class=\"active-habbo-data\">
                    <div class=\"active-habbo-data-container\">
                        <div class=\"active-name %s\">%s</div>
                        Habbo created on: %s
                            <p class=\"moto\">%s</p>
                    </div>
                </div>
                <input type=\"hidden\" id=\"active-habbo-url-%s\" value=\"user_profile.php?name=%s\"/>
                <input type=\"hidden\" id=\"active-habbo-image-%s\" class=\"active-habbo-image\" value=\"http://www.habbo.co.uk/habbo-imaging/avatarimage?figure=%s&size=b&direction=4&head_direction=4&gesture=sml\n\" />", $list_id, $status, $row['name'], $row['hbirth'], stripslashes($row['mission']), $list_id, $row['name'], $list_id, $row['figure']);
}
?>



            <div id="placeholder-container">
                    <div id="active-habbo-image-placeholder-0" class="active-habbo-image-placeholder"></div>
                    <div id="active-habbo-image-placeholder-1" class="active-habbo-image-placeholder"></div>
                    <div id="active-habbo-image-placeholder-2" class="active-habbo-image-placeholder"></div>
                    <div id="active-habbo-image-placeholder-3" class="active-habbo-image-placeholder"></div>
                    <div id="active-habbo-image-placeholder-4" class="active-habbo-image-placeholder"></div>
                    <div id="active-habbo-image-placeholder-5" class="active-habbo-image-placeholder"></div>
                    <div id="active-habbo-image-placeholder-6" class="active-habbo-image-placeholder"></div>
                    <div id="active-habbo-image-placeholder-7" class="active-habbo-image-placeholder"></div>
                    <div id="active-habbo-image-placeholder-8" class="active-habbo-image-placeholder"></div>
                    <div id="active-habbo-image-placeholder-9" class="active-habbo-image-placeholder"></div>
                    <div id="active-habbo-image-placeholder-10" class="active-habbo-image-placeholder"></div>
                    <div id="active-habbo-image-placeholder-11" class="active-habbo-image-placeholder"></div>
                    <div id="active-habbo-image-placeholder-12" class="active-habbo-image-placeholder"></div>
                    <div id="active-habbo-image-placeholder-13" class="active-habbo-image-placeholder"></div>
                    <div id="active-habbo-image-placeholder-14" class="active-habbo-image-placeholder"></div>
                    <div id="active-habbo-image-placeholder-15" class="active-habbo-image-placeholder"></div>
                    <div id="active-habbo-image-placeholder-16" class="active-habbo-image-placeholder"></div>
                    <div id="active-habbo-image-placeholder-17" class="active-habbo-image-placeholder"></div>
            </div>
    </div>

    <map id="habbomap" name="habbomap">
            <area id="imagemap-area-0" shape="rect" coords="55,53,95,103" href="#" alt=""/>
            <area id="imagemap-area-1" shape="rect" coords="120,53,160,103" href="#" alt=""/>
            <area id="imagemap-area-2" shape="rect" coords="185,53,225,103" href="#" alt=""/>
            <area id="imagemap-area-3" shape="rect" coords="250,53,290,103" href="#" alt=""/>
            <area id="imagemap-area-4" shape="rect" coords="315,53,355,103" href="#" alt=""/>
            <area id="imagemap-area-5" shape="rect" coords="380,53,420,103" href="#" alt=""/>
            <area id="imagemap-area-6" shape="rect" coords="28,103,68,153" href="#" alt=""/>
            <area id="imagemap-area-7" shape="rect" coords="93,103,133,153" href="#" alt=""/>
            <area id="imagemap-area-8" shape="rect" coords="158,103,198,153" href="#" alt=""/>
            <area id="imagemap-area-9" shape="rect" coords="223,103,263,153" href="#" alt=""/>
            <area id="imagemap-area-10" shape="rect" coords="288,103,328,153" href="#" alt=""/>
            <area id="imagemap-area-11" shape="rect" coords="353,103,393,153" href="#" alt=""/>
            <area id="imagemap-area-12" shape="rect" coords="55,153,95,203" href="#" alt=""/>
            <area id="imagemap-area-13" shape="rect" coords="120,153,160,203" href="#" alt=""/>
            <area id="imagemap-area-14" shape="rect" coords="185,153,225,203" href="#" alt=""/>
            <area id="imagemap-area-15" shape="rect" coords="250,153,290,203" href="#" alt=""/>
            <area id="imagemap-area-16" shape="rect" coords="315,153,355,203" href="#" alt=""/>
            <area id="imagemap-area-17" shape="rect" coords="380,153,420,203" href="#" alt=""/>
    </map>
<script type="text/javascript">
    var activeHabbosHabblet = new ActiveHabbosHabblet();
    document.observe("dom:loaded", function() { activeHabbosHabblet.generateRandomImages(); });
</script>


					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

</div>
<div id="column2" class="column">
				<div class="habblet-container ">





				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

				<div class="habblet-container ">





				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

				<div class="habblet-container news-promo">
						<div class="cbb clearfix notitle ">


						<div id="newspromo">
        <div id="topstories">
	        <div class="topstory" style="background-image: url(<?php echo $news_1_topstory; ?>)">
	            <h4>Latest news</h4>
	            <h3><a href="news.php?id=<?php echo $news_1_id; ?>"><?php echo $news_1_title; ?></a></h3>
	            <p class="summary">
	            <?php echo $news_1_snippet; ?>
	            </p>
	            <p>
	                <a href="news.php?id=<?php echo $news_1_id; ?>">Read more &raquo;</a>
	            </p>
	        </div>
	        <div class="topstory" style="background-image: url(<?php echo $news_2_topstory; ?>); display: none">
	            <h4>Latest news</h4>
	            <h3><a href="news.php?id=<?php echo $news_2_id; ?>"><?php echo $news_2_title; ?></a></h3>
	            <p class="summary">
	            <?php echo $news_2_snippet; ?>
	            </p>
	            <p>
	                <a href="news.php?id=<?php echo $news_2_id; ?>">Read more &raquo;</a>
	            </p>
	        </div>
        </div>
        <ul class="widelist">
            <li class="even">
                <a href="news.php?id=<?php echo $news_3_id; ?>"><?php echo $news_3_title; ?></a><div class="newsitem-date"><?php echo $news_3_date; ?></div>
            </li>
            <li class="odd">
                <a href="news.php?id=<?php echo $news_4_id; ?>"><?php echo $news_4_title; ?></a><div class="newsitem-date"><?php echo $news_4_date; ?></div>
            </li>
            <li class="last"><a href="news.php">More news &raquo;</a></li>
        </ul>
</div>
<script type="text/javascript">
	document.observe("dom:loaded", function() { NewsPromo.init(); });
</script>


					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

				<div class="habblet-container ">
						<div class="cbb clearfix red ">

							<h2 class="title">Users like..
							</h2>
						<div class="habblet box-content">
<?php include('tagcloud.php'); ?>
    <div class="tag-search-form">
<form name="tag_search_form" action="tags.php" class="search-box">
    <input type="text" name="tag" id="search_query" value="" class="search-box-query" style="float: left"/>
	<a onclick="$(this).up('form').submit(); return false;" href="#" class="new-button search-icon" style="float: left"><b><span></span></b><i></i></a>
</form>    </div>
</div>


					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>


</div>

<script type="text/javascript">
	HabboView.add(LoginFormUI.init);
</script>

<div id="column3" class="column">
</div>

<?php

include('templates/community/footer.php');

?>
