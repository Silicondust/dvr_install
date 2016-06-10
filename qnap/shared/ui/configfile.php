<?php
	require_once("TinyAjaxBehavior.php");
	require_once("vars.php");
	require_once("logfile.php");
	require_once("includes/dvrui_recordengine_config.php");
	
	function updateRecordPath($recordPath) {
		// prep
		ob_start();
		$tab = new TinyAjaxBehavior();
		
		//create output
		$configFile = new DVRUI_Engine_Config();
		$configFile->setRecordPath($recordPath);
		$configFile->writeConfigFile();
		
		// rescan the file for the string and build up the page again
		$configStr = '';
		$configEntry = file_get_contents('style/config_entry.html');
		if ($configFile->configFileExists()) {
			$configStr = str_replace('<!-- dvrui_config_file_name -->',$configFile->getConfigFileName(),$configEntry);
			$configStr = str_replace('<!-- dvrui_config_recordpath_value -->',$configFile->getRecordPath(),$configStr);
		} else {
			$configStr = "ERROR: Can't Parse Config File: " . $configFile->getConfigFileName();
		}
		
		// now rescan the logfiles..
		$logFileList = getLogFileList($configFile->getRecordPath());
		$statusmsg = getLatestHDHRStatus();
	
		//get data
		$result = ob_get_contents();
		ob_end_clean();
		
		//display
		$tab->add(TabInnerHtml::getBehavior("loglist", $logFileList));
		$tab->add(TabInnerHtml::getBehavior("config_box", $configStr));
		if ($result != '' && $result != NULL)
			$tab->add(TabInnerHtml::getBehavior("statusMessage", $result));
		else
			$tab->add(TabInnerHtml::getBehavior("statusMessage", $statusmsg));
		return $tab->getString();
	}
?>