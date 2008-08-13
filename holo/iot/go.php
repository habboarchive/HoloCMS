<?php
/*---------------------------------------------------+
| HoloCMS - Website and Content Management System
+----------------------------------------------------+
| Copyright © 2008 Meth0d
+----------------------------------------------------+
| HoloCMS is provided "as is" and comes without
| warrenty of any kind. 
+---------------------------------------------------*/

include('../config.php');
include('../core.php');
include('../includes/session.php');

if(!isset($_POST['gosend']) && !isset($_GET['post'])){
	// Let's give the URL that pointless help tool feeling
	if(!isset($_GET['lang'])){
	header("location:go.php?lang=en&country=uk");
	} else if(!isset($_GET['lang'])){
	header("location:go.php?lang=en&country=uk");
	} else if($_GET['lang'] !== "en"){
	header("location:go.php?lang=en&country=uk");
	} else if($_GET['country'] !== "uk"){
	header("location:go.php?lang=en&country=uk");
	}
}

include('header.php');

if(isset($_POST['gosend'])){
$subject = addslashes(htmlspecialchars($_POST['subject']));
$message = addslashes(htmlspecialchars($_POST['message']));
	if(empty($subject) || empty($message)){
	$result = "Please do not leave any fields blank";
	} else {
	$sql2 = mysql_query("SELECT * FROM cms_help WHERE ip = '".$remote_ip."' and picked_up = '0' LIMIT 1");
	$num2 = mysql_num_rows($sql2);
		if($num2 == "0"){
		$result = "Ticket submitted. We will get back to you shortly, please be patient as this might take a while. Note that you cannot send any more support requests untill this support ticket is picked up.";
		$disableform = "1";
		$go_sql = mysql_query("INSERT INTO cms_help (username,message,subject,ip,date,picked_up) VALUES ('".$rawname."','".$message."','".$subject."','".$remote_ip."','".$date_full."','0')") or die(mysql_error());
		} else {
		$result = "Unable to submit support request: you still have a pending support request. Please try again later.";
		$disableform = "1";
		}
	}
}

if(isset($_GET['post'])){
$post_id = $_GET['post'];
	$sql2 = mysql_query("SELECT * FROM cms_help WHERE ip = '".$remote_ip."' and picked_up = '0' LIMIT 1");
	$num2 = mysql_num_rows($sql2);
	$sql3 = mysql_query("SELECT * FROM cms_forum_posts WHERE id = '".$post_id."' LIMIT 1");
	$exists = mysql_num_rows($sql3);
	if($exists > 0){ $row3 = mysql_fetch_assoc($sql3); }
		if($num2 == "0"){
			if($_GET['sure'] == "yes" && $exists > 0){
				$result = "Thank you for reporting this post.";
				$disableform = "1";
				$page = $_GET['page'];
				if(empty($page) || !is_numeric($page)){ $page = 1; }
				$go_sql = mysql_query("INSERT INTO cms_help (username,message,subject,ip,date,picked_up) VALUES ('".$rawname."','This user has reported an post on the Discussion Board. The Post ID is ".$post_id.", in thread ".$row3['threadid'].".\n\nOriginal Message\n-----------------------------------\n".addslashes($row3['message'])."\n\nURL:\n-----------------------------------\n".$path."viewthread.php?thread=".$row3['threadid']."&page=".$page."#post-".$row3['id']."','DISCUSSION BOARD - REPORTED POST','".$remote_ip."','".$date_full."','0')") or die(mysql_error());
			} elseif($exists < 1){
				$result = "Invalid post specified";
				$disableform = "1";
			} else {
				$result = "Is the post you have selected offensive or breaking the rules? Are you sure you want to report this post?<br /><br /><a href='go.php?do=report&post=".$post_id."&page=".$_GET['page']."&sure=yes'>Proceed</a>";
				$disableform = "1";
			}
		} else {
		$result = "Unable to report post: you still have a pending support request. Please try again later. Note that post reports also count as support requests.";
		$disableform = "1";
		}
}

?>

  <div style="height: 4px;"></div>
  <div style="height: 18px; padding: 0 0 0 12px;">&nbsp;</div>	
  <div class="portlet">
   <div class="portlet-top-process"><div class="portlet-process-header">&nbsp;</div></div>
   <div class="portlet-body-process">
   <div class="imaindiv">
<form method="post" action="go.php"><input type="hidden" name="sid" value="55"><table border="0" cellspacing="0" cellpadding="0" class="ihead">
 <tr>
  <td class="icon"><img src="header_images/Western2/1.gif" alt=" " width="47" height="37" /></td>
  <td class="text"><h2>Ask your question</h2></td></tr>
</table>
<br>

<table border="0" cellspacing="0" cellpadding="0" class="content-table">
 <tr>
  <td>
   <div class="iinfodiv">
    You can contact Player Support through this form. They will get back to you as soon as possible. If it's urgent, you may also use the Call For Help system ingame.
<br><br>
<?php if(!empty($result)){
echo "<b>".$result."</b>";
echo "<br><br>"; } ?>

<?php if($disableform !== "1"){ ?>
    Subject:<br>
    <input type="text" name="subject"  size="50" maxlength="50" value="" /><br> 
    Your question/problem:<br>
    <textarea name="message"  class="imessageform"></textarea>
   </div>
<?php } ?>
  <br>
  </td>
 </tr>
</table>
   <div style="float:right;">
   <table height="21" border="0" cellpadding="0" cellspacing="0" class="button">
<?php if($disableform !== "1"){ ?>
    <tr><td class="button-left-side"></td><td class="middle"><input type="submit" class="proceedbutton" name="gosend" value="Proceed" /></td><td class="button-right-side-arrow"></td></tr>
<?php } else { ?>
    <tr><td class="button-left-side"></td><td class="middle"><input type="button" class="proceedbutton" onclick="javascript:window.close()" value="Close" /></td><td class="button-right-side-arrow"></td></tr>
<?php } ?>
   </table>
   </div>
</form>
   </div>
   </div>
   <div class="portlet-bottom-process"></div>
  </div>
 </div>

<?php

include('footer.php');

?>