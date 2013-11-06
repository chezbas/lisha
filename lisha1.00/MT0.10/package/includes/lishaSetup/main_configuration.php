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
	define("__LISHA_TABLE_LOCALIZATION__","lisha_i18n");					// Localization information
	define("__LISHA_TABLE_INTERNAL__","lisha_internal");					// internal use ( memory engine )
	//==================================================================
	
	//==================================================================
	// Define Tree documentation tables ( Optional )
	//==================================================================
	// Text for documentation and tickets screen
	define("__LISHA_TABLE_EXTRA_TEXT__","lisha_extra_screen_text");			// Text for documentation and tickets screen			
	define("__LISHA_TABLE_EXTRA_TICK__","bugsreports");						// Contains list of tickets / changelog			
	define("__LISHA_TABLE_EXTRA_TICK_TEXT__","bugstexts");					// Contains texts of bug / ticket screen			
	define("__LISHA_TABLE_EXTRA_TICK_CLAS__","bugsclass");					// Contains status of tickets ( Open, analyse, valid and so on.. )			
	
	// User documentation tree table ( Optional )
	define("__MT_TABLE_USER_DOCU__","lisha_mt_doc_user");					// User tree documentation			
	define("__MT_TABLE_USER_DOCU_CAPTION__","lisha_mt_doc_user_caption");	// User tree documentation caption			

	// Technical documentation tree table ( Optional )
	define("__MT_TABLE_TECH_DOCU__","lisha_mt_doc_tech");					// Technical tree documentation			
	define("__MT_TABLE_TECH_DOCU_CAPTION__","lisha_mt_doc_tech_caption");	// Technical tree documentation caption			
	//==================================================================

	$path_root_lisha =  __LISHA_APPLICATION_RELEASE__;						// Internal use for tickets screen. Don't remove please
?>