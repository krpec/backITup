<?php
	function __autoload($className) {
		require(strtolower("./include/$className.php"));
	}
	
	$system = new System(dirname(__FILE__));
	
	$system->addSyslog("syslog", "Remaking crontab.");
	$system->makeCrontab();
	$system->addSyslog("syslog", "crontab done.");
	$system->saveSyslog();
	
	if(isset($_GET['redirect']) && $_GET['redirect'] == 1) {
		header("Location: ./index.php");
	}
	
?>