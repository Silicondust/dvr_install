<?php
	require_once("TinyAjaxBehavior.php");
	require_once("vars.php");
	require_once("statusmessage.php");
	require_once("includes/dvrui_recordengine_config.php");
	require_once("includes/dvrui_recordengine_logfile.php");
	require_once("includes/dvrui_recordengine_loglist.php");
	
	function openDashboard() {
		// prep
		ob_start();
		$tab = new TinyAjaxBehavior();

		//create output
		$serverConfig = new DVRUI_Engine_Config();
		$htmlStr  = file_get_contents('style/dashboard_page.html');
		$htmlStr = str_replace('<!-- dvrui_dashboard_server_ctrls -->',loadServerCtrlPane(),$htmlStr);
		$htmlStr = str_replace('<!-- dvrui_dashboard_server_params -->',loadServerParamPane($serverConfig),$htmlStr);
		$htmlStr = str_replace('<!-- dvrui_dashboard_hdhr -->',loadHDHRPane(),$htmlStr);
		$htmlStr = str_replace('<!-- dvrui_dashboard_logs -->',loadLogFilePane($serverConfig),$htmlStr);
		
		//get data
		$result = ob_get_contents();
		ob_end_clean();

		// get latest status	
		$statusmsg = getLatestHDHRStatus();
	
		//display
		$tab->add(TabInnerHtml::getBehavior("dashboard_box", $htmlStr));
		if ($result != '' && $result != NULL)
			$tab->add(TabInnerHtml::getBehavior("statusMessage", $result));
		else
			$tab->add(TabInnerHtml::getBehavior("statusMessage", $statusmsg));
		return $tab->getString();
	}


	function loadServerCtrlPane() {
		$htmlStr  = file_get_contents('style/dashboard_ctrls.html');
		return $htmlStr;
	}
	
	function loadServerParamPane($serverConfig) {
		$htmlStr  = file_get_contents('style/dashboard_params.html');
		if ($serverConfig->configFileExists()) {
			$serverParamEntry = file_get_contents('style/dashboard_ctrls_entry_ro.html');
			$serverParamEntry = str_replace('<!-- dvrui_param_name -->', 'Config File Location', $serverParamEntry);
			$serverParamEntry = str_replace('<!-- dvrui_param_value -->', $serverConfig->getConfigFileName(), $serverParamEntry);
			$htmlStr = str_replace('<!-- dvrui_config_file_name -->', $serverParamEntry, $htmlStr);

			$serverParamEntry = file_get_contents('style/dashboard_ctrls_entry_wr.html');
			$serverParamEntry = str_replace('<!-- dvrui_param_name -->', 'RecordPath', $serverParamEntry);
			$serverParamEntry = str_replace('<!-- dvrui_param_value -->', $serverConfig->getRecordPath(), $serverParamEntry);
			$htmlStr = str_replace('<!-- dvrui_config_recordpath_value -->', $serverParamEntry, $htmlStr);

			$serverParamEntry = file_get_contents('style/dashboard_ctrls_entry_wr.html');
			$serverParamEntry = str_replace('<!-- dvrui_param_name -->', 'RecordStreamsMax', $serverParamEntry);
			$serverParamEntry = str_replace('<!-- dvrui_param_value -->', $serverConfig->getRecordStreamsMax(), $serverParamEntry);
			$htmlStr = str_replace('<!-- dvrui_config_recordstreamsmax_value -->', $serverParamEntry, $htmlStr);

			$serverParamEntry = file_get_contents('style/dashboard_ctrls_entry_wr.html');
			$serverParamEntry = str_replace('<!-- dvrui_param_name -->', 'Port', $serverParamEntry);
			$serverParamEntry = str_replace('<!-- dvrui_param_value -->', $serverConfig->getServerPort(), $serverParamEntry);
			$htmlStr = str_replace('<!-- dvrui_config_port_value -->', $serverParamEntry, $htmlStr);

			$serverParamEntry = file_get_contents('style/dashboard_ctrls_entry_wr.html');
			$serverParamEntry = str_replace('<!-- dvrui_param_name -->', 'StorageID', $serverParamEntry);
			$serverParamEntry = str_replace('<!-- dvrui_param_value -->', $serverConfig->getStorageId(), $serverParamEntry);
			$htmlStr = str_replace('<!-- dvrui_config_storage_value -->', $serverParamEntry, $htmlStr);

			$serverParamEntry = file_get_contents('style/dashboard_ctrls_entry_wr.html');
			$serverParamEntry = str_replace('<!-- dvrui_param_name -->', 'RunAs', $serverParamEntry);
			$serverParamEntry = str_replace('<!-- dvrui_param_value -->', $serverConfig->getRunAs(), $serverParamEntry);
			$htmlStr = str_replace('<!-- dvrui_config_runas -->', $serverParamEntry, $htmlStr);

			$serverParamEntry = file_get_contents('style/dashboard_ctrls_entry_wr.html');
			$serverParamEntry = str_replace('<!-- dvrui_param_name -->', 'BetaEngine', $serverParamEntry);
			$serverParamEntry = str_replace('<!-- dvrui_param_value -->', $serverConfig->getBetaEngine(), $serverParamEntry);
			$htmlStr = str_replace('<!-- dvrui_config_beta -->', $serverParamEntry, $htmlStr);

		} else {
			$htmlStr = "ERROR: Can't Parse Config File: " . $configFile->getConfigFileName();
		}
		return $htmlStr;
	}
	
	function loadHDHRPane() {
		// Discover HDHR Devices
		$hdhr = new DVRUI_HDHRjson();
		
		// Process Devices
		$devices =  $hdhr->device_count();
		$hdhr_data = '';
		for ($i=0; $i < $devices; $i++) {
			$hdhrEntry = file_get_contents('style/hdhrlist_entry.html');
			$hdhr_device_data = "<a href=" . $hdhr->get_device_baseurl($i) . ">" . $hdhr->get_device_id($i) . "</a>";
			$hdhr_lineup_data = "<a href=" . $hdhr->get_device_lineup($i) . ">" . $hdhr->get_device_channels($i) . " Channels</a>";
			$hdhr_name_data = str_replace("HDHomeRun", "HDHomeRun <br>", $hdhr->get_device_modelname($i));
			$hdhrEntry = str_replace('<!--hdhr_device-->',$hdhr_device_data,$hdhrEntry);
			$hdhrEntry = str_replace('<!--hdhr_channels-->',$hdhr_lineup_data,$hdhrEntry);
			$hdhrEntry = str_replace('<!--hdhr_model-->',$hdhr_name_data,$hdhrEntry);
			$hdhrEntry = str_replace('<!--hdhr_tuners-->',$hdhr->get_device_tuners($i) . ' tuners',$hdhrEntry);
			$hdhrEntry = str_replace('<!--hdhr_firmware_name-->',$hdhr->get_device_fwname($i),$hdhrEntry);
			$hdhrEntry = str_replace('<!--hdhr_firmware_ver-->',$hdhr->get_device_firmware($i),$hdhrEntry);
			$hdhrEntry = str_replace('<!--hdhr_image-->',$hdhr->get_device_image($i),$hdhrEntry);
			$hdhr_data .= $hdhrEntry ;	
		}

		// Process Engines
		$engines =  $hdhr->engine_count();
		for ($i=0; $i < $engines; $i++) {
			$hdhrEntry = file_get_contents('style/enginelist_entry.html');
			$hdhr_device_data = "<a href=" . $hdhr->get_engine_baseurl($i) . ">" . $hdhr->get_engine_id($i) . "</a>";
			$hdhr_storage_url = "<a href=" . $hdhr->get_engine_storageUrl($i) . ">" . $hdhr->get_engine_storageUrl($i) . "</a>";
			$hdhr_name_data = str_replace("HDHomeRun", "HDHomeRun <br>", $hdhr->get_engine_modelname($i));
			$hdhrEntry = str_replace('<!--hdhr_device-->',$hdhr_device_data,$hdhrEntry);
			$hdhrEntry = str_replace('<!--hdhr_model-->',$hdhr_name_data,$hdhrEntry);
			$hdhrEntry = str_replace('<!--hdhr_firmware-->',$hdhr->get_engine_firmware($i),$hdhrEntry);
			$hdhrEntry = str_replace('<!--hdhr_freespace-->',$hdhr->get_engine_freespace($i),$hdhrEntry);
			$hdhrEntry = str_replace('<!--hdhr_storageid-->',$hdhr->get_engine_storageId($i),$hdhrEntry);
			$hdhrEntry = str_replace('<!--hdhr_storageurl-->',$hdhr_storage_url,$hdhrEntry);
			$hdhrEntry = str_replace('<!--hdhr_image-->',$hdhr->get_engine_image($i),$hdhrEntry);
			$hdhr_data .= $hdhrEntry ;	
		}

		return $hdhr_data;
	}
	
	function loadLogFilePane($serverConfig) {
		$htmlStr =  file_get_contents('style/dashboard_logs.html');
		$loglist = buildLogFileList($serverConfig->getRecordPath());
		$htmlStr =  str_replace('<!-- dvrui_loglist -->', $loglist, $htmlStr);
		return $htmlStr;
	}

	function buildLogFileList($logPath) {
		error_log("*** Building Log File List ***");
		$listStr = '';
		$logPathlist = array();
		if (strpos($logPath, ';') !== false) {
			// have multiple entries
			$logPathlist = explode(';',$logPath);
		} else {
			$logPathlist[0] = $logPath;
		}
		
		$logListEntry = file_get_contents('style/loglist_entry.html');
		$listStr = '<ul>';
		foreach ($logPathlist as $path) {
			if ($path != 'null') {
				$logList = new DVRUI_Engine_LogList($path);
				if ($logList->pathExists()) {
					for ($i = $logList->getListLength() - 1 ; $i >= 0 ; $i--) {
						$logfile = basename($logList->getNextLogFile($i),'.log');
						$logfullname = $logList->getNextLogFile($i);
						$logEntry = str_replace('<!--logfile-name -->',$logfile,$logListEntry);
						$logEntry = str_replace('<!--logfile-fname -->',$logfullname,$logEntry);
						$listStr .= $logEntry;
					}
				} else {
					$listStr = "ERROR: recording path is invalid";
					error_log("ERROR: recording path is invalid");
				}
			} 
		}
		$listStr .= '</ul>';
		return $listStr;
	}

	function getLogFile($filename) {
		error_log("*** Parsing Log File ***");
		// prep
		ob_start();
		$tab = new TinyAjaxBehavior();
		$configFile = new DVRUI_Engine_Config();

		//create output
		$logfile = new DVRUI_Engine_LogFile($filename);
		$logEntry = file_get_contents('style/logfile_entry.html');
		$htmlStr = '';

		for ($i=0; $i < $logfile->getNumEntry(); $i++) {
			$entry = $logfile->getEntryAt($i);
			$entryStr = str_replace('<!--log-time-->',$entry['Timestamp'],$logEntry);
			
			if ($logfile->isTypeError($i)) {
				$entryStr = str_replace('<!--logtype-class-->','logTypeError',$entryStr);
			} else if ($logfile->isTypeStatus($i)) {
				$entryStr = str_replace('<!--logtype-class-->','logTypeStatus',$entryStr);
			} else if ($logfile->isTypePlayback($i)) {
				$entryStr = str_replace('<!--logtype-class-->','logTypePlayback',$entryStr);
			} else if ($logfile->isTypeRecording($i)) {
				$entryStr = str_replace('<!--logtype-class-->','logTypeRecording',$entryStr);
			} else if ($logfile->isTypeRecorded($i)) {
				$entryStr = str_replace('<!--logtype-class-->','logTypeRecorded',$entryStr);
			} else {
				$entryStr = str_replace('<!--logtype-class-->','logTypeInfo',$entryStr);
			}
			
			$entryStr = str_replace('<!--log-type-->',$entry['Type'],$entryStr);
			$entryStr = str_replace('<!--logsubtype-class-->','',$entryStr);
			$entryStr = str_replace('<!--log-subtype-->',$entry['SubType'],$entryStr);
			if ($logfile->infoContainsError($i)) {
				$entryStr = str_replace('<!--logentry-class-->','logInfoError',$entryStr);
			} else {
				$entryStr = str_replace('<!--logentry-class-->','',$entryStr);
			}
			$entryStr = str_replace('<!--log-entry-->',$entry['Info'],$entryStr);
			$htmlStr.= $entryStr;
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

	function updateServerConfig($port,$path,$streams,$runas,$beta) {
		// prep
		ob_start();
		$tab = new TinyAjaxBehavior();
		
		//create output
		$serverConfig = new DVRUI_Engine_Config();

		error_log('Updating Config File: '. $port . ' | ' . $streams . ' | ' . $path . ' | ' . $runas . ' | ' . $beta);
		$serverConfig->setRecordPath($path);
		$serverConfig->setRecordStreamsMax($streams);
		$serverConfig->setServerPort($port);
		if ($runas != 'not set') {
			$serverConfig->setRunAs($runas);
		}
		$serverConfig->setBetaEngine($beta);
		$serverConfig->writeConfigFile();
	
	
		$htmlStr  = file_get_contents('style/dashboard_page.html');
		$htmlStr = str_replace('<!-- dvrui_dashboard_server_ctrls -->',loadServerCtrlPane(),$htmlStr);
		$htmlStr = str_replace('<!-- dvrui_dashboard_server_params -->',loadServerParamPane($serverConfig),$htmlStr);
		$htmlStr = str_replace('<!-- dvrui_dashboard_hdhr -->',loadHDHRPane(),$htmlStr);
		$htmlStr = str_replace('<!-- dvrui_dashboard_logs -->',loadLogFilePane($serverConfig),$htmlStr);
		
		$statusmsg = getLatestHDHRStatus();
	
		//get data
		$result = ob_get_contents();
		ob_end_clean();
		
		//display
		$tab->add(TabInnerHtml::getBehavior("dashboard_box", $htmlStr));
		if ($result != '' && $result != NULL)
			$tab->add(TabInnerHtml::getBehavior("statusMessage", $result));
		else
			$tab->add(TabInnerHtml::getBehavior("statusMessage", $statusmsg));
		return $tab->getString();
	}

?>
