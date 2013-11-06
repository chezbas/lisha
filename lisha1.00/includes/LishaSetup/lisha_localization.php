<?php
	/**==================================================================
	* Database connexion
	====================================================================*/
	require($path_root_lisha.'/includes/common/db_connect.php');
	/**===================================================================*/

	/**==================================================================
	* Load lisha localization data
	====================================================================*/
	$query = "SELECT
				`id_tiny`,
				`date_format` 		AS 'date_format',
				`decimal_symbol` 	AS 'decimal_symbol',
				`thousand_symbol` 	AS 'thousand_symbol',
				`number_of_decimal` AS 'number_of_decimal'
			FROM 
				`".__LISHA_TABLE_LOCALIZATION__."`
			WHERE 1 = 1
				AND `id` ='".$_SESSION[$ssid]['lisha']['langue']."'";

	$result = $link->query($query);

	$row = $result->fetch_array(MYSQLI_ASSOC);

	$_SESSION[$ssid]['lisha']['langue_TinyMCE'] = $row['id_tiny'];
	$_SESSION[$ssid]['lisha']['date_format'] = $row['date_format'];
	$_SESSION[$ssid]['lisha']['decimal_symbol'] = $row['decimal_symbol'];
	$_SESSION[$ssid]['lisha']['thousand_symbol'] = $row['thousand_symbol'];
	$_SESSION[$ssid]['lisha']['number_of_decimal'] = $row['number_of_decimal'];
	/**===================================================================*/