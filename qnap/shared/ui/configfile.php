<?php
  require_once("TinyAjaxBehavior.php");
  require_once("vars.php");

  function updateRecordPath($recordPath) {
    // prep
    ob_start();
    $tab = new TinyAjaxBehavior();
    
    //create output
    $configFile = new DVRUI_Engine_Config();
    $configFile->setRecordPath($recordPath);
    $configFile->writeConfigFile();
    

    //rescan the file for the string and build up the page again
    $htmlStr = '';
    $configEntry = file_get_contents('style/config_entry.html');
    if ($configFile->configFileExists()) {
  	  $htmlStr = str_replace('<!-- dvrui_config_file_name -->',$configFile->getConfigFileName(),$configEntry);
  	  $htmlStr = str_replace('<!-- dvrui_config_recordpath_value -->',$configFile->getRecordPath(),$htmlStr);
    } else {
  	  $htmlStr = "ERROR: Can't Parse Config File: " . $configFile->getConfigFileName();
    }

    //get data
    $result = ob_get_contents();
    ob_end_clean();
    
    //display
    $tab->add(TabInnerHtml::getBehavior("config_box", $htmlStr));
    if ($result != '' && $result != NULL)
      $tab->add(TabInnerHtml::getBehavior("statusMessage", $result));
    return $tab->getString();
  }
?>