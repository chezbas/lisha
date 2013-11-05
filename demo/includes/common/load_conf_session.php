<?php
	/**==================================================================
	* Database connexion
	====================================================================*/	
	require('db_connect.php');
	/**===================================================================*/

	//==================================================================
	// Load configuration in Session
	//==================================================================
	$query = "SELECT
				`id_conf`,
				`valeur`  
			FROM 
				`".__MAGICTREE_TABLE_SETUP__."`
			WHERE 1 = 1
				AND `application_release` = '".__MAGICTREE_APPLICATION_RELEASE__."'";

	$result = $link->query($query);

	while ($row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$_SESSION[$ssid]['MT']['configuration'][$row['id_conf']] = $row['valeur'];
	}
	$result->free(); // Free results
	//==================================================================