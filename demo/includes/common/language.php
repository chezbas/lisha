<?php
	/**==================================================================
	* Recover language define in URL and check if exist in database
	====================================================================*/	

	// Get the key name of language directive
	// Id position : 2
	$a_valeur_interdite = explode('|',$_SESSION[$ssid]['MT']['configuration'][2]);

	if(!isset($_GET[$a_valeur_interdite[1]]))
	{	
		$_SESSION[$ssid]['MT']['langue'] = $_SESSION[$ssid]['MT']['configuration'][1];
	}
	else
	{
		$fi_lng = $link->real_escape_string($_GET[$a_valeur_interdite[1]]);

		$query = 'SELECT
					1  
				FROM 
					`'.__MAGICTREE_TABLE_LANGUAGE__.'`
				WHERE 1 = 1
					AND `id` = "'.$fi_lng.'"';

		$result = $link->query($query);

		if(mysqli_num_rows($result) == 1)
		{			
			$_SESSION[$ssid]['MT']['langue'] = $fi_lng;
		}
		else
		{
			$_SESSION[$ssid]['MT']['langue'] = $_SESSION[$ssid]['MT']['configuration'][1];	
		}		
	}

	$_GET['lng'] = $_SESSION[$ssid]['MT']['langue'];
	/*===================================================================*/

	/**==================================================================
	* Read id on 2 digits for TinyMCE
	====================================================================*/	
	$query = 'SELECT
				`id_tiny` AS \'tiny\'  
			FROM 
				`'.__MAGICTREE_TABLE_LANGUAGE__.'`
			WHERE 1 = 1
				AND `id` = "'.$_SESSION[$ssid]['MT']['langue'].'"';

	$result = $link->query($query);

	$row = $result->fetch_array(MYSQLI_ASSOC);
	$_SESSION[$ssid]['MT']['langue_TinyMCE'] = $row['tiny'];
	/*===================================================================*/