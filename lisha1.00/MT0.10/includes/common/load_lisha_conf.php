<?php
	/**==================================================================
	* Database connexion
	====================================================================*/	
	require('db_connect.php');
	/*===================================================================*/

	//==================================================================
	// Load configuration data into php session
	//==================================================================
	$query = "SELECT
				`NAM`.`id` AS `id`,
				`NAM`.`value` AS `value`  
			FROM
				`".__LISHA_TABLE_SETUP__."` NAM
			WHERE 1 = 1
			";
	$result = $link_mt->query($query);
	
	while ($row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$_SESSION[$ssid]['lisha']['configuration'][$row['id']] = $row['value'];
	}
	$result->free(); // Free results
	//==================================================================
?>