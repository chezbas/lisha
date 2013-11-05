<?php
	//==================================================================
	// Main database connexion schema
	//==================================================================
	$link = new mysqli(__LISHA_DATABASE_HOST__, __LISHA_DATABASE_USER__, __LISHA_DATABASE_PASSWORD__, __LISHA_DATABASE_SCHEMA__);
	if ($link->connect_errno)
	{
		error_log("Database connexion error : ".$link->connect_error);
		die();
	}
	$link->set_charset("utf8"); // Usefull for php 5.4
	//==================================================================