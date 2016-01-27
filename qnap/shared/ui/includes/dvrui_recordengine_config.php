<?php
error_reporting(E_ALL);
require_once("vars.php");

class DVRUI_Engine_Config {
	
	private $configFileName = NULL;
	private $configFileExists = False;
	
	// Default Constructor - nothing provided - do nothing yet...
	public function DVRUI_Engine_Config() {
		if (file_exists(DVRUI_Vars::DVR_qpkgPath . "/" . DVRUI_Vars::DVR_config)) {
			$this->configFileName = DVRUI_Vars::DVR_qpkgPath . "/" .  DVRUI_Vars::DVR_config;
			$this->configFileExists = True;
		} else {
			$this->configFileName = DVRUI_Vars::DVR_qpkgPath . "/" . DVRUI_Vars::DVR_config . " Does Not Exist";
			$this->configFileExists = False;
		}
	}
	
	public function getConfigFileName() {
		return $this->configFileName;
	}
	
	public function configFileExists() {
		return $this->configFileExists;
	}
	
	public function getRecordPath() {
		return "";
	}
	
	public function setRecordPath() {
		return 0;
  }
}
?>