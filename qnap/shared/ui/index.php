<?php
  ini_set('display_errors', 'On');
  error_reporting(E_ALL);
  define('TINYAJAX_PATH', '.');
  require_once("TinyAjax.php");
  require_once("TinyAjaxBehavior.php");
  require_once("vars.php");
  require_once("includes/dvrui_recordengine_config.php");
  require_once("includes/dvrui_recordengine_loglist.php");
  require_once("logfile.php");
  require_once("configfile.php");

  /* Prepare Ajax */
  $ajax = new TinyAjax();
  $ajax->setRequestType("POST");    // Change request-type from GET to POST
  $ajax->showLoading();             // Show loading while callback is in progress
   
  /* Export the PHP Interface */
  $ajax->exportFunction("getLogFile", "logfile");
  $ajax->exportFunction("rmLogFile", "logfile");
  $ajax->exportFunction("updateRecordPath","recordPath");

  /* GO */
  $ajax->process();                // Process our callback
  
  session_start();

	// Prep data for the page
	$loginform = "";
	$sidebar_data = "";
	$content_data = "";
	
  $sidebar_data = '<div class="box">
			Log Files will be listed here
		</div>';
	$content_data = '<div class="box">
			Log File data will be displayed here
		</div>';
	$config_data = '<div class="box">
			Config File Data will Appear here
		</div>';

  // Build the Data
  $configFile = new DVRUI_Engine_Config();
  $configEntry = file_get_contents('style/config_entry.html');
  if ($configFile->configFileExists()) {
  	$config_data = str_replace('<!-- dvrui_config_file_name -->',$configFile->getConfigFileName(),$configEntry);
  	$config_data = str_replace('<!-- dvrui_config_recordpath_value -->',$configFile->getRecordPath(),$config_data);
  } else {
  	$config_data = "ERROR: Can't Parse Config File: " . $configFile->getConfigFileName();
  }
  
  //Construct the List of LogFiles
  $logList = new DVRUI_Engine_LogList();
  $logListEntry = file_get_contents('style/loglist_entry.html');
  if ($logList->pathExists()) {
  	$sidebar_data = '<ul>';
  	for ($i = $logList->getListLength() - 1 ; $i >= 0 ; $i--) {
    	$logfile = basename($logList->getNextLogFile($i),'.log');
    	$logEntry = str_replace('<!--logfile-name -->',$logfile,$logListEntry);
    	$sidebar_data .= $logEntry;
  	}
  	$sidebar_data .= '</ul>';
    //$sidebar_data = "Loading Logfile Lists [" . $logList->getListLength() . "]";
    
  } else {
  	$sidebar_data = "ERROR: recording path is invalid";
  }
  
  // --- Build Page Here ---
  $pageName = DVRUI_Vars::DVRUI_name;
  $UIVersion = "UI Version:" . DVRUI_Vars::DVRUI_version;
  $DVRVersion = "Record Engine Version: " . DVRUI_Vars::DVR_version;
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
  $logfiledata = file_get_contents('style/index_logdata.html');

  $topmenu = str_replace('[[pagetitle]]',$pageName,$topmenu);
  $topmenu = str_replace('[[UI-Version]]',$UIVersion,$topmenu);
  $topmenu = str_replace('[[DVR-Version]]',$DVRVersion,$topmenu);
  $configbox = str_replace('<!-- dvrui_config_data -->',$config_data,$configbox);
  $logfiledata = str_replace('<!-- dvrui_content_data -->',$content_data,$logfiledata);
  $logfilelist = str_replace('<!-- dvrui_sidebar_data -->',$sidebar_data,$logfilelist);
  $indexPage = str_replace('<!-- dvrui_topmenu -->',$topmenu,$indexPage);
  $indexPage = str_replace('<!-- dvrui_config -->',$configbox,$indexPage);
  $indexPage = str_replace('<!-- dvrui_loglist -->',$logfilelist,$indexPage);
  $indexPage = str_replace('<!-- dvrui_logfile -->',$logfiledata,$indexPage);

  // -- Attach the Index to the Page
  $pagecontent .= $indexPage;
  
  // --- include footer ---
  $footer .= file_get_contents('style/footer.html');
  
  $pagecontent .= $footer;
 	echo($pagecontent);
?>

