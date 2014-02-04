<?php 
	$lisha1_id = 'lisha_demo_list';

	// Force lisha language from framework language
	//$_GET['lng'] = $_SESSION[$ssid]['langue']; // Already done by language include of main page

	// Use framework connexion information from framework
	$_SESSION[$ssid]['lisha'][$lisha1_id] = new lisha(
														$lisha1_id,
														$ssid,
														__MYSQL__,
														array('user' => __LISHA_DATABASE_USER__,'password' => __LISHA_DATABASE_PASSWORD__,'host' => __LISHA_DATABASE_HOST__,'schema' => __LISHA_DATABASE_SCHEMA__),
														$path_root_lisha,
														false);	// Type of internal lisha ( false by default )

	// Create a reference to the session
	$obj_lisha_tran = &$_SESSION[$ssid]['lisha'][$lisha1_id];

	//==================================================================
	// Define main query
	//==================================================================
	$query = "
			SELECT
				`demo_list_example`.`id`			AS `id`,
				`demo_list_example`.`name`			AS `name`,
				`demo_list_example`.`description`	AS `description`,
				`demo_list_example`.`link`	AS `link`,
				`demo_list_example`.`screen`		AS `screen`,
				`demo_list_example`.`last_update`	AS `last_update`,
				`demo_list_example`.`title`			AS `title`
			".$_SESSION[$ssid]['lisha']['configuration'][10]."
				`demo_list_example`
				WHERE 1 = 1
				";
	$obj_lisha_tran->define_attribute('__main_query', $query);
	//==================================================================

	//==================================================================
	// Lisha display setup
	//==================================================================
	$obj_lisha_tran->define_nb_line(20);											// Row by page								
	$obj_lisha_tran->define_size(100,'%',100,'%');									// Size of object
	$obj_lisha_tran->define_attribute('__active_readonly_mode', __RW__);			// Read & Write
	$obj_lisha_tran->define_attribute('__id_theme','grey');							// Define style	

	$obj_lisha_tran->define_attribute('__active_title', true);						// Title bar	
	$obj_lisha_tran->define_attribute('__title', 'Demo list example');				// Title

	$obj_lisha_tran->define_attribute('__max_lines_by_page', 80);					// Limit rows by page	

	$obj_lisha_tran->define_attribute('__active_column_separation',false);
	$obj_lisha_tran->define_attribute('__active_row_separation',false);

	$obj_lisha_tran->define_attribute('__active_top_bar_page',false);
	$obj_lisha_tran->define_attribute('__active_bottom_bar_page',true);

	$obj_lisha_tran->define_attribute('__active_user_doc', false);					// user documentation button
	$obj_lisha_tran->define_attribute('__active_tech_doc', false);					// technical documentation button
	$obj_lisha_tran->define_attribute('__active_ticket', false);						// Tickets link


	$obj_lisha_tran->define_attribute('__display_mode', __NMOD__);					// Display mode

	$obj_lisha_tran->define_attribute('__key_url_custom_view', 'f1');				// Defined key for quick custom view loader in url browser

	$obj_lisha_tran->define_attribute('__update_table_name', "demo_list_example");		// Define table to update

	//$obj_lisha_tran->define_attribute('__column_name_group_of_color', "MyGroupTheme");		// ( Optional ) Define csutom column color name
	//==================================================================

	//==================================================================
	// define columns
	//==================================================================

		//==================================================================
		// define column : id
		//==================================================================
		$obj_lisha_tran->define_column('`demo_list_example`.`id`','id','Identifier',__INT__,__WRAP__,__CENTER__,__CONTAIN__,__DISPLAY__);
		$obj_lisha_tran->define_attribute('__column_input_check_update', __FORBIDDEN__,'id');
		//==================================================================


		//==================================================================
		// define column : name
		//==================================================================
		$obj_lisha_tran->define_column('`demo_list_example`.`name`','name','Name',__TEXT__,__WRAP__,__LEFT__);
		$obj_lisha_tran->define_attribute('__column_input_check_update', __REQUIRED__,'name');
		$obj_lisha_tran->define_input_focus('name', true);					// Focused
		//==================================================================

		//==================================================================
		// define column : HTML link
		//==================================================================
		$obj_lisha_tran->define_column("`demo_list_example`.`link`",'link','Access',__BBCODE__,__WRAP__,__LEFT__);
		$obj_lisha_tran->define_attribute('__column_input_check_update', __REQUIRED__,'link');
		//==================================================================

		//==================================================================
		// define column : title
		//==================================================================
		$obj_lisha_tran->define_column('`demo_list_example`.`title`','title','Title',__TEXT__,__WRAP__,__LEFT__);
		$obj_lisha_tran->define_attribute('__column_input_check_update', __REQUIRED__,'title');
		//==================================================================

		//==================================================================
		// define column : Screenshot
		//==================================================================
		$obj_lisha_tran->define_column('`demo_list_example`.`screen`','screen','Screenshot',__BBCODE__,__WRAP__,__LEFT__);
		//$obj_lisha_tran->define_attribute('__column_input_check_update', __FORBIDDEN__,'description');
		//==================================================================

		//==================================================================
		// define column : Description
		//==================================================================
		$obj_lisha_tran->define_column('`demo_list_example`.`description`','description','Description',__BBCODE__,__WRAP__,__LEFT__);
		//$obj_lisha_tran->define_attribute('__column_input_check_update', __FORBIDDEN__,'description');
		//==================================================================

		//==================================================================
		// define column : Last update
		//==================================================================
		$obj_lisha_tran->define_column('`demo_list_example`.`last_update`','last_update','Last update',__TEXT__,__WRAP__,__LEFT__);
		$obj_lisha_tran->define_attribute('__column_input_check_update', __FORBIDDEN__,'last_update');
		//==================================================================

		//==================================================================
		// define column : SetOfColor
		//==================================================================
		//$obj_lisha_tran->define_column('`demo_table`.`status`','MyGroupTheme','MyGroupTheme',__INT__,__WRAP__,__CENTER__);
		//$obj_lisha_tran->define_attribute('__column_display_mode',false,'MyGroupTheme');
		//$obj_lisha_tran->define_attribute('__column_input_check_update', __FORBIDDEN__,'MyGroupTheme');
		//==================================================================

	//==================================================================



	// Table columns primary key
	// Caution : Can't change key column name from origine query column name
	// It's not required to declare column key with define_column method
	$obj_lisha_tran->define_key(Array('id'));

	//==================================================================
	// Define extra events actions 
	//==================================================================
	//$obj_lisha_tran->define_lisha_action(__ON_ADD__,__AFTER__,'lisha_transaction',Array('rebuild_account();'));
	//==================================================================

	//==================================================================
	// Column order : Define in ascending priority means first line defined will be first priority column to order by and so on...
	//==================================================================
	$obj_lisha_tran->define_order_column('id',__DESC__);
	//$obj_lisha_tran->define_order_column('name',__DESC__);
	//==================================================================

	//==================================================================
	// Line theme mask
	//==================================================================
	// Default group
	$obj_lisha_tran->define_line_theme("EEDDDD","0.7em","EECCCC","0.7em","AA8888","0.7em","BB7878","0.7em","000","000");
	$obj_lisha_tran->define_line_theme("FFEEEE","0.7em","FFCDCD","0.7em","EECCCC","0.7em","DDC8C8","0.7em","000","000");

	// Group 2
	//$obj_lisha_tran->define_line_theme("DDEEDD","0.7em","CCEECC","0.7em","68B7E0","0.7em","68B7E0","0.7em","000","000",1);
	//$obj_lisha_tran->define_line_theme("EEFFEE","0.7em","D0E0DC","0.7em","AEE068","0.7em","AEE068","0.7em","000","000",1);

	// Group 3
	//$obj_lisha_tran->define_line_theme("DDDDEE","0.7em","CCCCEE","0.7em","68B7E0","0.7em","68B7E0","0.7em","028","000",2);
	//$obj_lisha_tran->define_line_theme("EEEEFF","0.7em","D0DCE0","0.7em","AEE068","0.7em","AEE068","0.7em","006","000",2);
	//==================================================================			

	//==================================================================
	// Do not remove this bloc
	// Keep this bloc at the end
	//==================================================================
	$obj_lisha_tran->generate_public_header();   
	$obj_lisha_tran->generate_header();
	//==================================================================