<?php
  
require_once("includes/dvrui_recordengine_logentry.php");
  
class DVRUI_Engine_LogFile {
	
	private $logEntries = array();
	private $position = 0;
	
	public function DVRUI_Engine_LogFile($filename) {
		if (file_exists($filename)) {

			$lines = file($filename);
			// read line and convert to entry
			for ($i=0; $i<count($lines); $i++) {
				$entry = new DVRUI_Engine_LogEntry($lines[$i]);
				$this->logEntries[$i]['Timestamp'] = $entry->getLogTimestamp();
				$this->logEntries[$i]['Type'] = $entry->getLogType();
				$this->logEntries[$i]['SubType'] = $entry->getLogSubType();
				$this->logEntries[$i]['Info'] = $entry->getLogInfo();
			}
		}
	}
	
	public function getNextEntry() {
		$this->position++;
		return $this->logEntries[$this->position];
	}
	
	public function getPrevEntry() {
		$this->position--;
		return $this->logEntries[$this->position];
	}
	
	public function getEntryAt($pos) {
		$this->position = $pos;
		return $this->logEntries[$this->position];
	}
	
	public function getNumEntry() {
		return count($this->logEntries);
	}
	
}
?>