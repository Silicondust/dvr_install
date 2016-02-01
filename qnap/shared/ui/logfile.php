<?php
  require_once("TinyAjaxBehavior.php");
  require_once("vars.php");
  //require_once("incldues/dvrui_recordengine_logfile.php");

  function getLogFile($logfile) {
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
