<?php
	error_reporting(E_ALL & ~(E_DEPRECATED | E_STRICT));
	define('TINYAJAX_PATH', '.');
	require_once("TinyAjax.php");
	require_once("TinyAjaxBehavior.php");
	require_once("vars.php");
	require_once("includes/dvrui_recordengine_config.php");
	require_once("includes/dvrui_recordengine_loglist.php");
	require_once("includes/dvrui_hdhrjson.php");
	require_once("includes/dvrui_hdhrbintools.php");
	require_once("includes/dvrui_rules.php");
	require_once("logfile.php");
	require_once("configfile.php");
	require_once("statusmessage.php");
	require_once("controls.php");
	require_once("rules.php");

	/* Prepare Ajax */
	$ajax = new TinyAjax();
	$ajax->setRequestType("POST");    // Change request-type from GET to POST
	$ajax->showLoading();             // Show loading while callback is in progress
	
	/* Export the PHP Interface */
	$ajax->exportFunction("getLogFile", "filename");
	$ajax->exportFunction("rmLogFile", "filename");
	$ajax->exportFunction("updateRecordPath","recordPath");
	$ajax->exportFunction("updateServerPort","serverPort");
	$ajax->exportFunction("changeDvrState","option");
	$ajax->exportFunction("openLogPage","");
	$ajax->exportFunction("openRulesPage","");

	/* GO */
	$ajax->process();                // Process our callback

	// Prep data for the page
	$loginform = "";
	$sidebar_data = "";
	$content_data = "";
	
	// Build the Data
	$configFile = new DVRUI_Engine_Config();
	$configEntry = file_get_contents('style/config_entry.html');
	if ($configFile->configFileExists()) {
		$config_data = str_replace('<!-- dvrui_config_file_name -->',$configFile->getConfigFileName(),$configEntry);
		$config_data = str_replace('<!-- dvrui_config_recordpath_value -->',$configFile->getRecordPath(),$config_data);
		$config_data = str_replace('<!-- dvrui_config_port_value -->',$configFile->getServerPort(),$config_data);
	} else {
		$config_data = "ERROR: Can't Parse Config File: " . $configFile->getConfigFileName();
	}

	$statusmsg  = getLatestHDHRStatus();

	//Construct the List of LogFiles
	$sidebar_data = '';
	
	$hdhr = DVRUI_Vars::DVR_qpkgPath . '/' . DVRUI_Vars::DVR_bin;
	$DVRBin = new DVRUI_HDHRbintools($hdhr);
	$DVRBinVersion = $DVRBin->get_DVR_version();
	
	// Discover HDHR Devices
	$hdhr = new DVRUI_HDHRjson();
	$devices =  $hdhr->device_count();
	$hdhrListEntry = file_get_contents('style/hdhrlist_entry.html');
	$hdhr_data = '<ul>';
	for ($i=0; $i < $devices; $i++) {
		$hdhr_device_data = "<a href=" . $hdhr->get_device_baseurl($i) . ">" . $hdhr->get_device_id($i) . "</a>";
		$hdhr_lineup_data = "<a href=" . $hdhr->get_device_lineup($i) . ">" . $hdhr->get_device_channels($i) . " Channels</a>";
		$hdhrEntry = str_replace('<!--hdhr_device-->',$hdhr_device_data,$hdhrListEntry);
		$hdhrEntry = str_replace('<!--hdhr_model-->',$hdhr->get_device_model($i),$hdhrEntry);
		$hdhrEntry = str_replace('<!--hdhr_tuners-->',$hdhr->get_device_tuners($i) . ' tuners',$hdhrEntry);
		$hdhrEntry = str_replace('<!--hdhr_firmware-->',$hdhr->get_device_firmware($i),$hdhrEntry);
		$hdhrEntry = str_replace('<!--hdhr_channels-->',$hdhr_lineup_data,$hdhrEntry);
		$hdhr_data .= $hdhrEntry ;	
	}
	$hdhr_data .= '</ul>';

	// Discover Recording Rules
	$rules_data = '';
	
	//Build navigation menu for pages
	$pageTitles = array('Logs','Rules');
	$pageNames = array('log_page', 'rules_page');
	$menu_data = file_get_contents('style/pagemenu.html');
	$menuEntries = '';
	for ($i=0; $i < count($pageNames); $i++) {
		$menuEntry = str_replace('<!-- dvrui_menu_pagename-->',$pageNames[$i],file_get_contents('style/pagemenu_entry.html'));
		$menuEntry = str_replace('<!-- dvrui_menu_pagetitle-->',$pageTitles[$i],$menuEntry);
		$menuEntries .= $menuEntry;
	}
	$menu_data = str_replace('<!-- dvrui_pagemenu_entries-->',$menuEntries,$menu_data);
	
	// --- Build Page Here ---
	$pageName = DVRUI_Vars::DVRUI_name;
	$UIVersion = "UI Version:" . DVRUI_Vars::DVRUI_version;
	$DVRVersion = "Record Engine Version: <i>" . $DVRBinVersion . "</i>";
	$pagecontent = "";

	// --- include header ---
	$header = file_get_contents('style/header.html');
	$pagecontent = str_replace('[[pagetitle]]',$pageName,$header);
	$pagecontent = str_replace('<!-- tinyAjax -->',$ajax->drawJavaScript(false, true),$pagecontent);

	// --- Build Body ---
	$indexPage = file_get_contents('style/index_page.html');
	$topmenu = file_get_contents('style/topmenu.html');
	$configbox = file_get_contents('style/index_config.html');
	$logfilelist = file_get_contents('style/index_loglist.html');
	$hdhrlist = file_get_contents('style/hdhrlist.html');
	$logfiledata = file_get_contents('style/index_logdata.html');
	$rulesdata = file_get_contents('style/rules.html');

	$topmenu = str_replace('[[pagetitle]]',$pageName,$topmenu);
	$topmenu = str_replace('[[UI-Version]]',$UIVersion,$topmenu);
	$topmenu = str_replace('[[DVR-Version]]',$DVRVersion,$topmenu);


	$configbox = str_replace('<!-- dvrui_config_data -->',$config_data,$configbox);
	$logfiledata = str_replace('<!-- dvrui_content_data -->',$content_data,$logfiledata);
	$logfilelist = str_replace('<!-- dvrui_sidebar_data -->',$sidebar_data,$logfilelist);
	$rulesdata = str_replace('<!-- dvrui_rules_data -->',$rules_data,$rulesdata);
	$hdhrlist = str_replace('<!-- dvrui_hdhrlist_data -->',$hdhr_data,$hdhrlist);

	$indexPage = str_replace('<!-- dvrui_topmenu -->',$topmenu,$indexPage);
	$indexPage = str_replace('<!-- dvrui_config -->',$configbox,$indexPage);
	$indexPage = str_replace('<!-- dvrui_hdhrlist -->',$hdhrlist,$indexPage);
	$indexPage = str_replace('<!-- dvrui_pagemenu -->',$menu_data,$indexPage);
	$indexPage = str_replace('<!-- dvrui_loglist -->',$logfilelist,$indexPage);
	$indexPage = str_replace('<!-- dvrui_logfile -->',$logfiledata,$indexPage);
	$indexPage = str_replace('<!-- dvrui_ruleslist -->',$rulesdata,$indexPage);

	// -- Attach the Index to the Page
	$pagecontent .= $indexPage;

	// --- include footer ---
	$footer = file_get_contents('style/footer.html');
	$footer = str_replace('<!--dvr-statusmsg-->',$statusmsg,$footer);
	$pagecontent .= $footer;
	echo($pagecontent);
?>

