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

$allow_guests = false;

include('core.php');
include('includes/session.php');

$pagename = "Applications";
$pageid = "apply";

include('templates/community/subheader.php');
include('templates/community/header.php');

?>

<?php if(!isset($_GET['id'])) { ?>
<div id="container">
	<div id="content">
    <div id="column1" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix brown ">

							<h2 class="title">Applications
							</h2>
						<div class="habblet box-content">
						<?php 	$sql = mysql_query("SELECT id,name FROM cms_application_forms WHERE enabled='1' AND deleted='0' ORDER BY name ASC");
								$sqll = mysql_query("SELECT id,name FROM cms_application_forms WHERE enabled='0' AND deleted='0' ORDER BY name ASC"); 						?>
Do YOU want to become a staff member? That's possible, below you see what jobs are free and what not. In total there are <b><?php echo mysql_num_rows($sql); ?></b> open applications and <b><?php echo mysql_num_rows($sqll); ?></b> closed applications</b>.<br><I>If there aren't any open applications, try again later.</I><br><br><b>Open applications:</b><br><?php
	$sql = mysql_query("SELECT id,name FROM cms_application_forms WHERE enabled='1' AND deleted='0' ORDER BY name ASC");
	if(mysql_num_rows($sql) < 1) { echo "<i>There aren't any open applications!</i>"; }
	while($row = mysql_fetch_assoc($sql)) {
	echo "<a href='applications.php?id=".$row['id']."'>".htmlspecialchars(stripslashes($row['name']))."</a><br>";
	 } ?>
	 <br><b>Closed applications:</b><br><?php
	$sql = mysql_query("SELECT id,name FROM cms_application_forms WHERE enabled='0' AND deleted='0' ORDER BY name ASC");
	if(mysql_num_rows($sql) < 1) { echo "<i>There aren't any closed applications!</i><br>"; }
	while($row = mysql_fetch_assoc($sql)) {
	echo "".htmlspecialchars(stripslashes($row['name']))."<br>";
	 } ?><br>Regards,<br>The staff team
</div>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
			 
</div>
<div id="column2" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix blue ">
	
							<h2 class="title">Wanna be a staff member?
							</h2>
						<div id="notfound-looking-for" class="box-content">
    <p>If you want to become a member of our staff team you need to fill in a application. We will read this and say if you're accepted or not. It's very easy, you can apply in just a few minutes!</p>
</div>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
</div>


<div id="column2" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix green ">
	
							<h2 class="title">Accepted?! :D
							</h2>
						<div id="notfound-looking-for" class="box-content">
    <p>If you're accepted: congratulations! You will soon get your badge and rights. The whole staff team wish you a lot of succes!</p>
</div>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
</div>


<div id="column2" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix red ">
	
							<h2 class="title">Not accepted? :O :(
							</h2>
						<div id="notfound-looking-for" class="box-content">
    <p>It's possbile that you aren't accepted to become a member of our staff team or that there isn't any response. If you aren't accepted, try it again later with a better application. If you don't got any reponse you're application is maybe not read yet.</p>
</div>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
</div><?php }else{
$sql = mysql_query("SELECT * FROM cms_application_forms WHERE id='".$_GET['id']."' AND enabled='1' AND deleted='0'");
$usersql = mysql_query("SELECT * FROM users WHERE id='".$my_id."' LIMIT 1");
if(mysql_num_rows($sql) > 0){
$row = mysql_fetch_assoc($sql);
$user = mysql_fetch_assoc($usersql); ?>




<?php

if(isset($_POST['sumbit'])) {


mysql_query("INSERT INTO cms_applications (rankname,username,realname,birth,sex,country,general_information,experience,education,additional_information,accepted_disclaimer) VALUES ('".htmlspecialchars(stripslashes($row['name']))."','".$rawname."','".$_POST['realname']."','".$user['birth']."','".$_POST['sex']."','".$_POST['country']."','".$_POST['general_information']."','".$_POST['experience']."','".$_POST['education']."','".$_POST['additional_information']."','".$_POST['accepted_disclaimer']."')") or die(mysql_error());
echo "<b>You're application is submitted!</b>";

}
?>






<div id="container">
	<div id="content">
    <div id="column1" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix brown ">

							<h2 class="title">Application: <?php echo htmlspecialchars(stripslashes($row['name'])); ?>
							</h2>
						<div class="habblet box-content">
						<i><b>Note:</b> There aren't any 'checks' if you fill in everything. If you submit the form without fill in everything, it will still be submitted. You can apply once till your application is read.</i><hr><center>The staff team wishes you good luck!</center><hr>
						<?php if($row['introduction'] != "" OR $row['introduction'] != " ") { ?>
						<b>Introduction</b><br>
						<?php echo wordwrap(stripslashes(nl2br($row['introduction'])),5000,"\n",1); ?><br><br><?php }
						if($row['requirements'] != "" OR $row['requirements'] != " ") { ?>
						<b>Requirements</b><br>
						<?php echo wordwrap(stripslashes(nl2br($row['requirements'])),5000,"\n",1); ?><br><br><?php }
								?>Are you still sure you want to apply for this job? Yes? Than fill in the form below!<br><br>
								
					<form method="post">
						<table cellspacing="1" cellpadding="1" width="420" border="3">
							<?php if($row['username'] == 1) { ?>
							<tr>
								<td><b>Username</b><br>
								<i>What's your username?</i></td>
								
								<td><input type="text" maxlength="50" name="username" disabled="disabled" value="<?php echo htmlspecialchars(stripslashes($user['name'])); ?>"></td>
							</tr>
							<?php } 
							if($row['realname'] == 1) { ?>
						<tr>
							<td><b>Name</b><br>
							<i>What's your full name?</td>
							
							<td><input type="text" maxlength="50" name="realname" value="<?php echo $_POST['realname']; ?>"></td>
						</tr><?php }
							if($row['birth'] == 1) { ?>
						<tr>
							<td><b>Date of birth</b><br>
							<i>What's your birth?</i></td>
							
							<td><input type="text" maxlength="50" name="birth" disabled="disabled" value="<?php echo $user['birth']; ?>"></td>
						</tr>
							<?php }
							if($row['sex'] == 1) { ?>
						<tr>
							<td><b>Sex</b><br>
							What's your sex<br>(Male/Female/Shemale)?</td>
							
							<td><input type="text" maxlength="10" name="sex" value="<?php echo $_POST['sex']; ?>"></td>
						</tr>
							<?php }
							if($row['country'] == 1) { ?>
						<tr>
							<td><b>Country</b><br><i>In what country do you live?</i></td>
							
							<td><input type="text" maxlength="50" name="country" value="<?php echo $_POST['country']; ?>"></td>
						</tr>
							<?php }
							if($row['education'] == 1) { ?>
						<tr>
							<td><b>Education</b><br><i>What level you're/you did studying?</i></td>
							
							<td><input type="text" maxlength="50" name="education" value="<?php echo $_POST['education']; ?>"></td>
						</tr>
							<?php } ?>
					</table>
					
					<br>
					
					<table cellspacing="1" cellpadding="0" width="420" border="3">
						<?php if($row['general_information'] == 1) { ?>
							<td><b>General information</b><br><i>Why are you interested in this job, why should we choose you?</i><br>
							<textarea name='general_information' cols='64' rows='5'><?php echo $_POST['general_information']; ?></textarea></td>
						<?php } ?>
					</table>
					
					<br>
					
					<table cellspacing="1" cellpadding="0" width="420" border="3">
						<?php if($row['experience'] == 1) { ?>
							<td><b>Work experience</b><br><i>Do you've work experience. If so, tell us about it?</i><br>
							<textarea name='experience' cols='64' rows='5'><?php echo $_POST['experience']; ?></textarea></td>
						<?php } ?>
					</table>
					
					<br>
					
					<table cellspacing="1" cellpadding="0" width="420" border="3">
						<?php if($row['additional_information'] == 1) { ?>
							<td><b>Additional information</b><br><i>What are you´re hobbies/interests?</i><br>
							<textarea name='additional_information' cols='64' rows='5'><?php echo $_POST['additional_information']; ?></textarea></td>
						<?php } ?>
					</table>
					<?php
					$questionscheck = mysql_query("SELECT * FROM cms_application_questions WHERE aid='".$_GET['id']."'");
					if(mysql_num_rows($questionscheck) > 0) { ?>

					<br>

					<?php
					$sql = mysql_query("SELECT * FROM cms_application_questions WHERE aid='".$_GET['id']."' AND aoq='1'");
					if(mysql_num_rows($sql) > 0) { ?>
					<table cellspacing="1" cellpadding="0" width="420" border="3">
							<td><b>Questions</b><br><i>You're now getting some questions.</i><br>
							<?php
							while($row = mysql_fetch_assoc($sql)) {
							$questions = mysql_query("SELECT * FROM cms_application_questions WHERE qid='".$row['id']."'") or die(mysql_error()); ?>
							<br><b><?php echo stripslashes($row['text']); ?></b><br>
							<?php while($row = mysql_fetch_assoc($questions)) {
							$type = $row['type']; ?>
						 	<input value="<?php echo $row['id']; ?>" type="<?php if($row['sort'] != 1) { echo "checkbox"; }else{ echo "radio"; } ?>" name="<?php echo $row['type']; ?>"> <?php echo $row['text']; ?><br>
						<?php	}
							 }
						?></td>
					</table>
					
					<br>
<?php } ?>
					<?php } ?>
						<?php
$sql = mysql_query("SELECT * FROM cms_application_forms WHERE id='".$_GET['id']."' AND enabled='1' AND deleted='0'");
$row = mysql_fetch_assoc($sql);
						if($row['show_disclaimer'] == 1) {
								if($row['disclaimer_text'] != " " OR $row['disclaimer_text'] != "") {?>
								
					<table cellspacing="1" cellpadding="0" width="420" border="3">
							<td><b>Disclaimer</b><br><i>Below you read a disclaimer, you need to agree with these.</i><br><br><center>--------------------------------------------------------------------------------</center>
							<font color="gray"><?php echo wordwrap(stripslashes(nl2br($row['disclaimer_text'])),5000,"\n",1); ?></font><br><center>--------------------------------------------------------------------------------</center><br>
							<INPUT type=checkbox name="agreement"<?php if(isset($_POST['agreement'])){ echo " CHECKED"; } ?>> I agree with this disclaimer.
							</td>
					</table>
						<?php }
							} ?>
					<br>
					<center><input type="submit" name="sumbit" value="Submit application!"></center>
					</form>
</div>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
</div>

<div id="column2" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix blue ">
	
							<h2 class="title">Here we are!
							</h2>
						<div id="notfound-looking-for" class="box-content">
    <p>Here we are! You're now going to start with your application. We (the staff team) wish you good luck.</p>
</div>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
</div>

<div id="column2" class="column">
				<div class="habblet-container ">
						<div class="cbb clearfix green ">
	
							<h2 class="title">Questions?
							</h2>
						<div id="notfound-looking-for" class="box-content">
    <p>It's possible that you will find questions in the application form. Via these questions we can 'test' you with what you're knowing. - Again, good luck!</p>
</div>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
</div>
























<?php }else{ ?>
<div id="container">
	<div id="content" style="position: relative" class="clearfix">
    <div id="column1" class="column">
				<div class="habblet-container ">		
						<div class="cbb clearfix red ">
	
							<h2 class="title">This application is closed!
							</h2>
						<div id="notfound-content" class="box-content">
    <p class="error-text">Sorry, this application is closed or doesn't excist!</p> <img id="error-image" src="./web-gallery/v2/images/error.gif" />
    <p class="error-text">Please use the 'Back' button to get back to where you started.</p>
</div>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
			 
</div>
<div id="column2" class="column">
				<div class="habblet-container ">		
						<div class="cbb clearfix green ">
	
							<h2 class="title">Were you looking for...
							</h2>
						<div id="notfound-looking-for" class="box-content">
    <p><b>A friend's group or personal page?</b><br/>
    See if it is listed on the <a href="community.php">Community</a> page.</p>

    <p><b>Rooms that rock?</b><br/>
    Browse the <a href="community.php">Recommended Rooms</a> list.</p>

    <p><b>What other users are in to?</b><br/>
    Check out the <a href="tags.php">Top Tags</a> list.</p>

     <p><b>How to get Credits?</b><br/>
    Have a look at the <a href="credits.php">Credits</a> page.</p>
</div>
	
						
					</div>
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
			 
</div>
<?php
	}
}
include('templates/community/footer.php');

?>
