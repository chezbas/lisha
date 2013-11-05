<?php
	//==================================================================
	// Main database connexion schema
	//==================================================================
	$link = new mysqli(__MAGICTREE_DATABASE_HOST__, __MAGICTREE_DATABASE_USER__, __MAGICTREE_DATABASE_PASSWORD__, __MAGICTREE_DATABASE_SCHEMA__);
	if ($link->connect_errno)
	{
		error_log("Database connexion error : ".$link->connect_error);
		die();
	}
	$link->set_charset("utf8"); // Usefull for php 5.4
	//==================================================================