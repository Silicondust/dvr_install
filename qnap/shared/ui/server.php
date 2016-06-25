<?php
	require_once("TinyAjaxBehavior.php");
	require_once("vars.php");
	require_once("statusmessage.php");
	
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

	function getServerData() {
		$config_data = '';
		$configFile = new DVRUI_Engine_Config();
		$configEntry = file_get_contents('style/config_entry.html');
		if ($configFile->configFileExists()) {
			$config_data = str_replace('<!-- dvrui_config_file_name -->',$configFile->getConfigFileName(),$configEntry);
			$config_data = str_replace('<!-- dvrui_config_recordpath_value -->',$configFile->getRecordPath(),$config_data);
			$config_data = str_replace('<!-- dvrui_config_port_value -->',$configFile->getServerPort(),$config_data);
		} else {
			$config_data = "ERROR: Can't Parse Config File: " . $configFile->getConfigFileName();
		}

		return $config_data;
	}
?>
