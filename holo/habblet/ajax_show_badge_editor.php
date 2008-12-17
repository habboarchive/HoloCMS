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

include('../core.php');
include('../includes/session.php');

// simple check to avoid most direct access
$refer = $_SERVER['HTTP_REFERER'];
$pos = strrpos($refer, "group_profile.php");
if ($pos === false) { exit; }

$groupid = $_POST['groupId'];
if(!is_numeric($groupid)){ exit; }

$check = mysql_query("SELECT member_rank FROM groups_memberships WHERE userid = '".$my_id."' AND groupid = '".$groupid."' AND member_rank > 1 AND is_pending = '0' LIMIT 1") or die(mysql_error());
$is_member = mysql_num_rows($check);

if($is_member > 0){
    $my_membership = mysql_fetch_assoc($check);
    $member_rank = $my_membership['member_rank'];
    if($member_rank < 2){ exit; }
} else {
    exit;
}

$check = mysql_query("SELECT * FROM groups_details WHERE id = '".$groupid."' LIMIT 1") or die(mysql_error());
$valid = mysql_num_rows($check);

if($valid > 0){ $groupdata = mysql_fetch_assoc($check); } else {exit; }

?>
<div id="badge-editor-flash" align="center">
<strong>Flash is required to use this tool</strong>
</div>
<script type="text/javascript" language="JavaScript">
var swfobj = new SWFObject("http://images.habbohotel.co.uk/habboweb/28_6c80a6dd09d60c84a1396e0ceb63e445/14/web-gallery/flash/BadgeEditor.swf", "badgeEditor", "280", "366", "8");
swfobj.addParam("base", "http://images.habbohotel.co.uk/habboweb/28_6c80a6dd09d60c84a1396e0ceb63e445/14/web-gallery/flash/");
swfobj.addParam("bgcolor", "#FFFFFF");
swfobj.addVariable("post_url", "<?php echo $path; ?>save_group_badge.php");
swfobj.addVariable("__app_key", "Meth0d.org");
swfobj.addVariable("groupId", "<?php echo $groupid; ?>");
swfobj.addVariable("badge_data", "<?php echo $groupdata['badge']; ?>");
swfobj.addVariable("localization_url", "http://www.habbo.co.uk/xml/badge_editor");
swfobj.addVariable("xml_url", "http://www.habbo.co.uk/figure/badge_data_xml");
swfobj.addParam("allowScriptAccess", "always");
swfobj.write("badge-editor-flash");
</script>