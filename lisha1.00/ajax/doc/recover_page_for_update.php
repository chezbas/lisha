<?php
	/**==================================================================
	 * Set of documentation framework includes
	 ====================================================================*/
	require('header.php');
	/*===================================================================*/		


	/**==================================================================
	 * Page database connexion
	 ====================================================================*/	
	require('../../includes/common/db_connect.php');
	/*===================================================================*/	


	//==================================================================
	// Controle current page exists
	//==================================================================
	if(!isset($_SESSION[$ssid]['current_read_page']))
	{
		error_log_details('fatal','you need a current page');
		die();
	}
	//==================================================================	

	//==================================================================
	// Get language in use
	//==================================================================
	if(!isset($_POST["language"]))
	{
		error_log_details('fatal','you need an language');
		die();
	}
	$language= $_POST["language"];
	//==================================================================	

	$query = "	SELECT
					`MTC`.`description` AS 'DETAILS'
				FROM `".$_SESSION[$ssid]['MT']['ikdoc']->tree_caption."` `MTC`
			WHERE
				 `MTC`.`application_release` = '".__MAGICTREE_APPLICATION_RELEASE__."'
				 AND `MTC`.`language` = '".$language."'
				 AND `MTC`.`id` = ".$_SESSION[$ssid]['current_read_page'].";
			";

	$result = $link->query($query);
	$row = $result->fetch_array(MYSQLI_ASSOC);

	// Try default language if no local language defined
	if( ( !isset($row["DETAILS"]) || $row["DETAILS"] == '' )  && $_SESSION[$ssid]['MT']['configuration'][1] != $language )
	{
		// No page define for this page, recover default language page
		$query = "	SELECT 
						`MTC`.`description` AS 'DETAILS'
					FROM `".$_SESSION[$ssid]['MT']['ikdoc']->tree_caption."` `MTC`
				WHERE
					`MTC`.`application_release` = '".__MAGICTREE_APPLICATION_RELEASE__."'
					 AND `MTC`.`language` = '".$_SESSION[$ssid]['MT']['configuration'][1]."'
					 AND `MTC`.`id` = ".$_SESSION[$ssid]['current_read_page']."
				";
		$result = $link->query($query);
		$row = $result->fetch_array(MYSQLI_ASSOC);
	}

	$html = $row['DETAILS'];

	echo $html;