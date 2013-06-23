<script type="text/javascript">var mt_libelle_global = new Array();
<?php
	/**==================================================================
	 * Load label text into both session and javascript area
	 * 		Session 	: 
	 * 						$_SESSION[$ssid]['MT']['page_text'][id_text_number]['LT']
	 * 						$_SESSION[$ssid]['MT']['page_text'][id_text_number]['MT']
	 * 						$_SESSION[$ssid]['MT']['page_text'][id_text_number]['ST']
	 * 
	 * 						$_SESSION[$ssid]['MT']['global_text'][id_text_number]['LT']
	 * 						$_SESSION[$ssid]['MT']['global_text'][id_text_number]['MT']
	 * 						$_SESSION[$ssid]['MT']['global_text'][id_text_number]['ST']
	 * 		Javascript 	: 
	 * 						libelle_global["MTLT_id_text_number"]
	 * 						libelle_global["MTMT_id_text_number"]
	 * 						libelle_global["MTST_id_text_number"]
	 * 	 ====================================================================*/	

	//==================================================================
	// Read all text on a specific language
	//==================================================================		
	$query = "SELECT
				`id_texte` 		AS 'ID',
				`long_text` 	AS 'LT',
				`medium_text` 	AS 'MT',
				`short_text` 	AS 'ST',
				`extended_text` AS 'ET'
			FROM 
				`".__MAGICTREE_TABLE_TEXT__."`  
			WHERE 1 = 1
				AND `application_release` 	= '".__MAGICTREE_APPLICATION_RELEASE__."'
				AND `id_lang` 				= '".$_SESSION[$ssid]['MT']['langue']."' 
			ORDER BY 
				`id_texte`
		   ";
	$result = $link_mt->query($query);
	
	$w_libelle_common = ''; // Clear local variable

	while ($row = $result->fetch_array(MYSQLI_ASSOC))
	{
		// PHP part
		$_SESSION[$ssid]['MT']['global_text'][$row['ID']]['LT'] = $row['LT']; // Load long text
		$_SESSION[$ssid]['MT']['global_text'][$row['ID']]['MT'] = $row['MT']; // Load medium text
		$_SESSION[$ssid]['MT']['global_text'][$row['ID']]['ST'] = $row['ST']; // Load short text
		$_SESSION[$ssid]['MT']['global_text'][$row['ID']]['ET'] = $row['ET']; // Load extended text
		
		// Javascript part
		$w_libelle_common .= 'mt_libelle_global["MTLT_'.$row['ID'].'"]=\''.rawurlencode($row['LT']).'\';';
		$w_libelle_common .= 'mt_libelle_global["MTMT_'.$row['ID'].'"]=\''.rawurlencode($row['MT']).'\';';
		$w_libelle_common .= 'mt_libelle_global["MTST_'.$row['ID'].'"]=\''.rawurlencode($row['ST']).'\';';
		$w_libelle_common .= 'mt_libelle_global["MTET_'.$row['ID'].'"]=\''.rawurlencode($row['ET']).'\';';
		/*
		$_SESSION[$ssid]['MT']['page_text'][$row['ID']]['LT'] = $row['LT']; // Load long text
		$_SESSION[$ssid]['MT']['page_text'][$row['ID']]['MT'] = $row['MT']; // Load medium text
		$_SESSION[$ssid]['MT']['page_text'][$row['ID']]['ST'] = $row['ST']; // Load short text
		$_SESSION[$ssid]['MT']['page_text'][$row['ID']]['ET'] = $row['ET']; // Load extended text
		
		// Javascript part
		$w_libelle_common .= 'libelle_page["MTLT_'.$row['ID'].'"]=\''.rawurlencode($row['LT']).'\';';
		$w_libelle_common .= 'libelle_page["MTMT_'.$row['ID'].'"]=\''.rawurlencode($row['MT']).'\';';
		$w_libelle_common .= 'libelle_page["MTST_'.$row['ID'].'"]=\''.rawurlencode($row['ST']).'\';';
		$w_libelle_common .= 'libelle_page["MTET_'.$row['ID'].'"]=\''.rawurlencode($row['ET']).'\';';
		*/
	}
	/*===================================================================*/	
	echo $w_libelle_common; // Store text in javascript page
?>
</script>