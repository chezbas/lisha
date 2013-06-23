<?php
	/**==================================================================
	 * MagicTree configuration ( Need active php session and ssid definition )
	 ====================================================================*/
	require('header_ajax.php');
	/*===================================================================*/		

	//==================================================================
	// Expend mode
	// 0 means reduce all
	// 1 means expand all
	//==================================================================
	if(!isset($_POST["expend_mode"]))
	{
		// No value set by javascript call
		if(!isset($_SESSION[$ssid]['MT'][$tree_id]->expand_mode))
		{
			// No value already define in php session, set default value
			$_SESSION[$ssid]['MT'][$tree_id]->expand_mode = 0; // Default value if not set : Reduce all
		}
	}
	else
	{
		// A new value is set by javascript call, load it in php session to keep information
		$_SESSION[$ssid]['MT'][$tree_id]->expand_mode = $_POST["expend_mode"];
	}
	//==================================================================
	
	//==================================================================
	// Item number to focus
	// null or undefined means no focus
	//==================================================================
	if(!isset($_POST["focus"]) || $_POST["focus"] == '' )
	{
		// No value set by javascript call
		if(!isset($_SESSION[$ssid]['MT'][$tree_id]->myfocus))
		{
			// No value already define in php session, set default value
			$_SESSION[$ssid]['MT'][$tree_id]->myfocus = null;	// Means no focus
		}
	}
	else
	{
		// A new value is set by javascript call, load it in php session to keep information
		$_SESSION[$ssid]['MT'][$tree_id]->myfocus = $_POST["focus"];
	}
	//==================================================================
	
	$_SESSION[$ssid]['MT'][$tree_id]->inputsearch = '';
	
	//==================================================================
	// Action mode
	//==================================================================
	if(isset($_POST["action"]))
	{
		switch ($_POST["action"])
		{
			case 1: // Do search
				$_SESSION[$ssid]['MT'][$tree_id]->inputsearch = $_POST['searchinput'];
			break;
			case 2: // Clear search
				$_SESSION[$ssid]['MT'][$tree_id]->inputsearch = '';
			break;
			case 3: // Mark - Unmark
				// Nothing special here
			case 4: // Refresh
				if(isset($_POST['searchinput']))
				{
					$_SESSION[$ssid]['MT'][$tree_id]->inputsearch = $_POST['searchinput'];
				}
			break;
		}
	}
	else
	{
		$_POST["action"] = null;
	}
	
	$_SESSION[$ssid]['MT'][$tree_id]->action = $_POST["action"];
	//==================================================================
	
	$start_hour =  microtime(true); // Record start time

	$_SESSION[$ssid]['MT'][$tree_id]->draw_tree();
	
	$end_hour =  microtime(true); // Record start time
	
	//error_log('Temps (ms) : '.round($end_hour - $start_hour,6)*1000);
?>