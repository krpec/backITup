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
	$app->delete();
	
	header("Location: ./index.php");
?>