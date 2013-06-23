<?php
	/**==================================================================
	 * MagicTree configuration ( Need active php session and ssid definition )
	 ====================================================================*/
	require('header_ajax.php');
	/*===================================================================*/		

	
	//==================================================================
	// Get creation mode
	// C : Means do a children item
	// B : Means do a brother item
	//==================================================================
	if(!isset($_POST["mode"]))
	{
		error_log_details('fatal','you have to define always a mode');
		die();
	}
	$mode= $_POST["mode"];
	//==================================================================		

	//==================================================================
	// Get ID item node
	//==================================================================
	if(!isset($_POST["IDitem"]))
	{
		error_log_details('fatal','you need an item ID');
		die();
	}
	$id_item= $_POST["IDitem"];
	//==================================================================

	$_SESSION[$ssid]['MT'][$tree_id]->add_node($mode,$id_item,$_POST["caption"]);
?>