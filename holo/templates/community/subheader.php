<?php
/*---------------------------------------------------+
| HoloCMS - Website and Content Management System
+----------------------------------------------------+
| Copyright © 2008 Meth0d
+----------------------------------------------------+
| HoloCMS is provided "as is" and comes without
| warrenty of any kind.
+---------------------------------------------------*/

if (!defined("IN_HOLOCMS")) { header("Location: ..index.php"); exit; }

if(!isset($pagename) || empty($pagename)){
$pagename = $shortname;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $shortname; ?> ~ <?php echo $pagename; ?> </title>

<script type="text/javascript">
var andSoItBegins = (new Date()).getTime();
</script>

<link rel="shortcut icon" href="favicon.ico" type="image/vnd.microsoft.icon" />
<script src="web-gallery/static/js/visual.js" type="text/javascript"></script>
<script src="web-gallery/static/js/libs.js" type="text/javascript"></script>
<script src="web-gallery/static/js/common.js" type="text/javascript"></script>
<script src="web-gallery/static/js/fullcontent.js" type="text/javascript"></script>
<script src="web-gallery/static/js/libs2.js" type="text/javascript"></script>
<link rel="stylesheet" href="./web-gallery/v2/styles/style.css" type="text/css" />
<link rel="stylesheet" href="./web-gallery/v2/styles/buttons.css" type="text/css" />
<link rel="stylesheet" href="./web-gallery/v2/styles/boxes.css" type="text/css" />
<link rel="stylesheet" href="./web-gallery/v2/styles/tooltips.css" type="text/css" />
<link rel="alternate" type="application/rss+xml" title="<?php $sitename; ?> News RSS" href="./rss.php"/>

<script type="text/javascript">
document.habboLoggedIn = true;
var habboName = "<?php echo $name; ?>";
var habboReqPath = "";
var habboStaticFilePath = "web-gallery/";
var habboImagerUrl = "http://www.habbo.co.uk/habbo-imaging/";
var habboPartner = "Meth0d.org";
window.name = "habboMain";

</script>

<?php if($pageid == "forum"){ ?>
<link rel="stylesheet" href="./web-gallery/styles/myhabbo/myhabbo.css" type="text/css" />
<link rel="stylesheet" href="./web-gallery/styles/myhabbo/skins.css" type="text/css" />
<link rel="stylesheet" href="./web-gallery/styles/myhabbo/dialogs.css" type="text/css" />
<link rel="stylesheet" href="./web-gallery/styles/myhabbo/buttons.css" type="text/css" />
<link rel="stylesheet" href="web-gallery/styles/myhabbo/control.textarea.css" type="text/css" />
<link rel="stylesheet" href="./web-gallery/styles/myhabbo/boxes.css" type="text/css" />
<link rel="stylesheet" href="./web-gallery/v2/styles/myhabbo.css" type="text/css" />

<script src="web-gallery/static/js/homeview.js" type="text/javascript"></script>
<script src="web-gallery/static/js/homeauth.js" type="text/javascript"></script>
<link rel="stylesheet" href="./web-gallery/v2/styles/group.css" type="text/css" />
<style type="text/css">

    #playground, #playground-outer {
	    width: 752px;
	    height: 1360px;
    }

</style>

<script type="text/javascript">
document.observe("dom:loaded", function() { initView(55918, 1478728); });
</script>

<link href="web-gallery/styles/discussions.css" type="text/css" rel="stylesheet"/>
<?php } ?>

<?php if($body_id == "home" || empty($body_id)){ ?>
<link rel="stylesheet" href="./web-gallery/v2/styles/welcome.css" type="text/css" />
<link rel="stylesheet" href="./web-gallery/v2/styles/personal.css" type="text/css" />

<link rel="stylesheet" href="./web-gallery/v2/styles/group.css" type="text/css" />
<script src="web-gallery/static/js/group.js" type="text/javascript"></script>

<link rel="stylesheet" href="./web-gallery/v2/styles/rooms.css" type="text/css" />
	<script src="web-gallery/static/js/rooms.js" type="text/javascript"></script>
<?php } ?>

<?php if($pageid == "1" || $pageid == "7"){ ?>
<script src="./web-gallery/static/js/habboclub.js" type="text/javascript"></script>
<?php } ?>

<?php if($body_id == "profile"){ ?>
<script src="web-gallery/static/js/settings.js" type="text/javascript"></script>
<link rel="stylesheet" href="./web-gallery/v2/styles/settings.css" type="text/css" />
<link rel="stylesheet" href="./web-gallery/v2/styles/friendmanagement.css" type="text/css" />
<?php } ?>

<?php if($pageid == 1){ ?>
								<link rel="stylesheet" href="./web-gallery/v2/styles/minimail.css" type="text/css" />
<link rel="stylesheet" href="web-gallery/styles/myhabbo/control.textarea.css" type="text/css" />
<script src="web-gallery/static/js/minimail.js" type="text/javascript"></script>
<?php } ?>

<meta name="description" content="<?php echo $sitename; ?> is a virtual world where you can meet and make friends." />
<meta name="keywords" content="<?php echo $sitename . "," . $shortname; ?>,virtual world,play games,enter competitions,make friends" />

<!--[if lt IE 8]>
<link rel="stylesheet" href="./web-gallery/v2/styles/ie.css" type="text/css" />
<![endif]-->
<!--[if lt IE 7]>
<link rel="stylesheet" href="./web-gallery/v2/styles/ie6.css" type="text/css" />
<script src="web-gallery/static/js/pngfix.js" type="text/javascript"></script>
<script type="text/javascript">
try { document.execCommand('BackgroundImageCache', false, true); } catch(e) {}
</script>

<style type="text/css">
body { behavior: url(web-gallery/csshover.htc); }
</style>
<![endif]-->
<meta name="build" content="9.0.47 - Community Template - HoloCMS" />
</head>