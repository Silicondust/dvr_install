<?php
	require_once("TinyAjaxBehavior.php");
	require_once("vars.php");
	require_once("statusmessage.php");
	require_once("includes/dvrui_hdhrjson.php");
	require_once("includes/dvrui_recordings.php");
	
	function openRecordingsPage() {
		// prep
		ob_start();
		$tab = new TinyAjaxBehavior();

		//create output
		$htmlStr = getRecordings();

		//get data
		$result = ob_get_contents();
		ob_end_clean();

		// get latest status	
		$statusmsg = getLatestHDHRStatus();
	
		//display
		$tab->add(TabInnerHtml::getBehavior("recordings_box", $htmlStr));
		if ($result != '' && $result != NULL)
			$tab->add(TabInnerHtml::getBehavior("statusMessage", $result));
		else
			$tab->add(TabInnerHtml::getBehavior("statusMessage", $statusmsg));
		return $tab->getString();
	}

	function getRecordings() {
		$recordingsStr = '';
		// Discover Recording
		$hdhr = new DVRUI_HDHRjson();
		$hdhrRecordings = new DVRUI_Recordings($hdhr);
		$numRecordings = $hdhrRecordings->getRecordingCount();
		$recordingsData = 'Storage URL: ' . $hdhr->get_storage_url() . '<br/>';
		$recordingsData .= 'Found: ' . $numRecordings . ' Recordings<br/>';
		$recordingsData .= "<TABLE>";
		for ($i=0; $i < $numRecordings; $i++) {
			$recordingsData .= $hdhrRecordings->getRecordingString($i) ;
		}
		$recordingsData .= "</TABLE>";
		$recordingsStr = file_get_contents('style/recordings.html');
		$recordingsStr = str_replace('<!-- dvrui_recordings_data -->',$recordingsData,$recordingsStr);
		
		return $recordingsStr;
	}
?>
