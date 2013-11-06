<?php
	// Get the key name of language directive
	$a_valeur_interdite = explode('|',$_SESSION[$ssid]['lisha']['configuration'][2]);

	if(!isset($_GET[$a_valeur_interdite[4]]))
	{
		$fi_lng = $_SESSION[$ssid]['lisha']['configuration'][1];
	}
	else
	{
		$fi_lng = $link->real_escape_string($_GET[$a_valeur_interdite[4]]);
	}

	// Try this language
	$i18n_js =  file_get_contents($path_root_lisha.'/language/'.$fi_lng.'.json');
	if ($i18n_js === false) {
		// Custom language doesn't exit, force default language from configuration table !!
		$fi_lng = $_SESSION[$ssid]['lisha']['configuration'][1];

		$i18n_js =  file_get_contents($path_root_lisha.'/language/'.$fi_lng.'.json');
	}

	$i18n = json_decode($i18n_js,true);


	foreach($i18n as $key => $value)
	{
		$_SESSION[$ssid]['lisha']['lib'][$key] = $value;
	}

	$_SESSION[$ssid]['lisha']['langue'] = $fi_lng;

	$_GET['lng'] = $_SESSION[$ssid]['lisha']['langue'];