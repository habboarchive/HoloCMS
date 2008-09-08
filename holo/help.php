<?php
/*===================================================+
|| # HoloCMS - Website and Content Management System
|+===================================================+
|| # Copyright © 2008 Meth0d. All rights reserved.
|| # http://www.meth0d.org
|+===================================================+
|| # HoloCMS is provided "as is" and comes without
|| # warrenty of any kind. HoloCMS is free software!
|+===================================================+
|| # Parts by Yifan Lu
|| # www.obbahhotel.com
|+===================================================*/

$allow_guests = true;

include('core.php');

$pagename = "FAQ";
$body_id = "faq";
include('templates/community/subheader.php');
include('templates/community/fheader.php');

$id = $_GET['id'];
$query = $_POST['query'];
?>
<div id="faq-category-content" class="clearfix" >
<?php if(isset($_GET['id'])){
$sql = mysql_query("SELECT * FROM cms_faq WHERE id = '".$id."' LIMIT 1");
$row = mysql_fetch_assoc($sql);
if($row['content'] != ""){ ?>
<p class="faq-category-description"><?php echo $row['content']; ?></p>
<?php
$sql = mysql_query("SELECT * FROM cms_faq WHERE catid = '".$id."'");
$i = 0;
	while($itemrow = mysql_fetch_assoc($sql)){
	$i++;
	if($i == 1){ $selected = "selected"; }
	if($i != 1){ $faqitem = "$(\"faq-item-content-".$itemrow['id']."\").hide();"; }
		echo "<h4 id=\"faq-item-header-".$itemrow['id']."\" class=\"faq-item-header faq-toggle \"><span class=\"faq-toggle ".$selected."\" id=\"faq-header-text-".$itemrow['id']."\">".$itemrow['title']."</span></h4>
	<div id=\"faq-item-content-".$itemrow['id']."\" class=\"faq-item-content clearfix\">
	    <div class=\"faq-item-content clearfix\">".$itemrow['content']."</div>
	<div class=\"faq-close-container\">
	<div id=\"faq-close-button-".$itemrow['id']."\" class=\"faq-close-button clearfix faq-toggle\" style=\"display:none\">Close FAQ <img id=\"faq-close-image-".$itemrow['id']."\" class=\"faq-toggle\" src=\"./web-gallery/v2/images/faq/close_btn.png\"/></div>
	</div>
	</div>

	<script type=\"text/javascript\">
	    ".$faqitem."
	    $(\"faq-close-button-".$itemrow['id']."\").show();
	</script>\n";
	}
}
}elseif(isset($_POST['query'])){
$sql = mysql_query("SELECT * FROM cms_faq WHERE type = 'item' AND title LIKE '%".$query."%' OR content LIKE '%".$query."%'");
$count = mysql_num_rows($sql);
if($count == 0){;
	echo "No matching FAQ found. Please search again.";
}else{
	while($itemrow = mysql_fetch_assoc($sql)){
	$sql2 = mysql_query("SELECT * FROM cms_faq WHERE id = '".$itemrow['catid']."' LIMIT 1");
	$catrow = mysql_fetch_assoc($sql2);
	$cat = $catrow['title'];
		echo "<h4 id=\"faq-item-header-".$itemrow['id']."\" class=\"faq-item-header faq-toggle \"><span class=\"faq-toggle \" id=\"faq-header-text-".$itemrow['id']."\">".$itemrow['title']."</span><span class=\"item-category\"> - ".$cat."</span></h4>
	<div id=\"faq-item-content-".$itemrow['id']."\" class=\"faq-item-content clearfix\">
	    <div class=\"faq-item-content clearfix\">".$itemrow['content']."</div>
	<div class=\"faq-close-container\">
	<div id=\"faq-close-button-".$itemrow['id']."\" class=\"faq-close-button clearfix faq-toggle\" style=\"display:none\">Close FAQ <img id=\"faq-close-image-".$itemrow['id']."\" class=\"faq-toggle\" src=\"./web-gallery/v2/images/faq/close_btn.png\"/></div>
	</div>
	</div>

	<script type=\"text/javascript\">
	    $(\"faq-item-content-".$itemrow['id']."\").hide();
	    $(\"faq-close-button-".$itemrow['id']."\").show();
	</script>\n";
	}
}
}?>
<script type="text/javascript">
    FaqItems.init();
    SearchBoxHelper.init();
</script>
</div>

</div>
<?php include('templates/community/ffooter.php'); ?>