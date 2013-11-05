<?php
	/**==================================================================
	 * Load global functions
	 ====================================================================*/	
	require('../../includes/common/global_functions.php');
	/*===================================================================*/	


	/**==================================================================
	 * IE prerequisite
	 * Force header encoding for Internet explorer ajax call
	 * Have to be removed then ie is ok
	 ====================================================================*/	
	require('../../includes/common/header_ajax_ie.php');
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


	/**==================================================================
	 * Lisha configuration ( Need active php session and ssid definition )
	 ====================================================================*/
	require('../../../includes/lishaSetup/main_configuration.php');
	/*===================================================================*/		

	/**==================================================================
	 * Page database connexion
	 ====================================================================*/	
	require('../../includes/common/db_connect.php');
	/*===================================================================*/	


	//==================================================================	
	// switch mode
	//==================================================================	
	if(!isset($_SESSION[$ssid]['lisha']['doc']['tree']['user']))
	{
		$_SESSION[$ssid]['lisha']['doc']['tree']['user'] = true;
	}
	else
	{
		if($_SESSION[$ssid]['lisha']['doc']['tree']['user'])
		{
			$_SESSION[$ssid]['lisha']['doc']['tree']['user'] = false;
		}
		else
		{
			$_SESSION[$ssid]['lisha']['doc']['tree']['user'] = true;
		}
	}
	//==================================================================	
?>