<?php
	set_time_limit(0);

	function __autoload($className) {
		require(strtolower("./include/$className.php"));
	}
	
	$system = new System(dirname(__FILE__));
	
	if(isset($_GET['appId']) && is_numeric($_GET['appId'])) {
		$appId = $_GET['appId'];
		
		$app = new Application($system);
		$app->load($appId);
		$app->performNormalBackup($system->baseDir."/".$system->backup, $app->ftp['method'], $app->db['method']);
	}
	else {
		$system->addSyslog("syslog", "backup.php called without appId argument.");
		$system->saveSyslog();
	}
	
	
	
	if(isset($_GET['redirect']) && $_GET['redirect'] == 1) {
		header("Location: ./index.php");
	}
?>