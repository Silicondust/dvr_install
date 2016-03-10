<?php
	require_once("TinyAjaxBehavior.php");
	require_once("vars.php");
	require_once("statusmessage.php");
	require_once("includes/dvrui_recordengine_logfile.php");

	function getLogFile($filename) {
		// prep
		ob_start();
		$tab = new TinyAjaxBehavior();
	
		//create output
		$htmlStr = '';
		$logfile = new DVRUI_Engine_LogFile(DVRUI_Vars::DVR_recPath . '/' . $filename);
		for ($i=0; $i < $logfile->getNumEntry(); $i++) {
			$entry = $logfile->getEntryAt($i);
			$htmlStr .= $entry['Timestamp'];
			$htmlStr .= $entry['Type'];
			$htmlStr .= $entry['SubType'];
			$htmlStr .= $entry['Info'];
			$htmlStr .= '<br/>';
		}
	
		$statusmsg = getLatestHDHRStatus();
		//get data
		$result = ob_get_contents();
		ob_end_clean();
	
		//display
		$tab->add(TabInnerHtml::getBehavior("logfile_box", $htmlStr));
		if ($result != '' && $result != NULL)
			$tab->add(TabInnerHtml::getBehavior("statusMessage", $result));
		else
			$tab->add(TabInnerHtml::getBehavior("statusMessage", $statusmsg));
		return $tab->getString();
	}

	function rmLogFile($filename) {
		// prep
		ob_start();
		$tab = new TinyAjaxBehavior();
	
		//create output
		$logfile = DVRUI_Vars::DVR_recPath . '/' . $filename;
		$htmlStr = 'Deleting ' . $logfile;
		if (file_exists($logfile)) {
			$del = unlink($logfile);
		}

		$configFile = new DVRUI_Engine_Config();
		$logFileList = getLogFileList($configFile->getRecordPath());
		$statusmsg = getLatestHDHRStatus();
		
		//get data
		$result = ob_get_contents();
		ob_end_clean();
	
		//display
		$tab->add(TabInnerHtml::getBehavior("loglist", $logFileList));
		$tab->add(TabInnerHtml::getBehavior("logfile_box", $htmlStr));
		if ($result != '' && $result != NULL)
			$tab->add(TabInnerHtml::getBehavior("statusMessage", $result));
		else
			$tab->add(TabInnerHtml::getBehavior("statusMessage", $statusmsg));
		return $tab->getString();
	}
	
	function getLogFileList($logPath) {
		$listStr = '';
		$logList = new DVRUI_Engine_LogList($logPath);
		$logListEntry = file_get_contents('style/loglist_entry.html');
		if ($logList->pathExists()) {
			$listStr = '<ul>';
			for ($i = $logList->getListLength() - 1 ; $i >= 0 ; $i--) {
				$logfile = basename($logList->getNextLogFile($i),'.log');
				$logfullname = basename($logList->getNextLogFile($i));
				$logEntry = str_replace('<!--logfile-name -->',$logfile,$logListEntry);
				$logEntry = str_replace('<!--logfile-fname -->',$logfullname,$logEntry);
				$listStr .= $logEntry;
			}
			$listStr .= '</ul>';
		} else {
			$listStr = "ERROR: recording path is invalid";
		}
		return $listStr;
	}
?>
