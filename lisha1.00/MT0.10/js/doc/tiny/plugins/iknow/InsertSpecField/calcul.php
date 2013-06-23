<?php

	/*echo '<pre>';
	print_r($_POST);
	echo '</pre>';*/
	
	echo replace_champs_speciaux($_POST['forme_champ']);
	
	function replace_champs_speciaux($txt,$num)
	{
		// Liste des champs spéciaux:
		$champs[] = '__TOTAL_ETAPE__';
		$champs[] = '__NUM_ETAPE__';
		$champs[] = '__TOTAL_VARIN__';
		$champs[] = '__TOTAL_VAROUT__';
		$champs[] = '__VERSION_FICHE__';
		$champs[] = '__ID_FICHE__';
		$champs[] = '__TOTAL_TAG__';
		
		$replace[] = 1;
		$replace[] = 1;
		$replace[] = 1;
		$replace[] = 1;
		$replace[] = 1;
		$replace[] = 1;
		$replace[] = 1;
		
		$txt = str_replace($champs,$replace,$txt);

		// Gestion de la date
		
		// Dans le contenu des étapes
		preg_match_all('`__DATE\(([^_]+)\)__`',$txt,$out);
		
		foreach($out[1] as $key => $value)
		{
			$out[1][$key] = date($value);
		}

		$txt = str_replace($out[0],$out[1],$txt);

		// Dans les URLs
		preg_match_all('`__DATE%28([a-zA-Z0-9/\\\\:-_*=%]+)%29__`',$txt,$out);
		
		foreach($out[0] as $key_expreg => $value_expreg)
		{
			$out[1][$key_expreg] = date(urldecode($out[1][$key_expreg]));
		}	
		
		$txt = str_replace($out[0],$out[1],$txt);

		return $txt;
	}
?>