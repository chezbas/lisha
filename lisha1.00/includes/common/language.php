<?php
	// Get the key name of language directive
	$a_valeur_interdite = explode('|',$_SESSION[$ssid]['lisha']['configuration'][2]);

	if(!isset($_GET[$a_valeur_interdite[4]]))
	{	
		$_SESSION[$ssid]['lisha']['langue'] = $_SESSION[$ssid]['lisha']['configuration'][1];
	}
	else
	{
		$fi_lng = $link->real_escape_string($_GET[$a_valeur_interdite[4]]);

		$query = 'SELECT
					1  
				FROM 
					`'.__LISHA_TABLE_LANGUAGE__.'`
				WHERE 1 = 1
					AND `id` = "'.$fi_lng.'"';
		$result = $link->query($query);

		if(mysqli_num_rows($result) == 1)
		{			
			$_SESSION[$ssid]['lisha']['langue'] = $fi_lng;
		}
		else
		{
			$_SESSION[$ssid]['lisha']['langue'] = $_SESSION[$ssid]['lisha']['configuration'][1];	
		}		
	}

	$_GET['lng'] = $_SESSION[$ssid]['lisha']['langue'];
