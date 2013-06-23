page_text = new Array();<?php
					
	$query = "SELECT
				`id`	 		AS 'ID',
				`long_text` 	AS 'LT',
				`medium_text` 	AS 'MT',
				`short_text` 	AS 'ST',
				`extended_text` AS 'ET'
				FROM `".__MAGICTREE_TABLE_EXTRA_TEXT__."`
			WHERE 1 = 1
				AND `object` = '".$text_group."'
				AND `id_lang` = '".$_SESSION[$ssid]['langue']."' 
			";
	
	$result = $link_mt->query($query);	

	//==================================================================
	// Load screen text
	//==================================================================		
	$w_libelle = '';

	while ($row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$_SESSION[$ssid]['page_text'][$row['ID']]['LT'] = $row['LT']; // Load long text
		$_SESSION[$ssid]['page_text'][$row['ID']]['MT'] = $row['MT']; // Load medium text
		$_SESSION[$ssid]['page_text'][$row['ID']]['ST'] = $row['ST']; // Load short text
		$_SESSION[$ssid]['page_text'][$row['ID']]['ET'] = $row['ET']; // Load extended text

		$w_libelle .= 'page_text["MTLT_'.$row['ID'].'"]=\''.rawurlencode($row['LT']).'\';';
		$w_libelle .= 'page_text["MTMT_'.$row['ID'].'"]=\''.rawurlencode($row['MT']).'\';';
		$w_libelle .= 'page_text["MTST_'.$row['ID'].'"]=\''.rawurlencode($row['ST']).'\';';
		$w_libelle .= 'page_text["MTET_'.$row['ID'].'"]=\''.rawurlencode($row['ET']).'\';';
	}
	//==================================================================		
	
	echo $w_libelle;
?>