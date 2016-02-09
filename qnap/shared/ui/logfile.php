<?php
  require_once("TinyAjaxBehavior.php");
  require_once("vars.php");
  require_once("includes/dvrui_recordengine_logfile.php");

  function getLogFile($filename) {
    // prep
    ob_start();
    $tab = new TinyAjaxBehavior();
    
    //create output
    $htmlStr = '';
    $logfile = new DVRUI_Engine_LogFile(DVRUI_Vars::DVR_recPath . '/' . $filename . '.log');
    for ($i=0; $i < $logfile->getNumEntry(); $i++) {
    	$entry = $logfile->getEntryAt($i);
    	$htmlStr .= $entry['Timestamp'];
    	$htmlStr .= $entry['Type'];
    	$htmlStr .= $entry['SubType'];
    	//$htmlStr .= $entry->getFilename();
    	$htmlStr .= $entry['Info'];
    	$htmlStr .= '<br/>';
    }
    
    
    //get data
    $result = ob_get_contents();
    ob_end_clean();
    
    //display
    $tab->add(TabInnerHtml::getBehavior("logfile_box", $htmlStr));
    if ($result != '' && $result != NULL)
      $tab->add(TabInnerHtml::getBehavior("statusMessage", $result));
    return $tab->getString();
  }

  function rmLogFile($logfile) {
    // prep
    ob_start();
    $tab = new TinyAjaxBehavior();
    
    //create output
    $htmlStr = '';
    
    //get data
    $result = ob_get_contents();
    ob_end_clean();
    
    //display
    $tab->add(TabInnerHtml::getBehavior("main_display", $htmlStr));
    if ($result != '' && $result != NULL)
      $tab->add(TabInnerHtml::getBehavior("statusMessage", $result));
    return $tab->getString();
  }


?>
