<?php
	function __autoload($className) {
		require(strtolower("./include/$className.php"));
	}
	
	$system = new System(dirname(__FILE__));
	
	if(isset($_GET['appId']) && is_numeric($_GET['appId'])) {
		$appId = $_GET['appId'];
	}
	else {
		header("Location: ./index.php");
	}
	
	$app = new Application($system);
	$app->load($appId);
	
	switch($_GET['switch']) {
		case 'ftp':
			$app->ftpBackup = ($app->ftpBackup == 1 ? 0 : 1);
			break;
		case 'sql':
			$app->dbBackup = ($app->dbBackup == 1 ? 0 : 1);
			break;
	}
	
	$app->save();
	
	header("Location: ./index.php");
?>