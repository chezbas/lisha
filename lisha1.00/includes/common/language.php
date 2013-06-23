<?php
	// Get the key name of language directive
	$a_valeur_interdite = explode('|',$_SESSION[$ssid]['lisha']['configuration'][2]);
	
	if(!isset($_GET[$a_valeur_interdite[1]]))
	{	
		$_SESSION[$ssid]['lisha']['langue'] = $_SESSION[$ssid]['lisha']['configuration'][1];
	}
	else
	{
		$fi_lng = $link->real_escape_string($_GET[$a_valeur_interdite[1]]);
		
		$query = 'SELECT
					1  
				FROM 
					`'.$_SESSION[$ssid]['lisha']['struct']['tb_lang']['name'].'`
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
?>