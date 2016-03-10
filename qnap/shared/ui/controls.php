<?php
	require_once("TinyAjaxBehavior.php");
	require_once("vars.php");
	require_once("statusmessage.php");
	require_once("includes/dvrui_hdhrcontrols.php");
	
	function changeDvrState($option) {
		// prep
		ob_start();
		$tab = new TinyAjaxBehavior();
	
		//create output
		$htmlStr = 'Changing State of the DVR: ' . $option;

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

?>