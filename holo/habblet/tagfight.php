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

require_once('../core.php');
require_once('../includes/session.php');

$tag_1 = $_POST['tag1'];
$tmp = mysql_query("SELECT id FROM cms_tags WHERE tag = '".addslashes($tag_1)."'") or die(mysql_error());
$tag_1_results = mysql_num_rows($tmp);
$tag_2 = $_POST['tag2'];
$tmp = mysql_query("SELECT id FROM cms_tags WHERE tag = '".addslashes($tag_2)."'") or die(mysql_error());
$tag_2_results = mysql_num_rows($tmp);

if(empty($tag_1) || empty($tag_2)){ exit; }

if($tag_1_results == $tag_2_results){
$tag_1 = htmlspecialchars($_POST['tag1']);
$tag_2 = htmlspecialchars($_POST['tag2']);
$end = 0;
} elseif($tag_1_results > $tag_2_results){
$tag_1 = "<b>" . htmlspecialchars($_POST['tag1']) . "</b>";
$tag_2 = htmlspecialchars($_POST['tag2']);
$end = 2;
} elseif($tag_1_results < $tag_2_results){
$tag_1 = htmlspecialchars($_POST['tag1']);
$tag_2 = "<b>" . htmlspecialchars($_POST['tag2']) . "</b>";
$end = 1;
}

echo "			<div id=\"fightResultCount\" class=\"fight-result-count\">
				"; if($end == 0){ echo "Tie!"; } else { echo "The winnner is:"; } echo "<br />
			    ".stripslashes($tag_1)."
			(".$tag_1_results.") hits
            <br/>
                ".stripslashes($tag_2)."
            (".$tag_2_results.") hits
		    </div>
			<div class=\"fight-image\">
					<img src=\"./web-gallery/images/tagfight/tagfight_end_".$end.".gif\" alt=\"\" name=\"fightanimation\" id=\"fightanimation\" />
                <a id=\"tag-fight-button-new\" href=\"#\" class=\"new-button\" onclick=\"TagFight.newFight(); return false;\"><b>Again?</b><i></i></a>
                <a id=\"tag-fight-button\" href=\"#\" style=\"display:none\" class=\"new-button\" onclick=\"TagFight.init(); return false;\"><b>Start</b><i></i></a>
            </div>
";

?>