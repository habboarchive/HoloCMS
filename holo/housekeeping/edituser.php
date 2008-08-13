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

require_once('../core.php');
if($hkzone !== true){ header("Location: index.php?throwBack=true"); exit; }
if(!session_is_registered(acp)){ header("Location: index.php?p=login"); exit; }
if(function_exists(SendMUSData) !== true){ include('../includes/mus.php'); }

$pagename = "Chat Log Viewer";

if(isset($userid)){
	
	$showeditor = false;
	$msg = "Undefined";

	if(is_numeric($userid)){
		$query = mysql_query("SELECT * FROM users WHERE id = '".$userid."' LIMIT 1");
		$found = mysql_num_rows($query);
		if($found > 0){
			$showeditor = true;
			$msg = "Now editing user..";
			$userdata = mysql_fetch_assoc($query);
		} else {
			$msg = "User not found. Please double check the User ID.";
		}
	} else {
		$msg = "Invalid user ID - must be a valid numeric value.";
	}

}

if(isset($_POST['hiddenHook']) && $showeditor == true){
        
        // Start creating the query
        $query = "UPDATE users SET id = '".$userdata['id']."'";
	
        // Add the $_POST'ed UPDATE statements to the query
        foreach($_POST as $key => $value){
                if(strlen($value) > 0 && strlen($key) > 0 && $key !== "hiddenHook" && $key !== "name" && $key !== "id" && $key !== "ticket_sso" && $key !== "ipaddress_last" && $key !== "user" && $key !== "rank"){
                        $query = $query . ", ".$key." = '".$value."'";
                }
        }

        // Finish the query and execute it
        $query = $query . " WHERE id = '".$userdata['id']."' LIMIT 1";
        mysql_query($query) or die(mysql_error());
        
        // Refresh user vars
        $query = mysql_query("SELECT * FROM users WHERE id = '".$userid."' LIMIT 1");
        $userdata = mysql_fetch_assoc($query);
        
        // Refresh user vars ingame (poof etc)
        @SendMusData('UPRC' . $userid);
        @SendMusData('UPRS' . $userid);
        @SendMusData('UPRA' . $userid);
        
        // Create log entry
        mysql_query("INSERT INTO system_stafflog (action,message,note,userid,targetid,timestamp) VALUES ('Housekeeping','Edited user','edituser.php','".$my_id."','".$userid."','".$date_full."')") or die(mysql_error());
        
        // Set $msg
        $msg = "User updated successfully. This user will also update (poof) ingame, if connected.<br />";
        
}

@include('subheader.php');
@include('header.php');
?>
<table cellpadding='0' cellspacing='8' width='100%' id='tablewrap'>
<tr> <td width='22%' valign='top' id='leftblock'>
 <div>
 <!-- LEFT CONTEXT SENSITIVE MENU -->
<?php @include('usermenu.php'); ?>
 <!-- / LEFT CONTEXT SENSITIVE MENU -->
 </div>
 </td>
 <td width='78%' valign='top' id='rightblock'>
 <div><!-- RIGHT CONTENT BLOCK -->
 
<?php if(isset($msg)){ ?><p><strong><?php echo $msg; ?></p></strong><?php } ?>
 
 <?php if($showeditor == true){ ?>
<form action='index.php?p=edituser&do=edit&key=<?php echo $userid; ?>' method='post' name='theAdminForm' id='theAdminForm'>
<input type='hidden' name='hiddenHook' value='valid'>
<div class='tableborder'>
<div class='tableheaderalt'>Edit User</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>

<?php

    foreach($userdata as $key => $value){
        
        if($key !== "password" && $key !== "online"){
        
        if($key == "name" || $key == "id" || $key == "ticket_sso" || $key == "ipaddress_last" || $key == "user" || $key == "rank"){
                $flags = "disabled='disabled'";
        } else {
                $flags = "";
        }
        
        echo "<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>".$key."</b><div class='graytext'></div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='".$key."' size='100%' value='".$value."' ".$flags.">";

    echo "</td>
</tr>";

        }
    }
    
?>

<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='Save User' class='realbutton' accesskey='s'></td></tr>
</form></table></div><br />
<?php } else { ?>
<form action='index.php?p=uid&do=getthatdamnid' method='post' name='theAdminForm' id='theAdminForm'>
<div class='tableborder'>
<div class='tableheaderalt'>Edit User</div>

<table width='100%' cellspacing='0' cellpadding='5' align='center' border='0'>
<tr>
<td class='tablerow1'  width='40%'  valign='middle'><b>Username</b><div class='graytext'>The name of the user you want to edit.</div></td>
<td class='tablerow2'  width='60%'  valign='middle'><input type='text' name='query' value="<?php echo $_POST['query']; ?>" size='30' class='textinput'></td>
</tr>

<tr>
<tr><td align='center' class='tablesubheader' colspan='2' ><input type='submit' value='Edit User' class='realbutton' accesskey='s'></td></tr>
</form></table></div><br />
<?php } ?></div><!-- / RIGHT CONTENT BLOCK -->
	 </td></tr>
</table>
</div><!-- / OUTERDIV -->
<div align='center'><br />
<?php
$mtime = explode(' ', microtime());
$totaltime = $mtime[0] + $mtime[1] - $starttime;
printf('Time: %.3f', $totaltime);
?>
</div>