<?php
	require_once("TinyAjaxBehavior.php");
	require_once("vars.php");
	require_once("statusmessage.php");
 	require_once("includes/dvrui_recordengine_config.php");

	function openServerPage() {
		// prep
		ob_start();
		$tab = new TinyAjaxBehavior();

		//create output
		$htmlStr = getServerData();
		
		//get data
		$result = ob_get_contents();
		ob_end_clean();

		// get latest status	
		$statusmsg = getLatestHDHRStatus();
	
		//display
		$tab->add(TabInnerHtml::getBehavior("server_box", $htmlStr));
		if ($result != '' && $result != NULL)
			$tab->add(TabInnerHtml::getBehavior("statusMessage", $result));
		else
			$tab->add(TabInnerHtml::getBehavior("statusMessage", $statusmsg));
		return $tab->getString();
	}
		
	function updateRecordPath($recordPath) {
		// prep
		ob_start();
		$tab = new TinyAjaxBehavior();
		
		//create output
		$serverConfig = new DVRUI_Engine_Config();
		$serverConfig->setRecordPath($recordPath);
		$serverConfig->writeConfigFile();
		
		// rescan the file for the string and build up the page again
		$htmlStr = getServerData();
		$statusmsg = getLatestHDHRStatus();
	
		//get data
		$result = ob_get_contents();
		ob_end_clean();
		
		//display
		$tab->add(TabInnerHtml::getBehavior("server_box", $htmlStr));
		if ($result != '' && $result != NULL)
			$tab->add(TabInnerHtml::getBehavior("statusMessage", $result));
		else
			$tab->add(TabInnerHtml::getBehavior("statusMessage", $statusmsg));
		return $tab->getString();
	}
	
	function updateServerPort($serverPort) {
		// prep
		ob_start();
		$tab = new TinyAjaxBehavior();
		
		//create output
		$serverConfig = new DVRUI_Engine_Config();
		$serverConfig->setServerPort($serverPort);
		$serverConfig->writeConfigFile();
		
		// rescan the file for the string and build up the page again
		$htmlStr = getServerData(); 
		$statusmsg = getLatestHDHRStatus();
	
		//get data
		$result = ob_get_contents();
		ob_end_clean();
		
		//display
		$tab->add(TabInnerHtml::getBehavior("server_box", $htmlStr));
		if ($result != '' && $result != NULL)
			$tab->add(TabInnerHtml::getBehavior("statusMessage", $result));
		else
			$tab->add(TabInnerHtml::getBehavior("statusMessage", $statusmsg));
		return $tab->getString();
	}

	function getServerData() {
		
		$serverConfig = new DVRUI_Engine_Config();
		$serverStr = file_get_contents('style/server.html');
		$serverCtrls = file_get_contents('style/server_controls.html');
		$serverCfg = file_get_contents('style/server_config.html');
		
		if ($serverConfig->configFileExists()) {
			$serverCfg = str_replace('<!-- dvrui_config_file_name -->', $serverConfig->getConfigFileName(), $serverCfg);
			$serverCfg = str_replace('<!-- dvrui_config_recordpath_value -->', $serverConfig->getRecordPath(), $serverCfg);
			$serverCfg = str_replace('<!-- dvrui_config_port_value -->', $serverConfig->getServerPort(), $serverCfg);
		} else {
			$serverCfg = "ERROR: Can't Parse Config File: " . $configFile->getConfigFileName();
		}
		
		$serverStr = str_replace('<!-- dvrui_server_data -->', $serverCtrls, $serverStr);
		$serverStr = str_replace('<!-- dvrui_server_params -->', $serverCfg, $serverStr);
		
		return $serverStr; 
	}

?>
