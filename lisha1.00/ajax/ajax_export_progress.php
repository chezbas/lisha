<?php
	require('../../includes/lishaSetup/main_configuration.php');

	$path_root_lisha = '../';
	require('../lisha_includes.php');

	// Get ssid
	$ssid = $_POST['ssid'];

	/**==================================================================
	 * Page buffering ( !! No output ( echo, print_r etc..) before this include !! )
	 ====================================================================*/
	require('../includes/common/buffering.php');
	/*===================================================================*/	

	/**==================================================================
	 * Start unique php session with ssid name
	 ====================================================================*/
	require('../includes/common/active_session.php');
	/*===================================================================*/		

	//session_name($ssid);
	//session_start();
	//session_write_close();

	// Get lisha id
	$lisha_id = $_POST['lisha_id'];


	//error_log($_SESSION[$ssid]['lisha']['myexport']);

	if($_SESSION[$ssid]['lisha'][$lisha_id]->export_status == 2)
	{
		// export done
		$status = 'X'; // Export done
	}
	else
	{
		// Export still in progress
		$status = '-'; // Export in progress
	}

	if($_SESSION[$ssid]['lisha'][$lisha_id]->export_status == -1)
	{
		$status = "C";
	}

	$retour = array("STATUS" => $status,"CURRENT" => 0, "TOTAL" => number_format($_SESSION[$ssid]['lisha'][$lisha_id]->export_total,0,'', htmlentities($_SESSION[$ssid]['lisha']['thousand_symbol'])));
	echo json_encode($retour);