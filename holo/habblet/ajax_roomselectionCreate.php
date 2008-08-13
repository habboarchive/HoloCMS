<?php
include('../core.php');

$roomType = $_POST['roomType'];
echo $roomType;
echo "<br><br>";

if(isset($roomType)) {
	$sql = mysql_query("SELECT noob,gift,sort,roomid,lastgift FROM users WHERE id='".$my_id."'");
	$row = mysql_fetch_assoc($sql);
		if($row['noob'] == 0 & $row['sort'] == 0 && $row['roomid'] == 0) {
		echo $roomType;
			if($roomType == 0) {
			echo $roomType;
			mysql_query("INSERT INTO rooms (name,description,owner,category,model,floor,wallpaper,state,password,showname,superusers,visitors_now,visitors_max) VALUES ('".$rawname."''s room', '".$rawname." has entered the building', '".$rawname."', 0, 's', 601, 1501, 0, '', '1', '0', 0, 25)") or die(mysql_error());
			$sql = mysql_query("SELECT id FROM rooms WHERE name='".$rawname."''s room' AND description = '".$rawname." has entered the building' ORDER BY id DESC LIMIT 1") or die(mysql_error());
			$row = mysql_fetch_assoc($sql);
			mysql_query("UPDATE users SET roomid='".$row['id']."' WHERE id='".$my_id."' LIMIT 1") or die(mysql_error());
			mysql_query("INSERT INTO furniture (tid,ownerid,roomid,x,y,z,h) VALUES ('1183', '".$my_id."', '".$row['id']."', '1', '6', '2', '0.00')") or die(mysql_error());
			mysql_query("INSERT INTO furniture (tid,ownerid,roomid,x,y,z,h,wallpos) VALUES ('1196', '".$my_id."', '".$row['id']."', '0', '0', '0', '0.00', ':w=3,0 l=13,71 r')") or die(mysql_error());
			mysql_query("UPDATE users SET noob='1',lastgift='".date("d-m-Y")."',sort=".$roomType."+1 WHERE id='".$my_id."' LIMIT 1") or die(mysql_error());
			mysql_query("INSERT INTO furniture (tid,ownerid,roomid,x,y,z,h) VALUES ('1189','".$my_id."','0','0','0','0','0.00')");
			}elseif($roomType == 1) {
			mysql_query("INSERT INTO rooms (name,description,owner,category,model,floor,wallpaper,state,password,showname,superusers,visitors_now,visitors_max) VALUES ('".$rawname."''s room', '".$rawname." has entered the building', '".$rawname."', 0, 's', 0, 607, 0, '', '1', '0', 0, 25)") or die(mysql_error());
			$sql = mysql_query("SELECT id FROM rooms WHERE name='".$rawname."''s room' AND description = '".$rawname." has entered the building' ORDER BY id DESC LIMIT 1") or die(mysql_error());
			$row = mysql_fetch_assoc($sql);
			mysql_query("UPDATE users SET roomid='".$row['id']."' WHERE id='".$my_id."' LIMIT 1") or die(mysql_error());
			mysql_query("INSERT INTO furniture (tid,ownerid,roomid,x,y,z,h) VALUES ('1184', '".$my_id."', '".$row['id']."', '3', '6', '4', '0.00')") or die(mysql_error());
			mysql_query("INSERT INTO furniture (tid,ownerid,roomid,x,y,z,h,wallpos) VALUES ('1196', '".$my_id."', '".$row['id']."', '0', '0', '0', '0.00', ':w=3,0 l=13,71 r')") or die(mysql_error());
			mysql_query("UPDATE users SET noob='1',lastgift='".date("d-m-Y")."',sort=".$roomType."+1 WHERE id='".$my_id."' LIMIT 1") or die(mysql_error());
			mysql_query("INSERT INTO furniture (tid,ownerid,roomid,x,y,z,h) VALUES ('1190','".$my_id."','0','0','0','0','0.00')");
			}elseif($roomType == 2) {
			mysql_query("INSERT INTO rooms (name,description,owner,category,model,floor,wallpaper,state,password,showname,superusers,visitors_now,visitors_max) VALUES ('".$rawname."''s room', '".$rawname." has entered the building', '".$rawname."', 0, 's', 301, 1901, 0, '', '1', '0', 0, 25)") or die(mysql_error());
			$sql = mysql_query("SELECT id FROM rooms WHERE name='".$rawname."''s room' AND description = '".$rawname." has entered the building' ORDER BY id DESC LIMIT 1") or die(mysql_error());
			$row = mysql_fetch_assoc($sql);
			mysql_query("UPDATE users SET roomid='".$row['id']."' WHERE id='".$my_id."' LIMIT 1") or die(mysql_error());
			mysql_query("INSERT INTO furniture (tid,ownerid,roomid,x,y,z,h) VALUES ('1185', '".$my_id."', '".$row['id']."', '2', '2', '4', '0.00')") or die(mysql_error());
			mysql_query("INSERT INTO furniture (tid,ownerid,roomid,x,y,z,h,wallpos) VALUES ('1196', '".$my_id."', '".$row['id']."', '0', '0', '0', '0.00', ':w=3,0 l=13,71 r')") or die(mysql_error());
			mysql_query("INSERT INTO furniture (tid,ownerid,roomid,x,y,z,h) VALUES ('1191','".$my_id."','0','0','0','0','0.00')");
			mysql_query("UPDATE users SET noob='1',lastgift='".date("d-m-Y")."',sort=".$roomType."+1 WHERE id='".$my_id."' LIMIT 1") or die(mysql_error());
			}elseif($roomType == 3) {
			mysql_query("INSERT INTO rooms (name,description,owner,category,model,floor,wallpaper,state,password,showname,superusers,visitors_now,visitors_max) VALUES ('".$rawname."''s room', '".$rawname." has entered the building', '".$rawname."', 0, 's', 110, 1801, 0, '', '1', '0', 0, 25)") or die(mysql_error());
			$sql = mysql_query("SELECT id FROM rooms WHERE name='".$rawname."''s room' AND description = '".$rawname." has entered the building' ORDER BY id DESC LIMIT 1") or die(mysql_error());
			$row = mysql_fetch_assoc($sql);
			mysql_query("UPDATE users SET roomid='".$row['id']."' WHERE id='".$my_id."' LIMIT 1") or die(mysql_error());
			mysql_query("INSERT INTO furniture (tid,ownerid,roomid,x,y,z,h) VALUES ('1186', '".$my_id."', '".$row['id']."', '1', '2', '2', '0.00')") or die(mysql_error());
			mysql_query("INSERT INTO furniture (tid,ownerid,roomid,x,y,z,h,wallpos) VALUES ('1196', '".$my_id."', '".$row['id']."', '0', '0', '0', '0.00', ':w=3,0 l=13,71 r')") or die(mysql_error());
			mysql_query("UPDATE users SET noob='1',lastgift='".date("d-m-Y")."',sort=".$roomType."+1 WHERE id='".$my_id."' LIMIT 1") or die(mysql_error());
			mysql_query("INSERT INTO furniture (tid,ownerid,roomid,x,y,z,h) VALUES ('1192','".$my_id."','0','0','0','0','0.00')");
			}elseif($roomType == 4) {
			mysql_query("INSERT INTO rooms (name,description,owner,category,model,floor,wallpaper,state,password,showname,superusers,visitors_now,visitors_max) VALUES ('".$rawname."''s room', '".$rawname." has entered the building', '".$rawname."', 0, 's', 104, 503, 0, '', '1', '0', 0, 25)") or die(mysql_error());
			$sql = mysql_query("SELECT id FROM rooms WHERE name='".$rawname."''s room' AND description = '".$rawname." has entered the building' ORDER BY id DESC LIMIT 1") or die(mysql_error());
			$row = mysql_fetch_assoc($sql);
			mysql_query("UPDATE users SET roomid='".$row['id']."' WHERE id='".$my_id."' LIMIT 1") or die(mysql_error());
			mysql_query("INSERT INTO furniture (tid,ownerid,roomid,x,y,z,h) VALUES ('1187', '".$my_id."', '".$row['id']."', '3', '6', '0', '0.00')") or die(mysql_error());
			mysql_query("INSERT INTO furniture (tid,ownerid,roomid,x,y,z,h,wallpos) VALUES ('1196', '".$my_id."', '".$row['id']."', '0', '0', '0', '0.00', ':w=3,0 l=13,71 r')") or die(mysql_error());
			mysql_query("UPDATE users SET noob='1',lastgift='".date("d-m-Y")."',sort=".$roomType."+1 WHERE id='".$my_id."' LIMIT 1") or die(mysql_error());
			mysql_query("INSERT INTO furniture (tid,ownerid,roomid,x,y,z,h) VALUES ('1193','".$my_id."','0','0','0','0','0.00')");
			}elseif($roomType == 5) {
			mysql_query("INSERT INTO rooms (name,description,owner,category,model,floor,wallpaper,state,password,showname,superusers,visitors_now,visitors_max) VALUES ('".$rawname."''s room', '".$rawname." has entered the building', '".$rawname."', 0, 's', 107, 804, 0, '', '1', '0', 0, 25)") or die(mysql_error());
			$sql = mysql_query("SELECT id FROM rooms WHERE name='".$rawname."''s room' AND description = '".$rawname." has entered the building' ORDER BY id DESC LIMIT 1") or die(mysql_error());
			$row = mysql_fetch_assoc($sql);
			mysql_query("UPDATE users SET roomid='".$row['id']."' WHERE id='".$my_id."' LIMIT 1") or die(mysql_error());
			mysql_query("INSERT INTO furniture (tid,ownerid,roomid,x,y,z,h) VALUES ('1188', '".$my_id."', '".$row['id']."', '3', '6', '0', '0.00')") or die(mysql_error());
			mysql_query("INSERT INTO furniture (tid,ownerid,roomid,x,y,z,h,wallpos) VALUES ('1196', '".$my_id."', '".$row['id']."', '0', '0', '0', '0.00', ':w=3,0 l=13,71 r')") or die(mysql_error());
			mysql_query("UPDATE users SET noob='1',lastgift='".date("d-m-Y")."',sort=".$roomType."+1 WHERE id='".$my_id."' LIMIT 1") or die(mysql_error());
			mysql_query("INSERT INTO furniture (tid,ownerid,roomid,x,y,z,h) VALUES ('1194','".$my_id."','0','0','0','0','0.00')");
			}else{
			echo "";
			}
		}
}

?>