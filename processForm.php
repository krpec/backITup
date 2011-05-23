<?php
	function __autoload($className) {
		require(strtolower("./include/$className.php"));
	}

	$system = new System(dirname(__FILE__));

	$appData = array();

	if(isset($_POST['name']) && !empty($_POST['name'])) {
		$appData['name'] = $_POST['name'];
	}
	
	if(isset($_POST['ftpBackup']) && $_POST['ftpBackup'] == "on") {
		$appData['ftpBackup'] = 1;
	}
	else{
		$appData['ftpBackup'] = 0;
	}
	
	if(isset($_POST['ftpServer']) && !empty($_POST['ftpServer'])) {
		$appData['ftp']['server'] = $_POST['ftpServer'];
	}
	else {
		$appData['ftp']['server'] = null;
	}
	
	if(isset($_POST['ftpUser']) && !empty($_POST['ftpUser'])) {
		$appData['ftp']['user'] = $_POST['ftpUser'];
	}
	else {
		$appData['ftp']['user'] = null;
	}
	
	if(isset($_POST['ftpPassword']) && !empty($_POST['ftpPassword'])) {
		$appData['ftp']['password'] = $_POST['ftpPassword'];
	}
	else {
		$appData['ftp']['password'] = null;
	}
	
	if(isset($_POST['ftpDir']) && !empty($_POST['ftpDir'])) {
		$appData['ftp']['defaultDirectory'] = $_POST['ftpDir'];
	}
	else {
		$appData['ftp']['defaultDirectory'] = ".";
	}
	
	if(isset($_POST['ftpMethod']) && !empty($_POST['ftpMethod'])) {
		$appData['ftp']['method'] = $_POST['ftpMethod'];
	}
	else {
		$appData['ftp']['method'] = 'normal';
	}
	
	if(isset($_POST['dbBackup']) && $_POST['dbBackup'] == "on") {
		$appData['dbBackup'] = 1;
	}
	else {
		$appData['dbBackup'] = 0;
	}
	
	if(isset($_POST['dbServer']) && !empty($_POST['dbServer'])) {
		$appData['db']['server'] = $_POST['dbServer'];
	}
	else {
		$appData['db']['server'] = null;
	}
	
	if(isset($_POST['dbUser']) && !empty($_POST['dbUser'])) {
		$appData['db']['user'] = $_POST['dbUser'];
	}
	else {
		$appData['db']['user'] = null;
	}
	
	if(isset($_POST['dbPassword']) && !empty($_POST['dbPassword'])) {
		$appData['db']['password'] = $_POST['dbPassword'];
	}
	else {
		$appData['db']['password'] = null;
	}
	
	if(isset($_POST['dbDatabase']) && !empty($_POST['dbDatabase'])) {
		$appData['db']['database'] = $_POST['dbDatabase'];
	}
	else {
		$appData['db']['database'] = null;
	}
	
	if(isset($_POST['dbMethod']) && !empty($_POST['dbMethod'])) {
		$appData['db']['method'] = $_POST['dbMethod'];
	}
	else {
		$appData['db']['method'] = 'normal';
	}
	
	if(isset($_POST['dateLimit']) && $_POST['dateLimit'] == "on") {
		$appData['dateLimit'] = 1;
	}
	else {
		$appData['dateLimit'] = 0;
	}
	
	if(isset($_POST['backupFrom']) && !empty($_POST['backupFrom'])) {
		$appData['dates']['from'] = $_POST['backupFrom'];
	}
	else {
		$appData['dates']['from'] = "0000-00-00";
	}
	
	if(isset($_POST['backupTo']) && !empty($_POST['backupTo'])) {
		$appData['dates']['to'] = $_POST['backupTo'];
	}
	else {
		$appData['dates']['to'] = "0000-00-00";
	}
	
	if(isset($_POST['frequency']) && !empty($_POST['frequency'])) {
		$appData['frequency'] = $_POST['frequency'];
	}
	else {
		$appData['frequency'] = "w";
	}
	
	if(isset($_POST['weekday']) && is_numeric($_POST['weekday'])) {
		$appData['weekday'] = $_POST['weekday'];
	}
	else {
		$appData['weekday'] = $system->defaultWeekday;
	}
	
	if(isset($_POST['time']) && !empty($_POST['time'])) {
		$time = explode(":", $_POST['time']);
		
		$appData['backupTime'] = $time[0].":".$time[1].":00";
	}
	else {
		$appData['backupTime'] = $system->defaultTime;
	}
	
	$app = new Application($system);
	
	if(isset($_POST['appId']) && is_numeric($_POST['appId'])) {
		$app->load($_POST['appId']);
	}
	
	foreach($appData as $key => $value) {
		$app->$key = $value;
	}
		
	$app->save();
	
	header("Location: ./index.php");
?>