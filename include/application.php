<?php
	/**
	 *	backITup project - KDV admission project
	 *	@author		Roman Dittrich (krpec) dittrich.r@gmail.com
	 *	@package	backITup
	 *
	 *	This class represents single web application, provides methods for backing up this application
	 */
	class Application {
		
		/** @var int*/
		private $id = null;
		
		/** @var stream */
		public $ftpConnection = null;
		
		/** @var string */
		public $name;
		
		/** @var bool enable ftp backup */
		public $ftpBackup;
		
		/** @var array ftp login informations */
		public $ftp = array();
		
		/** @var bool enable database backup */
		public $dbBackup;
		
		/** @var array database login informations */
		public $db = array();
		
		/** @var bool backup date limit */
		public $dateLimit;
		
		/** @var array limit dates*/
		public $dates = array(
			'from' => "0000-00-00",
			'to' => "0000-00-00"
		);
		
		/** @var string/char backup frequency */
		public $frequency;
		
		/** @var int weekday */
		public $weekday;
		
		/** @var string */
		public $backupTime;
		
		/** @var System instance of System class */
		public $system;
		
		public function __construct($system) {
			$this->system = $system;
		}
		
		public function appId() {
			return $this->id;
		}
		
		/**
		 * Loads application from database
		 *
		 * @param int $id Application ID
		 * @return void
		 */
		public function load($id) {
			$query = $this->system->mysql->query("SELECT * FROM applications WHERE appId=$id LIMIT 1");
			
			$row = $query->fetch_assoc();
			
			$this->id = $row['appId'];
			$this->name = $row['name'];
			$this->ftpBackup = $row['ftpBackup'];
			
			$this->ftp = array(
				'server' => $row['ftpServer'],
				'user' => $row['ftpUser'],
				'password' => $row['ftpPassword'],
				'defaultDirectory' => $row['ftpDir'],
				'method' => $row['ftpMethod']
			);
			
			$this->dbBackup = $row['dbBackup'];
			
			$this->db = array(
				'server' => $row['dbServer'],
				'user' => $row['dbUser'],
				'password' => $row['dbPassword'],
				'database' => $row['dbDatabase'],
				'method' => $row['dbMethod'],
			);
			
			$this->dateLimit = $row['dateLimit'];
			
			$this->dates = array(
				'from' => $row['backupFrom'],
				'to' => $row['backupTo']
			);
			
			$this->frequency = $row['frequency'];
			
			$this->weekday =  $row['weekday'];
			
			$this->backupTime = $row['time'];
		}
		
		public function save() {
			if($this->id) {
				//update
				$query = "UPDATE `applications` SET ";
				$query .= "`name` = '".$this->name."', ";
				$query .= "`ftpBackup` = '".$this->ftpBackup."', ";
				$query .= "`ftpServer` = '".$this->ftp['server']."', ";
				$query .= "`ftpUser` = '".$this->ftp['user']."', ";
				$query .= "`ftpPassword` = '".$this->ftp['password']."', ";
				$query .= "`ftpDir` = '".$this->ftp['defaultDirectory']."', ";
				$query .= "`ftpMethod` = '".$this->ftp['method']."', ";
				$query .= "`dbBackup` = '".$this->dbBackup."', ";
				$query .= "`dbServer` = '".$this->db['server']."', ";
				$query .= "`dbUser` = '".$this->db['user']."', ";
				$query .= "`dbPassword` = '".$this->db['password']."', ";
				$query .= "`dbDatabase` = '".$this->db['database']."', ";
				$query .= "`dbMethod` = '".$this->db['method']."', ";
				$query .= "`dateLimit` = '".$this->dateLimit."', ";
				$query .= "`backupFrom` = '".$this->dates['from']."', ";
				$query .= "`backupTo` = '".$this->dates['to']."', ";
				$query .= "`frequency` = '".$this->frequency."', ";
				$query .= "`weekday` = '".$this->weekday."', ";
				$query .= "`time` = '".$this->backupTime."' ";
				$query .= "WHERE `appId` = '".$this->id."' LIMIT 1;";
			}
			else {
				//insert
				$query = "INSERT INTO `applications` ";
				$query .= "(`name`, `ftpBackup`, `ftpServer`, `ftpUser`, `ftpPassword`, `ftpDir`, `ftpMethod`, `dbBackup`, `dbServer`, `dbUser`, `dbPassword`, `dbDatabase`, `dbMethod`, `dateLimit`, `backupFrom`, `backupTo`, `frequency`, `weekday`, `time`) ";
				$query .= "VALUES ";
				$query .= "('".$this->name."', ";
				$query .= "'".$this->ftpBackup."', '".$this->ftp['server']."', '".$this->ftp['user']."', '".$this->ftp['password']."', '".$this->ftp['defaultDirectory']."', '".$this->ftp['method']."', ";
				$query .= "'".$this->dbBackup."', '".$this->db['server']."', '".$this->db['user']."', '".$this->db['password']."', '".$this->db['database']."', '".$this->db['method']."', ";
				$query .= "'".$this->dateLimit."', '".$this->dates['from']."', '".$this->dates['to']."', '".$this->frequency."', '".$this->weekday."', '".$this->backupTime."')";
			}
				
				return $this->system->mysql->query($query);
		}
		
		public function delete() {
			$query = "DELETE FROM `applications` WHERE `appId` = '".$this->id."' LIMIT 1";
			return $this->system->mysql->query($query);
		}
		
		/**
		 * Connects to application's ftp server
		 *
		 * @return stream 
		 */
		public function ftpConnect() {
			$this->ftpConnection = ftp_connect($this->ftp['server']);
			
			if(ftp_login($this->ftpConnection, $this->ftp['user'], $this->ftp['password'])) {
				
				if(!empty($this->ftp['defaultDirectory'])) {
					ftp_chdir($this->ftpConnection, $this->ftp['defaultDirectory']);
				}
				
				$this->system->addSyslog($this->name, "FTP successfully connected.");
				return $this->ftpConnection;
			}
			else $this->system->addSyslog($this->name, "Can't connect to FTP.");
			
			return null;
		}
		
		public function ftpClose() {
			if($this->ftpConnection) {
				$this->system->addSyslog($this->name, "FTP connection closed.");
				ftp_close($this->ftpConnection);
			}
		}
		
		/**
		 * Recursive download of a FTP directory
		 *
		 * @param string $localDir Local download directory without slash at end
		 * @param string $remoteDir Remote directory
		 * @return void
		 */
		public function ftpDownload($localDir, $remoteDir) {
			$i = 0;
			//change the directory if not current
			if($remoteDir !== ".") {
				$chdir = TRUE;
				
				if(!ftp_chdir($this->ftpConnection, $remoteDir)) {
					$this->system->addSyslog($this->name, "Can't change ftp directory to '$remoteDir'.");
				}
			}
			else {
				$chdir = FALSE;
			}
			
			$fileList = ftp_nlist($this->ftpConnection, ".");
			sort($fileList);
			
			foreach($fileList as $file) {
				if($file === "." || $file === "..") {
					continue;
				}
				
				if(@ftp_chdir($this->ftpConnection, $file)) {
					ftp_cdup($this->ftpConnection);
					
					mkdir($localDir . "/$file");
					
					//logovat vytvoreni adresare
					$this->system->addSyslog($this->name, "Local directory '$file' created.");
					
					$dir = $localDir . "/" . $file;
					
					$i += $this->ftpDownload($dir, $file);
				}
				else {
					if(ftp_get($this->ftpConnection, $localDir . "/$file", $file, FTP_BINARY)) {
						$i++;
						
						$this->system->addSyslog($this->name, "File '$file' downloaded successfully.");
					}
					else {
						$this->system->addSyslog($this->name, "File '$file' download failed.");
					}
				}
			}
			
			//return to previous directory before exit
			if($chdir) {
				ftp_cdup($this->ftpConnection);
			}
			
			return $i;
		}
		
		/**
		 * MySQL backup with backup mode selection (gzip, bzip2, without compression) using mysqldump
		 * 
		 * !Imporant: Remote access to the database must be allowed!
		 * 
		 * @param string $backupDir backup directory
		 * @param string $mode backup mode selection (default - none)
		 * @return void
		 */
		public function mysqlBackup($backupDir, $mode = "normal") {
			$this->system->addSyslog($this->name, "Starting MySQL backup...");
			
			$file="$backupDir/". $this->db['database'] . "-" . date('Y_m_d-H_i') . '.sql';
			
			switch($mode){
				case "gz";
				case "gzip":
					$compress = "|gzip -c";
					$file .= ".gz";
					break;
				case "bz2";
				case "bzip2":
					$compress = "|bzip2 -c";
					$file .= ".bz2";
					break;
				case "normal":
					$compress = "";
					break;
			}
			
			exec("/usr/bin/mysqldump -u " . $this->db['user'] . " -p" . $this->db['password'] . " -h " . $this->db['server'] . " --opt " . $this->db['database'] . " $compress > $file");
			
			$this->system->addSyslog($this->name, "Database " . $this->db['database'] . " successfully backed up into file '$file'.");
		}
		
		/**
		 * Performs standard (none-diff) backup
		 *
		 * @param string $localDir destination of local backup
		 * @param string $compressBackup set compressopn to compress backup or not (.tar.gz compression) (normal, gzip)
		 * @param string $sqlCompression set compression of mysql backup (none, gz, bz2)
		 * @return void
		 */
		public function performNormalBackup($localDir, $compressBackup = "normal", $sqlCompression = "normal") {
			//log start of backup 
			$this->system->addSyslog($this->name, "Starting " . $this->name . " backup...");
			
			$this->system->addSyslog($this->name, "Backup options:");
			
			if($this->ftpBackup) {
				$this->system->addSyslog($this->name, "\t" . ($compressBackup ? "Compressed " : "Uncompressed ") . "FTP backup.");
			}
			
			if($this->dbBackup) {
				$this->system->addSyslog($this->name, "\t$sqlCompression MySQL database backup.");
			}
			
			//create directory structure
			$backupDir = $this->name . "-" . date("Y_m_d-H_i");
			$dir = $localDir . "/" . $backupDir;
			mkdir($dir);
			$this->system->addSyslog($this->name, "Local directory structure created. Starting download to '$dir'");
			
			//process ftp backup
			if($this->ftpBackup) {
				mkdir($dir . "/__ftp");
				
				$this->system->addSyslog($this->name, "Opening FTP connection...");
				$this->ftpConnect();

				//download files from ftp
				$files = $this->ftpDownload($dir . "/__ftp", $this->ftp['defaultDirectory']);
				
				$this->ftpClose();
				
				//ftp backup compression
				if($compressBackup == "gzip") {
					$tarName = "ftp-" . $backupDir . ".tar.gz";
					
					
					
					$this->system->addSyslog($this->name, "Starting FTP backup compression, archive name: '$tarName'.");
					$tar = new Archive_Tar($tarName, "gz");
					
					chdir($dir . "/__ftp");
					$archive = scandir(".");
					
					for($i = 0; $i < count($archive); $i++) {
						if($archive[$i] == "." || $archive[$i] == "..") {
							unset($archive[$i]);
						}
					}
					
					$archive = array_values($archive);
					
					//create tar.gz archive
					$tar->create($archive);
					$this->system->addSyslog($this->name, "Archive created, cleaning up files...");
					
					$this->deleteFtpFiles(getcwd(), $tarName);
					
					$this->system->addSyslog($this->name, "FTP backup completed, $files files downloaded and compressed into '$tarName'.");
				}
				else {
					$this->system->addSyslog($this->name, "FTP backup completed, $files files downloaded.");
				}
			}
			
			//mysql backup
			if($this->dbBackup) {
				mkdir($dir . "/__sql");
				$this->mysqlBackup($dir . "/__sql", $sqlCompression);
			}
			
			$this->system->saveSyslog();
		}
		
		public function deleteFtpFiles($backupDir, $archiveName) {
			$folder = opendir($backupDir);
			
			while($file = readdir($folder)) {
				if($file == "." || $file == "..") continue;
				if($file == $archiveName) continue;
				
				if(is_file($backupDir . "/$file")) {
					unlink($backupDir . "/$file");
				}
				
				if(is_dir($backupDir . "/$file")) {
					$this->deleteFtpFiles($backupDir . "/$file", $archiveName);
				}
			}
			
			closedir($folder);
			@rmdir($backupDir);
		}
	}