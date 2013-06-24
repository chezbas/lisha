<?php
	/**==================================================================
	 * MagicTree configuration ( Need active php session and ssid definition )
	 ====================================================================*/
	require('header_ajax.php');
	/*===================================================================*/		
		

	//==================================================================
	// Get update mode
	//==================================================================
	if(!isset($_POST["mode"]))
	{
		error_log_details('fatal','you need a mode');
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
	
	$_SESSION[$ssid]['MT'][$tree_id]->update_node($mode,$id_item,$_POST["caption"]);
	
	
	//==================================================================
	// Manage BBCode entities if setup
	//==================================================================
	$final_title = htmlentities($_POST["caption"],ENT_QUOTES);
	
	$final_title =  str_replace(' ', '&nbsp;',$final_title);
	
	if($_SESSION[$ssid]['MT'][$tree_id]->use_bbcode)
	{
		$final_title = $_SESSION[$ssid]['MT'][$tree_id]->convertBBCodetoHTML($final_title);
	}
	
	
	$retour = array("RENDERING" => $final_title,"RAW" => htmlentities($_POST["caption"],ENT_QUOTES));
	echo json_encode($retour);
	
?>