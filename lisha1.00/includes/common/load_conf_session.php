<?php
	/**==================================================================
	* Database connexion
	====================================================================*/	
	require('db_connect.php');
	/*===================================================================*/

	//==================================================================
	// Load configuration in Session
	//==================================================================
	$query = "SELECT
				`id` AS `id`,
				`value` AS `value`  
			FROM 
				`".__LISHA_TABLE_SETUP__."`
			WHERE 1 = 1
			";

	$result = $link->query($query);

	while ($row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$_SESSION[$ssid]['lisha']['configuration'][$row['id']] = $row['value'];
	}
	$result->free(); // Free results
	//==================================================================