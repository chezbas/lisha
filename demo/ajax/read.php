<?php
	/**==================================================================
	 * MagicTree configuration
	====================================================================*/
	$path_root_framework = '../../';	// MagicTree framework is parent
	require('../../includes/MTSetup/setup.php');
	/**===================================================================*/

	$path_root_magictree = '../../MT0.10';
	require($path_root_magictree.'/page_includes.php');

	if(!isset($_POST["attribute"]))
	{
		error_log('fatal','you have to define an attributes name');
		die();
	}
	$attribute = $_POST["attribute"];

	if(!isset($_POST["ssid"]))
	{
		error_log('fatal','you have to define always a ssid');
		die();
	}
	$ssid = $_POST["ssid"];

	if(!isset($_POST["id"]))
	{
		error_log('fatal','you have to define internal id');
		die();
	}
	$internal_id = $_POST["id"];

	session_name($ssid);
	session_start();

	// Force node calcul
	$_SESSION[$ssid]['MT'][$internal_id]->count_node();

	echo $_SESSION[$ssid]['MT'][$internal_id]->read_attribute($attribute);