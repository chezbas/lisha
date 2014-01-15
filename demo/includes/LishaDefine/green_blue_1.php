<?php 
	$lisha1_id = 'lisha_green';

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
	$obj_lisha_green = &$_SESSION[$ssid]['lisha'][$lisha1_id];

	//==================================================================
	// Define main query
	//==================================================================
	$query = "
			SELECT
				`demo_table`.`index`			AS `index`,
				`demo_table`.`daterec` 		    AS `daterec`,
				`demo_table`.`otherdate` 		AS `otherdate`,
				`demo_table`.`description`	    AS `description`,
				DECODE(`demo_table`.`password`,'hX*sqdkjf3_--é0Fz.')	AS `password`,
				`demo_table`.`amount`			AS `amount`,
				UPPER(`demo_table`.`amount`)	AS `upper`,
				`demo_table`.`status`			AS `status`,
				`demo_table`.`status`			AS `MyGroupTheme`
			".$_SESSION[$ssid]['lisha']['configuration'][10]."
				`demo_table`
				WHERE 1 = 1
				";
	$obj_lisha_green->define_attribute('__main_query', $query);
	//==================================================================

	//==================================================================
	// Lisha display setup
	//==================================================================
	$obj_lisha_green->define_nb_line(20);											// Row by page								
	$obj_lisha_green->define_size(100,'%',100,'%');									// Size of object
	$obj_lisha_green->define_attribute('__active_readonly_mode', __RW__);			// Read & Write
	$obj_lisha_green->define_attribute('__id_theme','green');						// Define style

	$obj_lisha_green->define_attribute('__active_title', true);						// Title bar	
	$obj_lisha_green->define_attribute('__title', 'Green one');							// Title

	$obj_lisha_green->define_attribute('__max_lines_by_page', 80);					// Limit rows by page	

	$obj_lisha_green->define_attribute('__active_column_separation',false);
	$obj_lisha_green->define_attribute('__active_row_separation',false);

	$obj_lisha_green->define_attribute('__active_top_bar_page',true);
	$obj_lisha_green->define_attribute('__active_bottom_bar_page',true);

	$obj_lisha_green->define_attribute('__active_user_doc', false);					// user documentation button
	$obj_lisha_green->define_attribute('__active_tech_doc', true);					// technical documentation button
	$obj_lisha_green->define_attribute('__active_ticket', true);						// Tickets link


	$obj_lisha_green->define_attribute('__display_mode', __NMOD__);					// Display mode

	$obj_lisha_green->define_attribute('__key_url_custom_view', 'f1');				// Defined key for quick custom view loader in url browser

	$obj_lisha_green->define_attribute('__update_table_name', "demo_table");		// Define table to update

	//$obj_lisha_green->define_attribute('__active_insert_button', false);

	$obj_lisha_green->define_attribute('__column_name_group_of_color', "MyGroupTheme");		// ( Optional ) Define csutom column color name
	//==================================================================

	//==================================================================
	// define columns
	//==================================================================

		//==================================================================
		// define column : Date modification
		//==================================================================
		$obj_lisha_green->define_column('`demo_table`.`daterec`','daterec','date',__DATE__,__WRAP__,__CENTER__,__PERCENT__,__DISPLAY__);
		//$obj_lisha_green->define_attribute('__column_date_format','%d/%m/%Y','daterec');
		$obj_lisha_green->define_attribute('__column_date_format','%Y-%m-%d','daterec');
		$obj_lisha_green->define_attribute('__column_input_check_update', __REQUIRED__,'daterec');
		$obj_lisha_green->define_input_focus('daterec', true);					// Focused
		//==================================================================

		//==================================================================
		// define column : Date modification
		//==================================================================
		$obj_lisha_green->define_column('`demo_table`.`otherdate`','otherdate','Date Time !!',__DATE__,__WRAP__,__CENTER__,__PERCENT__,__DISPLAY__);
		$obj_lisha_green->define_attribute('__column_date_format','%Y-%m-%d %H:%i:%s','otherdate');
		//$obj_lisha_green->define_attribute('__column_input_check_update', __REQUIRED__,'otherdate');
		//==================================================================

		//==================================================================
		// define column : Password
		// Caution : MySQL column type Blob
		//==================================================================
		$obj_lisha_green->define_column("DECODE(`demo_table`.`password`,'hX*sqdkjf3_--é0Fz.')",'password','Encode/Decode',__TEXT__,__WRAP__,__LEFT__);
		//$obj_lisha_green->define_attribute('__column_input_check_update', __REQUIRED__,'password');
		$obj_lisha_green->define_col_rw_function('password',"ENCODE('__COL_VALUE__','hX*sqdkjf3_--é0Fz.')");
		$obj_lisha_green->define_col_select_function('password',"DECODE(`demo_table`.__COL_VALUE__,'hX*sqdkjf3_--é0Fz.')");
		//==================================================================

		//==================================================================
		// define column : Description
		//==================================================================
		$obj_lisha_green->define_column('`demo_table`.`description`','description','Caption',__BBCODE__,__WRAP__,__LEFT__);
		$obj_lisha_green->define_attribute('__column_input_check_update', __REQUIRED__,'description');
		//==================================================================

		//==================================================================
		// define column : amount
		//==================================================================
		$obj_lisha_green->define_column('`demo_table`.`amount`','amount','normal',__BBCODE__,__WRAP__,__LEFT__);
		//$obj_lisha_green->define_attribute('__column_display_mode',false,'amount');						
		//==================================================================

		//==================================================================
		// define column : compute
		//==================================================================
		$obj_lisha_green->define_column('UPPER(`demo_table`.`amount`)','upper','Upper',__TEXT__,__WRAP__,__LEFT__);
		$obj_lisha_green->define_attribute('__column_input_check_update', __FORBIDDEN__,'upper');
		//$obj_lisha_green->define_attribute('__column_display_mode',false,'amount');						
		//==================================================================

		//==================================================================
		// define column : identifier
		//==================================================================
		$obj_lisha_green->define_column('`demo_table`.`index`','index','id',__TEXT__,__WRAP__,__CENTER__);
		//$obj_lisha_green->define_attribute('__column_display_mode',true,'index');
		$obj_lisha_green->define_attribute('__column_input_check_update', __FORBIDDEN__,'index');
		//==================================================================

		//==================================================================
		// define column : status
		//==================================================================
		$obj_lisha_green->define_column('`demo_table`.`status`','status','status',__TEXT__,__WRAP__,__CENTER__);
		//$obj_lisha_green->define_attribute('__column_display_mode',false,'status');
		$obj_lisha_green->define_attribute('__column_input_check_update', __LISTED__,'status');

		// Match code
		$obj_lisha_green->define_lov("	SELECT
											`main`.`A` AS `mode`
											".$_SESSION[$ssid]['lisha']['configuration'][10]."
											(
												SELECT 0 AS `A`
												UNION
												SELECT 1 AS `A`
												UNION
												SELECT 2 AS `A`
											 ) `main`
												WHERE 1 = 1
											",
									'Color index',
									'`main`.`A`',
									'mode'
								);
		$obj_lisha_green->define_column_lov("`main`.`A`",'mode','myColor',__TEXT__,__WRAP__,__LEFT__);
		$obj_lisha_green->define_column_lov_order('mode',__ASC__);
		//==================================================================

		//==================================================================
		// define column : SetOfColor
		//==================================================================
		$obj_lisha_green->define_column('`demo_table`.`status`','MyGroupTheme','MyGroupTheme',__TEXT__,__WRAP__,__CENTER__);
		$obj_lisha_green->define_attribute('__column_display_mode',false,'MyGroupTheme');
		$obj_lisha_green->define_attribute('__column_input_check_update', __FORBIDDEN__,'MyGroupTheme');
		//==================================================================

	//==================================================================



	// Table columns primary key
	// Caution : Can't change key column name from origine query column name
	// It's not required to declare column key with define_column method
	$obj_lisha_green->define_key(Array('index'));

	//==================================================================
	// Define extra events actions 
	//==================================================================
	//$obj_lisha_green->define_lisha_action(__ON_ADD__,__AFTER__,'lisha_transaction',Array('rebuild_account();'));
	//==================================================================

	//==================================================================
	// Column order : Define in ascending priority means first line defined will be first priority column to order by and so on...
	//==================================================================
	$obj_lisha_green->define_order_column('index',__DESC__);					
	$obj_lisha_green->define_order_column('description',__DESC__);
	$obj_lisha_green->define_order_column('amount',__ASC__);
	//==================================================================

	//==================================================================
	// Line theme mask
	//==================================================================
	// Default group
	$obj_lisha_green->define_line_theme("DDEEDD","0.7em","CCEECC","0.7em","68B7E0","0.7em","68B7E0","0.7em","000","000");
	$obj_lisha_green->define_line_theme("EEFFEE","0.7em","D0E0DC","0.7em","AEE068","0.7em","AEE068","0.7em","000","000");

	// Group 3
	//$obj_lisha_green->define_line_theme("DDDDEE","0.7em","CCCCEE","0.7em","68B7E0","0.7em","68B7E0","0.7em","028","000",2);
	//$obj_lisha_green->define_line_theme("EEEEFF","0.7em","D0DCE0","0.7em","AEE068","0.7em","AEE068","0.7em","006","000",2);
	//==================================================================			




	//==================================================================
	// Do not remove this bloc
	// Keep this bloc at the end
	//==================================================================
	$obj_lisha_green->generate_public_header();   
	$obj_lisha_green->generate_header();
	//==================================================================