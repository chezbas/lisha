<?php
	if(!isset($_SESSION['demo'][$ssid]['identified_level']) || !$_SESSION['demo'][$ssid]['identified_level'])
	{
		// No identification
		header('Location: '.$_SESSION['demo'][$ssid]['level_require_path'].'check_id.php?'.$url_param);
		die();
	}
	else
	{
		// Login ok, continue
	}