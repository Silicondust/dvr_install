<?php
	require_once("TinyAjaxBehavior.php");
	require_once("vars.php");
	require_once("statusmessage.php");
	require_once("includes/dvrui_hdhrjson.php");
	require_once("includes/dvrui_search.php");
	
	function openSearchPage($searchString) {
		// prep
		ob_start();
		$tab = new TinyAjaxBehavior();

		//create output
		$htmlStr = getSearchResults($searchString);

		//get data
		$result = ob_get_contents();
		ob_end_clean();

		// get latest status	
		$statusmsg = getLatestHDHRStatus();
	
		//display
		$tab->add(TabInnerHtml::getBehavior("search_box", $htmlStr));
		if ($result != '' && $result != NULL)
			$tab->add(TabInnerHtml::getBehavior("statusMessage", $result));
		else
			$tab->add(TabInnerHtml::getBehavior("statusMessage", $statusmsg));
		return $tab->getString();
	}

	function getSearchResults($searchString) {
		$searchStr = '';
		$hdhr = new DVRUI_HDHRjson();
		$hdhrSearchResults = new DVRUI_Search($hdhr, $searchString);
		$numResults = $hdhrSearchResults->getSearchResultCount();
		$searchData = '';
		for ($i=0; $i < $numResults; $i++) {
			$searchEntry = file_get_contents('style/search_entry.html');
			$searchEntry = str_replace('<!-- dvr_search_image -->',$hdhrSearchResults->getSearchResultImage($i),$searchEntry);
			$searchEntry = str_replace('<!-- dvr_search_title -->',$hdhrSearchResults->getSearchResultTitle($i),$searchEntry);
			$searchEntry = str_replace('<!-- dvr_search_synopsis -->',$hdhrSearchResults->getSearchResultSynopsis($i),$searchEntry);
			$searchEntry = str_replace('<!-- dvr_record_recent -->',$hdhrSearchResults->getRecordRecentURL($i),$searchEntry);
			$searchEntry = str_replace('<!-- dvr_record_all -->',$hdhrSearchResults->getRecordAllURL($i),$searchEntry);
			$searchData .= $searchEntry;
		}
		$searchList = file_get_contents('style/search_list.html');
		$searchList = str_replace('<!-- dvr_search_count -->','Found: ' . $numResults . ' Results<br/>',$searchList);
		$searchList = str_replace('<!-- dvr_search_list -->',$searchData,$searchList);

		
		return $searchList;
	}
?>
