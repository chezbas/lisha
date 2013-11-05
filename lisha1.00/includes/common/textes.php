<script type="text/javascript">
var libelle_page = new Array();
<?php
	/**==================================================================
	 * Load label text into both session and javascript area
	 * 		Session 	: 
	 * 						$_SESSION[$ssid]['lisha']['page_text'][id_text_number]['LT']
	 * 						$_SESSION[$ssid]['lisha']['page_text'][id_text_number]['LI']
	 * 						$_SESSION[$ssid]['lisha']['page_text'][id_text_number]['ST']
	 * 
	 * 						$_SESSION[$ssid]['lisha']['global_text'][id_text_number]['LT']
	 * 						$_SESSION[$ssid]['lisha']['global_text'][id_text_number]['LI']
	 * 						$_SESSION[$ssid]['lisha']['global_text'][id_text_number]['ST']
	 * 		Javascript 	: 
	 * 						libelle_global["MTLT_id_text_number"]
	 * 						libelle_global["MTMT_id_text_number"]
	 * 						libelle_global["MTST_id_text_number"]
	 * 	 ====================================================================*/	

	/**==================================================================
	 * Load only text label depend on $type_screen
	 * type_soft means set of screen ( eg : index, admin, search,...) 
	 * See enum value of $_SESSION[$ssid]['lisha']['struct']['tb_libelles']['name'] table field 'object' for further information
	 * In all case, we loaded common label text design by objet = 'global'
	 ====================================================================*/	
	if(isset($type_screen))
	{
		switch($type_screen) 
		{
			case 'bug':
				$objet = 'bug';
				break;
			case 'user':
				$objet = 'user';
				break;
			case 'tech':
				$objet = 'tech';
				break;
			default:
				error_log_details("fatal","Unknown type_soft : ".$type_screen." >");
				break;
		}
	}
	else
	{
		// if no type_soft define, then load only global label
		$objet = "global";
	}

	//==================================================================
	// Read all text on a specific language
	//==================================================================		
	$query = "SELECT
				`id` 		AS 'ID',
				`object`	AS 'OB',
				`texte` 	AS 'TX',
				`corps` 	AS 'BO'
			FROM 
				`".__LISHA_TABLE_EXTRA_TEXT__."`  
			WHERE 1 = 1
				AND `application_release` 	= '".__LISHA_APPLICATION_RELEASE__."'
				AND `object` = '".$objet."'
				AND `id_lang` 				= '".$_SESSION[$ssid]['lisha']['langue']."' 
			ORDER BY 
				`texte`
		   ";

	$result = $link->query($query);

	$w_libelle_common = ''; // Clear local variable

	while ($row = $result->fetch_array(MYSQLI_ASSOC))
	{
		// Specific page text
		// PHP part
		$_SESSION[$ssid]['lisha']['page_text'][$row['ID']]['TX'] = $row['TX']; // Load text

		// Javascript part
		$w_libelle_common .= 'libelle_page["TX_'.$row['ID'].'"]=\''.rawurlencode($row['TX']).'\';';
	}
	/*===================================================================*/
	echo $w_libelle_common.'</script>'; // Store text in javascript page
?>