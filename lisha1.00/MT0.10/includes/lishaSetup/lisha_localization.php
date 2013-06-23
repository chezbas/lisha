<?php
	/**==================================================================
	* Database connexion
	====================================================================*/	
	require($path_root_lisha.'/includes/common/db_connect.php');
	/*===================================================================*/

	/**==================================================================
	* Read id on 2 digits for TinyMCE
	====================================================================*/	
	$query = 'SELECT
				`id_tiny`,
				`date_format` AS \'date_format\'
			FROM 
				`'.__LISHA_TABLE_LANGUAGE__.'`
			WHERE 1 = 1
				AND `id` = "'.$_SESSION[$ssid]['langue'].'"';
	
	$result = $link->query($query);

	$row = $result->fetch_array(MYSQLI_ASSOC);
	
	$_SESSION[$ssid]['langue_TinyMCE'] = $row['id_tiny'];
	$_SESSION[$ssid]['date_format'] = $row['date_format'];
	/*===================================================================*/
?>