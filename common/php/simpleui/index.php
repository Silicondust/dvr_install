<?php

	require_once("vars.php");
	require_once("includes/dvrui_debug.php");

	define('TINYAJAX_PATH', '.');
	require_once("TinyAjax.php");
	require_once("TinyAjaxBehavior.php");

	require_once("includes/dvrui_hdhrbintools.php");
	require_once("statusmessage.php");
	require_once("dashboard.php");
	require_once("diagnostics.php");

	/* Prepare Ajax */
	error_log( "Setting up Ajax" );
	$ajax = new TinyAjax();
	$ajax->setRequestType("POST");    // Change request-type from GET to POST
	$ajax->showLoading();             // Show loading while callback is in progress
	
	/* Export the PHP Interface */
	error_log( "Setting up Ajax functions" );
	$ajax->exportFunction("getLogFile", "filename");
	$ajax->exportFunction("updateServerConfig","port, path");
	$ajax->exportFunction("openDashboard","");
	$ajax->exportFunction("openDiagnosticsPage","");
	
	/* GO */
	error_log( "Enable Ajax" );
	$ajax->process(); // Process our callback

	// Prep data for the page
	error_log( "Get latest Status" );
	$statusmsg = getLatestHDHRStatus();

	// Get HDHR Version
	error_log( "Get HDHR DVR version" );
	$DVRBin = new DVRUI_HDHRbintools();
	$DVRBinVersion = $DVRBin->get_DVR_version();
	
	//Build navigation menu for pages
	error_log( "Build Navigation Pages" );
	$pageTitles = array('Dashboard', '.');
	$pageNames = array('dashboard_page', 'diagnostics_page');
	$menu_data = file_get_contents('style/pagemenu.html');
	$menuEntries = '';
	$firstPage = true;
	for ($i=0; $i < count($pageNames); $i++) {
		$menuEntry = str_replace('<!-- dvrui_menu_pagename-->',$pageNames[$i],file_get_contents('style/pagemenu_entry.html'));
		$menuEntry = str_replace('<!-- dvrui_menu_pagetitle-->',$pageTitles[$i],$menuEntry);
		if ($firstPage) {
			$menuEntry = str_replace('<!--dvrui_default_page_option-->','id="defaultPage"',$menuEntry);
			$firstPage = false;
		}
		$menuEntries .= $menuEntry;
	}
	$menu_data = str_replace('<!-- dvrui_pagemenu_entries-->',$menuEntries,$menu_data);
	
	// --- Build Page Here ---
	error_log( "Build Index Page - get version" );
	$pageName = DVRUI_Vars::DVRUI_name;
	$UIVersion = "Version:" . DVRUI_Vars::DVRUI_version;
	$DVRVersion = "Record Engine Version: <i>" . $DVRBinVersion . "</i>";
	$pagecontent = "";

	// --- include header ---
	error_log( "Build Index Page - header" );
	$header = file_get_contents('style/header.html');
	$pagecontent = str_replace('[[pagetitle]]',$pageName,$header);
	$pagecontent = str_replace('<!-- tinyAjax -->',$ajax->drawJavaScript(false, true),$pagecontent);

	// --- Build Body ---
	error_log( "Build Index Page - body" );
	$indexPage = file_get_contents('style/index_page.html');
	$topmenu = file_get_contents('style/topmenu.html');
	$dashboarddata = file_get_contents('style/dashboard.html');
	$diagnosticsdata = file_get_contents('style/diagnostics.html');

	$topmenu = str_replace('[[pagetitle]]',$pageName,$topmenu);
	$topmenu = str_replace('[[UI-Version]]',$UIVersion,$topmenu);
	$topmenu = str_replace('[[DVR-Version]]',$DVRVersion,$topmenu);

	$indexPage = str_replace('<!-- dvrui_topmenu -->',$topmenu,$indexPage);
	$indexPage = str_replace('<!-- dvrui_pagemenu -->',$menu_data,$indexPage);
	$indexPage = str_replace('<!-- dvrui_dashboard -->',$dashboarddata,$indexPage);
	$indexPage = str_replace('<!-- dvrui_diagnostics -->',$diagnosticsdata,$indexPage);

	// -- Attach the Index to the Page
	error_log( "Build Index Page - attach to page" );
	$pagecontent .= $indexPage;

	// --- include footer ---
	error_log( "Build Index Page - footer" );
	$footer = file_get_contents('style/footer.html');
	$footer = str_replace('<!--dvr-statusmsg-->',$statusmsg,$footer);
	$pagecontent .= $footer;

	error_log( "Output page" );
	echo($pagecontent);
	error_log( "======= Debug Log END =========" );
?>

