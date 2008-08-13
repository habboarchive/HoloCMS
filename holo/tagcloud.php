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

if($_GET['sp'] == "plain"){
include('core.php');
echo "<div class=\"habblet box-content\">";
} elseif(!defined("IN_HOLOCMS")){ exit; }

$result = mysql_query("SELECT tag, COUNT(id) AS quantity FROM cms_tags GROUP BY tag ORDER BY rand() LIMIT 20");
$number = mysql_num_rows($result);

if($number > 0){
echo "	    <ul class=\"tag-list\">";

	while($row = mysql_fetch_array($result)){
		$tags[$row['tag']] = $row['quantity'];
	}

	$max_qty = max(array_values($tags));
	$min_qty = min(array_values($tags));
	$spread = $max_qty - $min_qty;

	if($spread == 0){ $spread = 1; }

	$step = (200 - 100)/($spread);

	foreach($tags as $key => $value){
	    $size = 100 + (($value - $min_qty) * $step);
	    $size = ceil($size);
	    echo "<li><a href=\"tags.php?tag=".strtolower($key)."\" class=\"tag\" style=\"font-size:".$size."%\">".trim(strtolower($key))."</a> </li>\n";
	}

echo "</ul>";
} else {
echo "<div>No tags to display.</div>";
}

if($_GET['sp'] == "plain"){ ?>
    <div class="tag-search-form">
<form name="tag_search_form" action="tags.php" class="search-box">
    <input name="tag" id="search_query" value="" class="search-box-query" style="float: left;" type="text">
	<a onclick="$(this).up('form').submit(); return false;" href="#" class="new-button search-icon" style="float: left;"><b><span></span></b><i></i></a>	
</form>    </div>
<?php } ?>
