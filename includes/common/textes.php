libelle = new Array();<?php

	$query = "SELECT
				`id_texte` AS id_texte,
				`texte` AS texte
			FROM `demo_screen_texts`
			WHERE 1 = 1
				AND `id_lang` = '".$_SESSION[$ssid]['langue']."' 
			";

	$result = $link->query($query);	

	/**==================================================================
	 * Load label text
	 ====================================================================*/	
	$w_libelle = '';

	while ($row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$_SESSION[$ssid]['message'][$row['id_texte']] = $row['texte']; // Load label in php session
		$w_libelle .= 'libelle['.$row['id_texte'].']=\''.rawurlencode($row['texte']).'\';'; // Load label in javascript page			
	}
	/*===================================================================*/	

	echo $w_libelle;
	$ikn_txt = &$_SESSION[$ssid]['message'];
?>