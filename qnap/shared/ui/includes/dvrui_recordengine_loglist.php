<?php
error_reporting(E_ALL);
require_once("vars.php");

class DVRUI_Engine_LogList {
	private $pathExists = False;
	private $logfiles = array();
	
	// Default Constructor - nothing provided - do nothing yet...
	public function DVRUI_Engine_LogList() {
		if (file_exists(DVRUI_Vars::DVR_recPath)) {
			$this->pathExists = True;
      foreach(glob(DVRUI_Vars::DVR_recPath . "/*.log") as $filename) {
      	array_push($this->logfiles, $filename);
      }
		}
	}
	
	public function pathExists() {
		return $this->pathExists;
	}
	
	public function getListLength() {
		return count($this->logfiles);
	}
	
	public function getNextLogFile($index) {
		if ($index <= count($this->logfiles)) {
			return $this->logfiles[$index];
		}
	}
}
?>