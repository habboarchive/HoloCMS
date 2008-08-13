<?php
/*---------------------------------------------------+
| HoloCMS - Website and Content Management System
+----------------------------------------------------+
| Copyright © 2008 Meth0d
+----------------------------------------------------+
| HoloCMS is provided "as is" and comes without
| warrenty of any kind.
+---------------------------------------------------*/

if (!defined("IN_HOLOCMS")) { header("Location: ../../index.php"); exit; }

if(empty($pagename) && isset($searchname)){
$pagename = $searchname;
} elseif(empty($pagename)){
$pagename = "User Profile";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $shortname; ?> ~ <?php echo stripslashes($pagename); ?> </title>

<script type="text/javascript">
var andSoItBegins = (new Date()).getTime();
</script>

<script src="./web-gallery/static/js/visual.js" type="text/javascript"></script>
<script src="./web-gallery/static/js/libs.js" type="text/javascript"></script>
<script src="./web-gallery/static/js/common.js" type="text/javascript"></script>
<script src="./web-gallery/static/js/fullcontent.js" type="text/javascript"></script>
<script src="./web-gallery/static/js/libs2.js" type="text/javascript"></script>
<link rel="stylesheet" href="./web-gallery/v2/styles/style.css" type="text/css" />
<link rel="stylesheet" href="./web-gallery/v2/styles/buttons.css" type="text/css" />
<link rel="stylesheet" href="./web-gallery/v2/styles/boxes.css" type="text/css" />
<link rel="stylesheet" href="./web-gallery/v2/styles/tooltips.css" type="text/css" />
<link rel="alternate" type="application/rss+xml" title="<?php echo $; ?> News RSS" href="./rss.php"/>

<script type="text/javascript">
document.habboLoggedIn = true;
var habboName = "<?php echo $name; ?>";
var habboReqPath = "";
var habboStaticFilePath = "./web-gallery";
var habboImagerUrl = "http://www.habbo.co.uk/habbo-imaging/";
var habboPartner = "Meth0d.org";
window.name = "habboMain";

</script>

<link rel="stylesheet" href="./web-gallery/styles/myhabbo/myhabbo.css" type="text/css" />
<link rel="stylesheet" href="./web-gallery/styles/myhabbo/skins.css" type="text/css" />
<link rel="stylesheet" href="./web-gallery/styles/myhabbo/dialogs.css" type="text/css" />
<link rel="stylesheet" href="./web-gallery/styles/myhabbo/buttons.css" type="text/css" />
<link rel="stylesheet" href="./web-gallery/styles/myhabbo/control.textarea.css" type="text/css" />
<link rel="stylesheet" href="./web-gallery/styles/myhabbo/boxes.css" type="text/css" />
<link rel="stylesheet" href="./web-gallery/v2/styles/myhabbo.css" type="text/css" />
	<link href="./web-gallery/styles/myhabbo/assets.css" type="text/css" rel="stylesheet" />

<script src="./web-gallery/static/js/homeview.js" type="text/javascript"></script>
<script src="./web-gallery/static/js/homeauth.js" type="text/javascript"></script>
<link rel="stylesheet" href="./web-gallery/v2/styles/group.css" type="text/css" />
<style type="text/css">

<?php if(isset($groupid)){ ?>

    #playground, #playground-outer {
	    width: 922px;
	    height: 1360px;
    }

<?php } elseif(IsHCMember($user_row['id'])){ ?>

    #playground, #playground-outer {
	    width: 922px;
	    height: 1360px;
    }

<?php } else { ?>

    #playground, #playground-outer {
	    width: 752px;
	    height: 1360px;
    }

<?php } ?>

</style>




<script src="./web-gallery/static/js/homeedit.js" type="text/javascript"></script>
<script language="JavaScript" type="text/javascript">
<?php if(isset($groupid)){ $xid = $groupid; } else { $xid = $user_row['id']; } ?>
document.observe("dom:loaded", function() { initView(<?php echo $xid . "," . $xid; ?>); });
function isElementLimitReached() {
	if (getElementCount() >= 200) {
		showHabboHomeMessageBox("Error", "You have already placed the maximum number of items on the page. Remove a sticker, note or widget to be able to place this item.", "Close");
		return true;
	}
	return false;
}

function cancelEditing(expired) {
	<?php if(!isset($groupid)){ ?>
	location.replace("user_profile.php?name=<?php echo $searchname; ?>" + (expired ? "?expired=true" : ""));
	<?php } else { ?>
	location.replace("group_profile.php?id=<?php echo $groupid; ?>" + (expired ? "?expired=true" : ""));
	<?php } ?>
}

function getSaveEditingActionName(){
	return 'savehome.php';
}

function showEditErrorDialog() {
	var closeEditErrorDialog = function(e) { if (e) { Event.stop(e); } Element.remove($("myhabbo-error")); Overlay.hide(); }
	var dialog = Dialog.createDialog("myhabbo-error", "", false, false, false, closeEditErrorDialog);
	Dialog.setDialogBody(dialog, '<p>Error occured! Please try again in couple of minutes.</p><p><a href="#" class="new-button" id="myhabbo-error-close"><b>Close</b><i></i></a></p><div class="clear"></div>');
	Event.observe($("myhabbo-error-close"), "click", closeEditErrorDialog);
	Dialog.moveDialogToCenter(dialog);
	Dialog.makeDialogDraggable(dialog);
}


function showSaveOverlay() {
	var invalidPos = getElementsInInvalidPositions();
	if (invalidPos.length > 0) {
		showHabboHomeMessageBox("Sorry!", "Sorry, but you can\'t place your stickers, notes or widgets here. Close the window to continue editing your page.", "Close");
		$A(invalidPos).each(function(el) { Effect.Pulsate(el); });
		return false;
	} else {
		Overlay.show(null,'Saving');
		return true;
	}
}
</script>

<meta name="description" content="<?php echo $sitename; ?> is a virtual world where you can meet and make friends." />
<meta name="keywords" content="<?php echo $sitename; ?>,<?php echo $shortname; ?>,virtual world,play games,enter competitions,make friends" />

<!--[if lt IE 8]>
<link rel="stylesheet" href="./web-gallery/v2/styles/ie.css" type="text/css" />
<![endif]-->
<!--[if lt IE 7]>
<link rel="stylesheet" href="./web-gallery/v2/styles/ie6.css" type="text/css" />
<script src="./web-gallery/static/js/pngfix.js" type="text/javascript"></script>
<script type="text/javascript">
try { document.execCommand('BackgroundImageCache', false, true); } catch(e) {}
</script>

<style type="text/css">
body { behavior: url(./web-gallery/csshover.htc); }
</style>
<![endif]-->
<meta name="build" content="21.0.53 - HoloCMS Homes" />
</head>