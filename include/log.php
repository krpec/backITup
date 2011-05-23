<?php
	/**
	 *	backITup project - KDV admission project
	 *	@author		Roman Dittrich (krpec) dittrich.r@gmail.com
	 *	@package	backITup
	 *
	 *	This class provides log support with ability to save log to MySQL and/or to file.
	 */
	
	class Log {
		/** @var mysqli connection */
		public $mysqli;
		
		/** @var string application name */
		public $app;
		
		/** @var array log data */
		public $data = array();
		
		public function __construct($connection, $appName) {
			$this->mysqli = $connection;
			$this->app = $appName;
		}
		
		public function addLog($message, $appName = NULL) {
			$this->data[] = array(
				'date' => date("H:i:s d.m.Y"),
				'appName' => $appName,
				'message' => $message
			);
		}
		
		public function saveLogFile($logDirectory) {
			//ukladat log ma smysl jen pokud je co ukladat
			if(count($this->data)) {
				$log = fopen($logDirectory . "/[log]-" . $this->app . "-" . date("Y_m_d-H_i") . ".log", "w");
				
				foreach($this->data as $item) {
					$line = "[" . $item['date'] . "] " . ($item['appName'] ? "[" . $item['appName'] . "] " : "") . $item['message'] . "\r\n";
					fwrite($log, $line);
				}
				
				fclose($log);
			}
		}
		
		public function saveDbLog() {
			//ukladat logy do db ma smysl jen pokud je co ukladat
			if(count($this->data)) {
				
			}
		}
	}