<?php
	//==================================================================
	// Main database connexion schema
	//==================================================================
	$link_mt = new mysqli(__MAGICTREE_DATABASE_HOST__, __MAGICTREE_DATABASE_USER__, __MAGICTREE_DATABASE_PASSWORD__, __MAGICTREE_DATABASE_SCHEMA__);
	if ($link_mt->connect_errno)
	{
	    error_log("Database connexion error : ".$link_mt->connect_error);
	    die();
	}
	$link_mt->set_charset("utf8"); // Usefull for php 5.4
	//==================================================================
?>