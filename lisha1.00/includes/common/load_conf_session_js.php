<?php
	/**==================================================================
	* Database connexion and load conf parameters in javascript
	====================================================================*/	

	//==================================================================
	// Load configuration in Session
	//==================================================================
	$query = "SELECT
				`id_conf` AS 'id_conf',
				`valeur` AS 'valeur'  
			FROM 
				`".$_SESSION[$ssid]['MT']['struct']['tb_configuration']['name']."`
			WHERE 1 = 1
				AND `application_release` = '".$_SESSION[$ssid]['MT']['application_release']."'";

	$result = $link->query($query);

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