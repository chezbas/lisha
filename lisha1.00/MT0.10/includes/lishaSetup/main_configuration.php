<?php
	define("__LISHA_APPLICATION_NAME__","Lisha");							// Lisha name				
	define("__LISHA_APPLICATION_RELEASE__","lisha1.00");					// Lisha package name in use				
	
	//==================================================================
	// General database connexion
	//==================================================================
	define("__LISHA_DATABASE_HOST__","localhost");							// Host			
	define("__LISHA_DATABASE_SCHEMA__","lisha");							// Schema
	define("__LISHA_DATABASE_USER__","adminl");								// user
	define("__LISHA_DATABASE_PASSWORD__","demo");							// password
	//==================================================================
	
	//==================================================================
	// Define lisha main tables
	//==================================================================
	define("__LISHA_TABLE_FILTER__","lisha_filter");						// Record custom view
	define("__LISHA_TABLE_SETUP__","lisha_config");							// General configuration
	define("__LISHA_TABLE_LANGUAGE__","lisha_language");					// Liste of language available
	define("__LISHA_TABLE_TEXT__","lisha_text");							// Text for internal use
	//==================================================================
	
	$path_root_lisha =  __LISHA_APPLICATION_RELEASE__;						// Internal use for tickets screen. Don't remove please
?>