<?php
	require_once("TinyAjaxBehavior.php");
	require_once("vars.php");
	require_once("statusmessage.php");
	require_once("includes/dvrui_hdhrjson.php");
	require_once("includes/dvrui_rules.php");
	
	function openRulesPage() {
		// prep
		ob_start();
		$tab = new TinyAjaxBehavior();

		//create output
		$htmlStr = getRecordingRules();

		//get data
		$result = ob_get_contents();
		ob_end_clean();

		// get latest status	
		$statusmsg = getLatestHDHRStatus();
	
		//display
		$tab->add(TabInnerHtml::getBehavior("rules_box", $htmlStr));
		if ($result != '' && $result != NULL)
			$tab->add(TabInnerHtml::getBehavior("statusMessage", $result));
		else
			$tab->add(TabInnerHtml::getBehavior("statusMessage", $statusmsg));
		return $tab->getString();
	}

	function getRecordingRules() {
		$rulesStr = '';
		
		// Discover Recording Rules
		$hdhr = new DVRUI_HDHRjson();
		$hdhrRules = new DVRUI_Rules($hdhr);
		$numRules = $hdhrRules->getRuleCount();
		$rulesData = $hdhrRules->getExecutionTime() . '<br/>';
		for ($i=0; $i < $numRules; $i++) {
			$rulesData .= $hdhrRules->getRuleString($i) . '<br/>';
		}
		$rulesStr = file_get_contents('style/rules.html');
		$rulesStr = str_replace('<!-- dvrui_rules_data -->',$rulesData,$rulesStr);
		
		return $rulesStr;
	}
?>