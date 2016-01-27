<?php
  
require_once("incldues/dvrui_recordengine_logfile.php");
  
class DVRUI_Engine_LogFile {
	$logEntries = {};
	$position = 0;
	
	public function DVRUI_Engine_LogFile($filename) {
		// open file
		if file_exists($filename) {
			$lines = file($filename);
			// read line and convert to entry
			for ($i=0; $i<count($lines); $i++) {
				$entry = new DVRUI_Engine_LogEntry($line);
				$logEntries[$i]['Timestamp'] = $entry->getLogTimestamp();
				$logEntries[$i]['Type'] = $entry->getLogType();
				$logEntries[$i]['SubType'] = $entry->getLogSubType();
				$logEntries[$i]['Info'] = $entry->getLogInfo();
			}
		}
	}
	
	public function getNextEntry() {
		$position++;
		return $logEntries[$position];
	}
	
	public function getPrevEntry() {
		$position--;
		return $logEntries[$position];
	}
	
	public function getEntryAt($pos) {
		$position = $pos;
		return $logEntries[$position];
	}
}
?>