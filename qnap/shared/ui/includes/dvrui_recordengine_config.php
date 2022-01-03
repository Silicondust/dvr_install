<?php
error_reporting(E_ALL);
require_once("vars.php");

class DVRUI_Engine_Config {
	
	private $configFileName = NULL;
	private $configFileExists = False;
	private $configArray = NULL;
	
	// Default Constructor - nothing provided - do nothing yet...
	public function __construct() {
		if (file_exists(DVRUI_Vars::DVR_pkgPath . "/" . DVRUI_Vars::DVR_config)) {
			$this->configFileName = DVRUI_Vars::DVR_pkgPath . "/" .  DVRUI_Vars::DVR_config;
			$this->configFileExists = True;
			$this->configArray = $this->parseHDHRConfigFile($this->configFileName);
		} else {
			$this->configFileName = DVRUI_Vars::DVR_pkgPath . "/" . DVRUI_Vars::DVR_config . " Does Not Exist";
			$this->configFileExists = False;
		}
	}

	private function parseHDHRConfigFile($file) {
		$config = array();
		foreach(file($file) as $line) {
    	if(preg_match('/^([^;]*?)=(.*)/', $line, $m)) {
        $config[$m[1]] = $m[2];
      }
		}
		return $config;
	}
	
	public function getConfigFileName() {
		return $this->configFileName;
	}
	
	public function configFileExists() {
		return $this->configFileExists;
	}
	
	public function getRecordPath() {
	 	$retVal = 'Path not Set';
		if (array_key_exists('RecordPath', $this->configArray)) {
			$retVal = $this->configArray['RecordPath'];
		}
		return $retVal;
	}

	public function setRecordPath($record_path) {
		$this->configArray['RecordPath'] = $record_path;
	}

	public function getServerPort() {
		$retVal = 'Path not Set';
		if (array_key_exists('Port', $this->configArray)) {
			$retVal = $this->configArray['Port'];
		}
		return $retVal;
	}

	public function setServerPort($serverPort) {
		$this->configArray['Port'] = $serverPort;
	}

	public function getStorageId() {
		$retVal = 'StorageID not Set';
		if (array_key_exists('StorageID', $this->configArray)) {
			$retVal = $this->configArray['StorageID'];
		}
		return $retVal;
	}

	public function getRecordStreamsMax() {
	 	$retVal = '16';
		if (array_key_exists('RecordStreamsMax', $this->configArray)) {
			$retVal = $this->configArray['RecordStreamsMax'];
		}
		return $retVal;
	}

	public function setRecordStreamsMax($streams) {
		$this->configArray['RecordStreamsMax'] = $streams;
	}

	public function getRunAs() {
	 	$retVal = 'not set';
		if (array_key_exists('RunAs', $this->configArray)) {
			$retVal = $this->configArray['RunAs'];
		}
		return $retVal;
	}

	public function setRunAs($user) {
		$this->configArray['RunAs'] = $user;
	}

	public function getBetaEngine() {
	 	$retVal = '0';
		if (array_key_exists('BetaEngine', $this->configArray)) {
			$retVal = $this->configArray['BetaEngine'];
		}
		return $retVal;
	}

	public function setBetaEngine($enable) {
		$this->configArray['BetaEngine'] = $enable;
	}


	public function writeConfigFile() {
		error_log('Writing Config File');
		$content = "";
		foreach($this->configArray as $key => $val) {
			if (is_array($val)) {
				foreach($val as $skey => $sval) {
					$content .= $key . '=' . $val . "\n";
				}
			} else {
				$content .= $key . '=' . $val . "\n";
			}
		}
		$handle = fopen($this->configFileName, 'w');
		if (!$handle) {
			return False;
		} else {
			$retVal = fwrite($handle, $content);
			fclose($handle);
			return $retVal;
		}
	}
}
?>
