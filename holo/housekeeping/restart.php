<?php
require_once('../core.php');
if(function_exists(SendMUSData) !== true){ include('../includes/mus.php'); }

@SendMUSData('SVEX' . 1 . chr(2) . "hi");
?>