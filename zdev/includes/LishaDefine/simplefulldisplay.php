<?php 
	$lisha1_id = 'lisha_transaction';

	// Force lisha language from framework language
	//$_GET['lng'] = $_SESSION[$ssid]['langue']; // Already done by language include of main page

	// Use framework connexion information from framework
	/** @var $ssid string come from your main php page */
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
					`transaction`.`index`			AS `index`,
					`transaction`.`daterec` 		AS `daterec`,
					`transaction`.`description`	    AS `description`,
					`transaction`.`amount`			AS `amount`,
					IF(MOD(`transaction`.`index`,2)=0,MD5(`transaction`.`amount`),SHA1(`transaction`.`amount`))	    AS `encrypt`,
					CONCAT('[b]',`transaction`.`status`,'[/b]')			AS `status`,
					`transaction`.`checkme`		    AS `checkme`,
					`transaction`.`datum`			AS `datum`,
					`transaction`.`mode`			AS `mode`,
					(SELECT `text` FROM `transaction2` WHERE `mode` = `transaction`.`mode`)  AS `thistext`,
					`transaction`.`text`			AS `text`,
					`transaction`.`my_numeric`		AS `my_numeric`,
					`transaction`.`status`			AS `MyGroupTheme`
				".$_SESSION[$ssid]['lisha']['configuration'][10]."
					`transaction` -- No alias on update table !!
					WHERE 1 = 1
					";

	$obj_lisha_tran->define_attribute('__main_query', $query);
	//==================================================================

	//==================================================================
	// Lisha display setup
	//==================================================================
	$obj_lisha_tran->define_nb_line(70);											// Row by page								
	$obj_lisha_tran->define_size(100,'%',100,'%');									// Size of object
	$obj_lisha_tran->define_attribute('__active_readonly_mode', __RW__);			// Read & Write

	$obj_lisha_tran->define_attribute('__active_read_only_cells_edit', __RW__);		// Read & Write

	$obj_lisha_tran->define_attribute('__id_theme','grey');							// Define style

	$obj_lisha_tran->define_attribute('__active_title', true);						// Title bar	
	$obj_lisha_tran->define_attribute('__title', 'Main test Lisha');	// Title

	$obj_lisha_tran->define_attribute('__max_lines_by_page', 150);					// Limit rows by page

	$obj_lisha_tran->define_attribute('__active_column_separation',false);
	$obj_lisha_tran->define_attribute('__active_row_separation',false);

	$obj_lisha_tran->define_attribute('__active_top_bar_page',false);
	$obj_lisha_tran->define_attribute('__active_bottom_bar_page',true);

	$obj_lisha_tran->define_attribute('__active_user_doc', true);					// user documentation button
	$obj_lisha_tran->define_attribute('__active_tech_doc', true);					// technical documentation button
	$obj_lisha_tran->define_attribute('__active_ticket', true);						// Tickets link


	$obj_lisha_tran->define_attribute('__display_mode', __NMOD__);					// Display mode

	$obj_lisha_tran->define_attribute('__key_url_custom_view', 'Test');				// Defined key for quick custom view loader in url browser

	$obj_lisha_tran->define_attribute('__update_table_name', "transaction");		// Define table to update

	$obj_lisha_tran->define_attribute('__column_name_group_of_color', "MyGroupTheme");		// ( Optional ) Define custom column color name

	//$obj_lisha_tran->define_attribute('__active_global_search', false);           // Hide or display global search button
	//$obj_lisha_tran->define_attribute('__active_insert_button', false);           // Hide or display Add button
	//$obj_lisha_tran->define_attribute('__active_delete_button', false);           // Hide or display Delete button

	$obj_lisha_tran->define_attribute('__active_quick_search', true);				        // Quick search mode ( Optional : default true )
	//==================================================================

	//==================================================================
	// define columns
	//==================================================================

		//==================================================================
		// define column : Date modification
		//==================================================================
		$obj_lisha_tran->define_column("`transaction`.`daterec`",'daterec','datum ⛵',__DATE__,__WRAP__,__CENTER__,__EXACT__,__DISPLAY__);
		$obj_lisha_tran->define_attribute('__column_date_format','%d/%m/%Y','daterec');
		$obj_lisha_tran->define_attribute('__column_input_check_update', __REQUIRED__,'daterec');
		$obj_lisha_tran->define_input_focus('daterec', true);					// Focused
		//==================================================================

		//==================================================================
		// define column : Description
		//==================================================================
		$obj_lisha_tran->define_column("`transaction`.`description`",'description','⏩ ♌',__BBCODE__,__WRAP__,__LEFT__);
		$obj_lisha_tran->define_attribute('__column_input_check_update', __REQUIRED__,'description');
		//==================================================================

		//==================================================================
		// define column : module
		//==================================================================
		$obj_lisha_tran->define_column("`transaction`.`mode`",'mode','Mymodule',__TEXT__,__WRAP__,__LEFT__);
		$obj_lisha_tran->define_attribute('__column_input_check_update', __LISTED__,'mode');

		// Match code
		$obj_lisha_tran->define_lov("	SELECT DISTINCT
											TRANS.`mode` AS `mode`,
											TRANS2.`text` AS `text`
										".$_SESSION[$ssid]['lisha']['configuration'][10]."
											`transaction` TRANS,
											`transaction2` TRANS2
										WHERE 1 = 1
											AND TRANS.`mode` =	TRANS2.`mode`
									",
									'Title of mode',
									'TRANS.`mode`',
									'mode'
								   );
		$obj_lisha_tran->define_column_lov("TRANS.`mode`",'mode','myMode',__TEXT__,__WRAP__,__LEFT__);
		$obj_lisha_tran->define_column_lov("TRANS2.`text`",'text','myText',__TEXT__,__WRAP__,__LEFT__);
		$obj_lisha_tran->define_column_lov_order('mode',__ASC__);
		//==================================================================

		//==================================================================
		// define column : numeric
		//==================================================================
		$obj_lisha_tran->define_column("`transaction`.`my_numeric`",'my_numeric','MyNum',__FLOAT__,__WRAP__,__LEFT__);
		$obj_lisha_tran->define_attribute('__column_number_of_decimal', 7,'my_numeric');
		//$obj_lisha_tran->define_col_select_function('password','DECODE(password,"gfqhz__234j&-bhjq")');
		//.... DECODE(`password`,'gfqhz__234j&-bhjq') AS `password`,
		//==================================================================


		//==================================================================
		// define column : ModuleLib
		//==================================================================
		$obj_lisha_tran->define_column("(SELECT `text` FROM `transaction2` WHERE `mode` = `transaction`.`mode`)",'thistext','ModuleLibHere',__TEXT__,__WRAP__,__LEFT__);
		$obj_lisha_tran->define_attribute('__column_input_check_update', __FORBIDDEN__,'thistext');
		// Match code
		$obj_lisha_tran->define_lov("	SELECT
												TRANS2.`mode` AS `mode`,
												TRANS2.`text` AS `text`
										".$_SESSION[$ssid]['lisha']['configuration'][10]."
												`transaction2` TRANS2
										WHERE 1 = 1
												AND TRANS2.`mode` = '||TAGLOV_mode**mode||'
												",
										'Title of description',
										'TRANS2.`text`',
										'text'
									);
		$obj_lisha_tran->define_column_lov("TRANS2.`mode`",'mode','Mymode',__TEXT__,__WRAP__,__LEFT__);
		$obj_lisha_tran->define_column_lov("TRANS2.`text`",'text','Mytext',__TEXT__,__WRAP__,__LEFT__);
		$obj_lisha_tran->define_column_lov_order('mode',__ASC__);
		//==================================================================

		//==================================================================
		// define column : amount
		//==================================================================
		$obj_lisha_tran->define_column("`transaction`.`amount`",'amount','',__BBCODE__,__WRAP__,__LEFT__);
		//$obj_lisha_tran->define_attribute('__column_display_mode',false,'amount');						
		//==================================================================

		//==================================================================
		// define column : compute
		//==================================================================
		$obj_lisha_tran->define_column("IF(MOD(`transaction`.`index`,2)=0,MD5(`transaction`.`amount`),SHA1(`transaction`.`amount`))",'encrypt','Maj xxxxx',__TEXT__,__WRAP__,__LEFT__);
		$obj_lisha_tran->define_attribute('__column_input_check_update', __FORBIDDEN__,'encrypt');
		//$obj_lisha_tran->define_attribute('__column_display_mode',false,'amount');						
		//==================================================================

		//==================================================================
		// define column : identifier
		//==================================================================
		$obj_lisha_tran->define_column("`transaction`.`index`",'index','Libid',__TEXT__,__WRAP__,__CENTER__);
		$obj_lisha_tran->define_attribute('__column_display_mode',true,'index');
		$obj_lisha_tran->define_attribute('__column_input_check_update', __FORBIDDEN__,'index');
		//==================================================================

		//==================================================================
		// define column : checkbox
		//==================================================================
		$obj_lisha_tran->define_column("`transaction`.`checkme`",'checkme','Libcheckbox',__CHECKBOX__,__WRAP__,__CENTER__);
		$obj_lisha_tran->define_attribute('__column_display_mode',true,'checkme');
		//==================================================================

		//==================================================================
		// define column : status
		//==================================================================
		$obj_lisha_tran->define_column("`transaction`.`status`",'status','MyColorStatus',__BBCODE__,__WRAP__,__CENTER__);
		//$obj_lisha_tran->define_attribute('__column_display_mode',false,'status');
		//$obj_lisha_tran->define_attribute('__column_input_check_update', __FORBIDDEN__,'status');
		//==================================================================

		//==================================================================
		// define column : datum
		//==================================================================
		$obj_lisha_tran->define_column("`transaction`.`datum`",'datum','other date',__DATE__,__WRAP__,__CENTER__);
		$obj_lisha_tran->define_attribute('__column_date_format','%d/%m/%Y','datum');
		//$obj_lisha_tran->define_attribute('__column_display_mode',false,'status');
		//$obj_lisha_tran->define_attribute('__column_input_check_update', __FORBIDDEN__,'status');
		//==================================================================

		//==================================================================
		// define column : SetOfColor
		//==================================================================
		$obj_lisha_tran->define_column("`transaction`.`status`",'MyGroupTheme','MyGroupTheme',__TEXT__,__WRAP__,__CENTER__);
		$obj_lisha_tran->define_attribute('__column_display_mode',false,'MyGroupTheme');
		$obj_lisha_tran->define_attribute('__column_input_check_update', __FORBIDDEN__,'MyGroupTheme');
		//==================================================================

		//==================================================================
		// define column : masse
		//==================================================================
		$obj_lisha_tran->define_column("`transaction`.`text`",'text','Mass text',__TEXT__,__WRAP__,__LEFT__);
		//$obj_lisha_tran->define_attribute('__column_display_mode',false,'status');
		//$obj_lisha_tran->define_attribute('__column_input_check_update', __FORBIDDEN__,'status');
		//==================================================================

	//==================================================================


	//==================================================================
	// Table columns primary key
	// Caution : Can't change key column name from origine query column name
	// It's not required to declare column key with define_column method
	//==================================================================
	$obj_lisha_tran->define_key(Array('index'));
	//==================================================================

	//==================================================================
	// Define extra events actions 
	//==================================================================
	//$obj_lisha_tran->define_lisha_action(__ON_ADD__,__AFTER__,'lisha_transaction',Array('after_add_new_line(\'titi\');'));
	//==================================================================

	//==================================================================
	// Column order : Define in ascending priority means first line defined will be first priority column to order by and so on...
	//==================================================================
	//$obj_lisha_tran->define_order_column('index',__DESC__);
	//$obj_lisha_tran->define_order_column('amount',__ASC__);
	//$obj_lisha_tran->define_order_column('description',__DESC__);
	//==================================================================

	//==================================================================
	// Line theme mask
	//==================================================================
	// default group color with no group defined
	$obj_lisha_tran->define_line_theme("DDDDEE","0.7em","9999CC","0.7em","99c3ed","0.7em","6690ba","0.7em","000","FFF");
	$obj_lisha_tran->define_line_theme("EEEEEE","0.7em","AAAAAA","0.7em","336086","0.7em","003053","0.7em","000","888");

	// Set of color 1 with key 1
	$obj_lisha_tran->define_line_theme("EEDDDD","0.7em","CC9999","0.7em","eeb289","0.7em","888888","0.7em","000","FFF",1);
	$obj_lisha_tran->define_line_theme("EEEEEE","0.7em","AAAAAA","0.7em","ee8844","0.7em","000000","0.7em","000","DDD",1);
	//==================================================================

	//==================================================================
	// Do not remove this bloc
	// Keep this bloc at the end
	//==================================================================
	$obj_lisha_tran->generate_public_header();   
	$obj_lisha_tran->generate_header();
	//==================================================================