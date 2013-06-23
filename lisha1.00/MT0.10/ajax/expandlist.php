<?php
	/**==================================================================
	 * MagicTree configuration ( Need active php session and ssid definition )
	 ====================================================================*/
	require('header_ajax.php');
	/*===================================================================*/		

	//==================================================================
	// ID item
	//==================================================================
	if(!isset($_POST["IDitem"]))
	{
		error_log_details('fatal','you need an item ID');
		die();
	}
	$id_item= $_POST["IDitem"];
	//==================================================================

	//==================================================================
	// Action
	//==================================================================
	if(!isset($_POST["action"]))
	{
		error_log_details('fatal','action is mandatory');
		die();
	}
	$action = $_POST["action"];
	//==================================================================
	
	switch ($action)
	{
		case 0: // Remove item from expansion list
			if(isset($_SESSION[$ssid]['MT'][$tree_id]->listexpand[$id_item]))
			{
				unset($_SESSION[$ssid]['MT'][$tree_id]->listexpand[$id_item]);
			}	
		break;
		case 1: // Add item from expansion list
			$_SESSION[$ssid]['MT'][$tree_id]->listexpand[$id_item] = true;
		break;	
	}
	
	// Remember node focus just expanded or collapsed
	$_SESSION[$ssid]['MT'][$tree_id]->myfocus = $id_item;
?>