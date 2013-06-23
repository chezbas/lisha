<?php 
	/**==================================================================
	 * Page buffering ( !! No output ( echo, print_r etc..) before this include !! )
	 ====================================================================*/
	require('../../../../../../includes/common/buffering.php');
	/*===================================================================*/	

	
	require('../../../../../../class/common/class_bdd.php');
	require('../../../../../../class/ifiche/class_cartridge.php');
	require('../../../../../../class/ifiche/class_header.php');
	require('../../../../../../class/ifiche/class_etape.php');
	require('../../../../../../class/ifiche/class_fiche.php');
	require('../../../../../../class/ifiche/class_step.php'); 
	require('../../../../../../class/ifiche/class_check.php');

	
	/**==================================================================
	 * Load common constants
	 ====================================================================*/
	require('../../../../../../includes/common/constante.php');
	/*===================================================================*/	
	
	
	$dir_obj = '';
	require('../../../../../../vimofy/vimofy_includes.php');
	
	
	/**==================================================================
	 * Active php session
	 ====================================================================*/	
	if(isset($_GET['ssid']))
	{
		$ssid = $_GET['ssid'];
	}
	require('../../../../../../includes/common/active_session.php');
	/*===================================================================*/	
?>