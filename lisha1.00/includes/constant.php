<?php	
	define("__R__",true);											// Readonly
	define("__RW__",false);											// Read & Write
	define("__TEXT__","text");										// text
    define("__RAW__","raw");										// Raw : no translation or conversion
	define("__BBCODE__","bbcode");									// bbcode
	define("__DATE__","date");										// date
	define("__CHECKBOX__","checkbox");								// checkbox type
	define("__INT__", "int");                                       // integer
	define("__FLOAT__", "float");                                   // float
	define("__LEFT__","left");										// left
	define("__RIGHT__","right");									// right
	define("__CENTER__","center");									// center
	define("__NOWRAP__"," nowrap");									// nowrap
	define("__WRAP__","");											// wrap
	define("__LMOD__","lmod");										// List mode
	define("__NMOD__","nmod");										// Normal mode
	define("__CMOD__","child");										// child mode
	define("__SIMPLE__","simple");									// LMOD simple return
	define("__MULTIPLE__","multiple");								// LMOD multiple return
	define("__NONE__","");			    							// None
	define("__ASC__","ASC");										// Ascending order
	define("__DESC__","DESC");										// Descending order
	define("__ADD__","ADD");										// Add an order clause
	define("__NEW__","NEW");										// Add a new order clause and delete other order clause
	define("__MYSQL__","mysql");									// MySQL engine
	define("__POSTGRESQL__","postgresql");							// PostgreSQL engine
	define("__ODBC__","odbc");										// ODBC conection
	define("__MSSQLSERVER__","mssqlserver");						// Microsoft SQL Server engine
	define("__CONTAIN__","__CONTAIN__");							// Operator contain means a LIKE with %%
	define("__PERCENT__","%");										// Operator percent LIKE without any jocker digit
	define("__EXACT__","");											// Operator equal
	define("__GT__","__GT__");									    // Operator greather than
	define("__GE__","__GE__");									    // Operator greather or equal than
	define("__LT__","__LT__");									    // Operator less than
	define("__LE__","__LE__");									    // Operator less or equal than
	define("__NULL__","__NULL__");									// Operator is null
	define("__ADV_FILTER__","__ADV_FILTER__");						// Sublisha of column advanced filter
	define("__POSSIBLE_VALUES__","__POSSIBLE_VALUES__");			// Possible values
	define("__LOAD_FILTER__","__LOAD_FILTER__");					// Load a filter
	define("__HEADER__","header");									// header
	define("__FOOTER__","footer");									// footer
	define("__FORBIDDEN__","forbidden");								// forbidden value
	define("__REQUIRED__","required");								// Required value
	define("__LISTED__","listed");									// Only if the value is in the LOV
	define("__HIDE__",false);										// Hide the column
	define("__DISPLAY__",true);										// Display the column
	define("__EDIT_MODE__","__EDIT_MODE__");						// lisha in edit mode
	define("__DISPLAY_MODE__","__DISPLAY_MODE__");					// lisha in display mode
	define("__COLUMN_LIST__","__COLUMN_LIST__");					// Sublisha of column list
	define("__HIDE_DISPLAY_COLUMN__","__HIDE_DISPLAY_COLUMN__");	// Hide/Display column menu
	define("__BEFORE__","__BEFORE__");								// __BEFORE__
	define("__AFTER__","__AFTER__");								// __AFTER__
	define("__RELATIVE__","__RELATIVE__");							// __RELATIVE__
	define("__ABSOLUTE__","__ABSOLUTE__");							// __ABSOLUTE__

	// Events 
	define("__ON_LMOD_INSERT__","__ON_LMOD_INSERT__");				// Event when a line was clicked for insert on an LMOD lisha
	define("__ON_UPDATE__","__ON_UPDATE__");						// Event when a line was updated on the lisha
	define("__ON_ADD__","__ON_ADD__");								// Event when a line was added
	define("__ON_DELETE__","__ON_DELETE__");						// Event when a line was deleted
	define("__ON_REFRESH__","__ON_REFRESH__");						// Event when a line lisha was refreshed

	// Actions
	define("__LMOD_OPEN__","__LMOD_OPEN__");						// Open a LMOD lisha

	// Javascript events constant
	define("__lisha_INTERNAL__","__LISHA_INTERNAL__");
	define("__lisha_EXTERNAL__","__LISHA_EXTERNAL__");
	define("__lisha_NEXT__","__NEXT__");
	define("__lisha_PREVIOUS__","__PREVIOUS__");
	define("__lisha_FIRST__","__FIRST__");
	define("__lisha_LAST__","__LAST__");