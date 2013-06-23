<?php
	/**==================================================================
	* Database connexion
	====================================================================*/
	require($path_root_lisha.'/includes/common/db_connect.php');
	/*===================================================================*/

	//==================================================================
	// Load lisha localization data
	//==================================================================
	$query = "SELECT
				`id_tiny`,
				`date_format` AS 'date_format'
			FROM 
				`".__LISHA_TABLE_LANGUAGE__."`
			WHERE 1 = 1
				AND `id` ='".$_SESSION[$ssid]['lisha']['langue']."'";
	
	$result = $link->query($query);

	$row = $result->fetch_array(MYSQLI_ASSOC);
	
	$_SESSION[$ssid]['lisha']['langue_TinyMCE'] = $row['id_tiny'];
	$_SESSION[$ssid]['lisha']['date_format'] = $row['date_format'];
	//==================================================================
?>