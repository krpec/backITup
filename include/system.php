<?php
	/**
	 *	backITup project - KDV admission project
	 *	@author		Roman Dittrich (krpec) dittrich.r@gmail.com
	 *	@package	backITup
	 *
	 */
	class System {
		/** @var array local mysql connection */
		private $db = array(
			'server' => "127.0.0.1", //must be an IP address, 'localhost' won't work when executing from command line
			'username' => "root",
			'password' => "root",
			'database' => "backitup",
			'port' => 8889
		);
		
		/** @var mysqli MySQL connection */
		public $mysql;
		
		/** @var array Array of applications. */
		public $apps = array();
		
		/** @var array settings */
		private $settings = array(
			'log' => "__log",
			'tmp' => "__tmp",
			'backup' => "__backup",
			'baseDir' => null,
			'defaultTime' => "02:00:00",
			'defaultWeekday' => 1, //monday
			'localHttpPort' => 8888,
			'appDir' => "backITup"
		);
		
		/** @var Log syslog*/
		public $syslog;
		
		public function __construct($baseDir) {
			//connect to database
			$this->mysql = new mysqli($this->db['server'], $this->db['username'], $this->db['password'], $this->db['database'], $this->db['port']);
	
			if($this->mysql->connect_error) {
				die("Nepodařilo se připojit k MySQL serveru: [" . $this->mysql->connect_errno . "] " . $this->mysql->connect_error);
			}	
			
			//Setting up Log instance
			$this->syslog = new Log($this->mysql, "syslog");
			
			//set up base directory
			$this->baseDir = $baseDir;
			
			date_default_timezone_set("Europe/Prague");
		}
		
		public function __get($key) {
			if(array_key_exists($key, $this->settings)) {
				return $this->settings[$key];
			}
			else return FALSE;
		}
		
		public function __set($key, $value) {
			$this->settings[$key] = $value;
		}
		
		public function saveSyslog() {
			$this->syslog->saveLogFile($this->baseDir . "/" . $this->log);
		}
 		
		/**
		 * Adds new line to syslog.
		 *
		 * @param string $appName name of the application
		 * @param string $message log message
		 * @return void
		 */
		public function addSyslog($appName, $message) {
			$this->syslog->addLog($message, $appName);
		}
		
		public function loadAllApplications() {
			$query = "SELECT appId FROM `applications`";
			if(!$result = $this->mysql->query($query)) {
				echo $this->mysql->error;
			}
			
			while($row = $result->fetch_assoc()) {
				$app = new Application($this);
				$app->load($row['appId']);
				$this->apps[] = $app;
			}
		}
		
		public function makeCrontab() {
			$file = <<<EOM
# (Use to post in the top of your crontab)
# ------------- minute (0 - 59)
# | ----------- hour (0 - 23)
# | | --------- day of month (1 - 31)
# | | | ------- month (1 - 12)
# | | | | ----- day of week (0 - 6) (Sunday=0)
# | | | | |
# * * * * * command to be executed\n
EOM;

			$file .= "0\t0\t*\t*\t*\t";
			$file .= "wget http://localhost:".$this->localHttpPort."/".$this->appDir."/cronSetup.php -o ".$this->baseDir."/".$this->tmp."/cronsetup-".date("Y_m_d-H_i")." -q\n";
			
			$this->loadAllApplications();
			
			foreach($this->apps as $app) {
				//there is something to backup
				if($app->ftpBackup || $app->dbBackup) {
					//date limit
					if(!$app->dateLimit) {
						$time = explode(":", $app->backupTime);
						$hour = $time[0];
						$minute = $time[1];
						
						if($app->frequency == "m") {
							$mDay = date("d");
						}
						else {
							$mDay = "*";
						}
						
						if($app->frequency == "w") {
							$wDay = $app->weekday;
						}
						else {
							$wDay = "*";
						}
						
						$file .= "$hour\t$minute\t$mDay\t*\t$wDay\t";
						$file .= "wget http://localhost:".$this->localHttpPort."/".$this->appDir."/backup.php?appId=".$app->appId()." -o ".$this->baseDir."/".$this->tmp."/".$app->name.date("Y_m_d-H_i")." -q\n";
					}
					elseif((date("Y-m-d") >= $app->dates['from']) && (date("Y-m-d") <= $app->dates['to'])) {
						$time = explode(":", $app->backupTime);
						$hour = $time[0];
						$minute = $time[1];
						
						if($app->frequency == "m") {
							$mDay = date("d");
						}
						else {
							$mDay = "*";
						}
						
						if($app->frequency == "w") {
							$wDay = $app->weekday;
						}
						else {
							$wDay = "*";
						}
						
						$file .= "$hour\t$minute\t$mDay\t*\t$wDay\t";
						$file .= "wget http://localhost:".$this->localHttpPort."/".$this->appDir."/backup.php?appId=".$app->appId()." -o ".$this->baseDir."/".$this->tmp."/".$app->name.date("Y_m_d-H_i")." -q\n";
					}
				}
			}
			
			//delete temp folder content
			$this->deleteTmp();
			
			$cronSetup = fopen($this->baseDir."/".$this->tmp."/cronSetup", "w");
			fwrite($cronSetup, $file);
			fclose($cronSetup);
			
			exec("crontab ".$this->baseDir."/".$this->tmp."/cronSetup", $out);
		}
		
		public function deleteTmp() {
			$folder = opendir($this->baseDir."/".$this->tmp);
			
			while($file = readdir($folder)) {
				if($file == "." || $file == "..") continue;
				
				if(is_file($this->baseDir."/".$this->tmp."/$file")) {
					unlink($this->baseDir."/".$this->tmp."/$file");
				}
			}
			
			closedir($folder);
		}
	}