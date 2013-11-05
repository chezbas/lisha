<?php
	require('header_ajax.php');

	switch($_POST['action'])
	{
		case 1:
			// Load LMOD lisha
			echo $_SESSION['lisha'][$ssid][$lisha_id]->generate_lmod_content();
			break;

	}