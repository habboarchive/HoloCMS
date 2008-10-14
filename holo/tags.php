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

$pagename = "Tags";
$pageid = "5";
$body_id = "tags";

if(isset($_GET['tag'])){

        $query = strtolower($_GET['tag']);
        $add = $_GET['add'];
        if($add == "true"){ $add = true; } else { $add = false; }

        $mytags = mysql_evaluate("SELECT COUNT(*) FROM cms_tags WHERE ownerid = '".$my_id."'");

        if(!empty($query)){
                $valid_search = true;
                $filter = preg_replace("/[^a-z\d]/i", "", $query);

                if(strlen($query) < 2 || $filter !== $query || strlen($query) > 20 || $mytags > 20){
                        $valid_tag = false;
                } else {
                        $valid_tag = true;
                }

                $check = mysql_query("SELECT id FROM cms_tags WHERE ownerid = '".$my_id."' AND tag = '".FilterText($query)."' LIMIT 1") or die(mysql_error());
                $tag_count = mysql_num_rows($check);
                if($tag_count > 0){
                        $already_tagged = true;
                } elseif($tag_count < 1){
                        $already_tagged = false;
                }

                if($valid_tag == true && $add == true && $already_tagged !== true){
                        mysql_query("INSERT INTO cms_tags (tag,ownerid) VALUES ('".$query."','".$my_id."')") or die(mysql_error());
                        $already_tagged = true;
                }

                $get_taggers = mysql_query("SELECT ownerid FROM cms_tags WHERE tag LIKE '".FilterText($query)."' LIMIT 20") or die(mysql_error());
                $results = mysql_num_rows($get_taggers);
        } else {
                $valid_search = false;
        }

} else {
        $valid_search = false;
}

include('templates/community/subheader.php');
include('templates/community/header.php');

?>

<div id="container">
	<div id="content" style="position: relative" class="clearfix">
    <div id="column1" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix default ">

							<h2 class="title">Tag Cloud
							</h2>
						<div id="tag-related-habblet-container" class="habblet box-contents">

    <?php @include('tagcloud.php'); ?>
</div>


					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

				<div class="habblet-container ">
						<div class="cbb clearfix default ">

							<h2 class="title">Tag Fight
							</h2>
						<div id="tag-fight-habblet-container">
<div class="fight-process" id="fight-process">The fight ... is on ...</div>
<div id="fightForm" class="fight-form">
    <div class="tag-field-container">First tag<br /><input maxlength="30" type="text" class="tag-input" name="tag1" id="tag1"/></div>
    <div class="tag-field-container">Second tag<br /><input maxlength="30" type="text" class="tag-input" name="tag2" id="tag2"/></div>
</div>
<div id="fightResults" class="fight-results">
    <div class="fight-image">
    	<img src="./web-gallery/images/tagfight/tagfight_start.gif" alt="" name="fightanimation" id="fightanimation" />
        <a id="tag-fight-button" href="#" class="new-button" onclick="TagFight.init(); return false;"><b>Fight!</b><i></i></a>
    </div>
</div>
<div class="tagfight-preload">
	<img src="./web-gallery/images/tagfight/tagfight_end_0.gif" width="1" height="1"/>
	<img src="./web-gallery/images/tagfight/tagfight_end_1.gif" width="1" height="1"/>
	<img src="./web-gallery/images/tagfight/tagfight_end_2.gif" width="1" height="1"/>
	<img src="./web-gallery/images/tagfight/tagfight_loop.gif" width="1" height="1"/>
	<img src="./web-gallery/images/tagfight/tagfight_start.gif" width="1" height="1"/>
</div>
</div>


					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

				<div class="habblet-container ">





				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

</div>
<div id="column2" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix default ">

							<h2 class="title">Find others with the same interests <?php if($valid_search == true){ echo "(" . $results . ")"; } ?>
							</h2>
						<div id="tag-search-habblet-container">
<form name="tag_search_form" action="tags.php" class="search-box">
    <input type="text" name="tag" id="search_query" value="<?php if(isset($query)){ echo HoloText($query); } ?>" class="search-box-query" style="float: left"/>
	<a onclick="$(this).up('form').submit(); return false;" href="#" class="new-button search-icon" style="float: left"><b><span></span></b><i></i></a>
</form>        <?php if($results > 0){ echo "<br /><p class=\"search-result-count\">1 - " . $results . " / " . $results . "</p>"; } else { echo "<p class=\"search-result-count\">No results</p>"; } ?>
                <?php if($valid_search == true && $logged_in == true && $valid_tag == true && $already_tagged == false){ echo "<p id=\"tag-search-add\" class=\"clearfix\"><span style=\"float:left\">Tag yourself with:</span> <a id=\"tag-search-tag-add\" href=\"tags.php?tag=".trim(HoloText($query)))."&add=true\" class=\"new-button\" style=\"float:left\"><b>".trim(HoloText($query)."</b><i></i></a></p>"; } ?>
        <p class="search-result-divider"></p>

    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="search-result">
        <tbody>
        <?php

                $count = 0;

                if($valid_search == true && $results > 0){

                        while($tmp = mysql_fetch_assoc($get_taggers)){

                                $check = mysql_query("SELECT * FROM users WHERE id = '".$tmp['ownerid']."' ORDER BY name ASC LIMIT 1") or die(mysql_error());
                                $exists = mysql_num_rows($check);

                                if($exists > 0){

                                        $count++;
                                        $userdata = mysql_fetch_assoc($check);

                                        if(IsEven($count)){ $oddeven = "even"; } else { $oddeven = "odd"; }

                                        echo "            <tr class=\"".$oddeven."\">
                <td class=\"image\" style=\"width:39px;\">
                    <img src=\"http://www.habbo.co.uk/habbo-imaging/avatarimage?figure=".$userdata['figure']."&size=s&direction=4&head_direction=4&gesture=sml\" alt=\"".$userdata['name']."\" align=\"left\"/>
                </td>
                <td class=\"text\">
                    <a href=\"user_profile.php?name=".$userdata['name']."\" class=\"result-title\">".$userdata['name']."</a><br/>
                    <span class=\"result-description\">".HoloText($userdata['mission'])."</span>
                        <ul class=\"tag-list\">\n";

                                        $get_tags = mysql_query("SELECT tag FROM cms_tags WHERE ownerid = '".$userdata['id']."'") or die(mysql_error());
                                        while($tag = mysql_fetch_assoc($get_tags)){
                                                echo "<li><a href=\"tags.php?tag=".strtolower($tag['tag'])."\" class=\"tag\" style=\"font-size:10px\">".strtolower($tag['tag'])."</a> </li>\n";
                                        }
                                echo "</ul>
                </td>
            </tr>";
                                }
                        }
                }
        ?>
        </tbody>
        </table>

</div>


					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>

</div>
<script type="text/javascript">
HabboView.run();
</script>

<?php
include('templates/community/footer.php');
?>
