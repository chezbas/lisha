<?php
	require('header_ajax.php');

	$lisha_type = $_POST['lisha_type'];

	switch($_POST['lisha_type'])
	{
		case __ADV_FILTER__:
			// Advanced filter on a colmun
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->lisha_internal_adv_filter($_POST['column']);
			break;
		case __POSSIBLE_VALUES__:
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->lisha_lov($_POST['column']);
			break;
		case __LOAD_FILTER__:
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->lisha_load_filter_lov();
			break;
		case __COLUMN_LIST__:
			// List of available columns
			echo $_SESSION[$ssid]['lisha'][$lisha_id]->column_list();
			break;
	}