<?php
/**==================================================================
 * Main configuration and definition for MagicTree
 ====================================================================*/

	define("__MAGICTREE_APPLICATION_NAME","MagicTree");							// Name of application				
	define("__MAGICTREE_APPLICATION_RELEASE__","MT0.10");						// MagicTree package name in use				

	define("__PREFIX_URL_COOKIES__","");										// SSID root prefixe

	//==================================================================
	// General database connexion
	//==================================================================
	define("__MAGICTREE_DATABASE_HOST__","localhost");							// Host			
	define("__MAGICTREE_DATABASE_SCHEMA__","lisha");							// Schema
	define("__MAGICTREE_DATABASE_USER__","adminl");								// user
	define("__MAGICTREE_DATABASE_PASSWORD__","demo");							// password
	//==================================================================

	//==================================================================
	// Define common tables for MagicTreee ( configuration, available languages, textes )
	//==================================================================
	define("__MAGICTREE_TABLE_SETUP__","mt_conf");								// General configuration of magic tree
	define("__MAGICTREE_TABLE_LANGUAGE__","mt_lang");							// Liste of language available
	define("__MAGICTREE_TABLE_TEXT__","mt_text");								// Text for internal use
	//==================================================================

	$path_root_magictree =  __MAGICTREE_APPLICATION_RELEASE__;					// Internal use for tickets screen. Don't remove please