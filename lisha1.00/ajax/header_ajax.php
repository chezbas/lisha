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

	// Get lisha id
	$lisha_id = $_POST['lisha_id'];

	// Page header
	header('Content-type: text/html; charset=UTF-8');