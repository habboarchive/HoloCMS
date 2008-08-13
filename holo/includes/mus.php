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

/** MUS SOCKET INCLUDE (INC.MUS)
* @author	Meth0d
* @desc		Send data to the Holograph MUS Socket
* @usage	@SendMUSData();
*/

if (!defined("IN_HOLOCMS")) { header("Location: ../index.php"); exit; }

function SendMUSData($data){

$configsql = mysql_query("SELECT * FROM cms_system LIMIT 1");
$config = mysql_fetch_assoc($configsql);

$mus_ip = $config['ip'];
$mus_port = FetchServerSetting('server_mus_port');

if(!is_numeric($mus_port)){ echo "<b>System Error</b><br />Invalid MUS Port!"; exit; }

$sock = socket_create(AF_INET, SOCK_STREAM, getprotobyname('tcp'));
socket_connect($sock, $mus_ip, $mus_port);

	if(!is_resource($sock)){
		return false;
	} else {
		socket_send($sock, $data, strlen($data), MSG_DONTROUTE);
		return true;
	}
	
socket_close($sock);
}

?>