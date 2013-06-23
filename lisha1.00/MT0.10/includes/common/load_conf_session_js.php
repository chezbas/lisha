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
				`id_conf` AS 'id_conf',
				`valeur` AS 'valeur'  
			FROM 
				`".__MAGICTREE_TABLE_SETUP__."`
			WHERE 1 = 1
				AND `application_release` = '".__MAGICTREE_APPLICATION_RELEASE__."'";

	$result = $link_mt->query($query);
	
	/**==================================================================
	* Loading conf in javascript page
	====================================================================*/	
	$s_conf = 'var conf_tree = new Array();';	

	while ($row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$s_conf .= 'conf_tree['.$row['id_conf'].']=\''.rawurlencode($row['valeur']).'\';';	
	}
	
	echo $s_conf;
	/*===================================================================*/
?>