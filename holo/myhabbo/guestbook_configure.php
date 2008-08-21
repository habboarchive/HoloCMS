<?php
include('../core.php');

$id = $_POST['widgetId'];

$sql = mysql_query("SELECT * FROM cms_homes_stickers WHERE id = '".$id."' LIMIT 1");
$row = mysql_get_assoc($sql);

if($row['var'] == "0"){
$var = "1";
}else{
$var = "0";
}

mysql_query("UPDATE cms_homes_stickers SET var = '".$var."' WHERE id = '".$id."' LIMIT 1");

?>
var el = $("guestbook-type");
if (el) {
	if (el.hasClassName("public")) {
		el.className = "private";
		new Effect.Pulsate(el,
			{ duration: 1.0, afterFinish : function() { Element.setOpacity(el, 1); } }
		);						
	} else {						
		new Effect.Pulsate(el,
			{ duration: 1.0, afterFinish : function() { Element.setOpacity(el, 0); el.className = "public"; } }
		);						
	}
}