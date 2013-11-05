<?php
	/**==================================================================
	 * Load global functions
	 ====================================================================*/	
	require('../../includes/common/global_functions.php');
	/*===================================================================*/	

	/**==================================================================
	 * Get ssid window identifier
	 * Start unique php session with ssid name
	 ====================================================================*/
	if(!isset($_POST["ssid"]))
	{
		error_log_details('fatal','you have to define always a ssid');
		die();
	}
	$ssid = $_POST["ssid"];
	require('../../includes/common/active_session.php');
	/*===================================================================*/	

	echo $_SESSION[$ssid]['current_read_page'];