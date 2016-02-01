<?php
  require_once("TinyAjaxBehavior.php");
  require_once("vars.php");

  function updateRecordPath() {
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