<?php 
	$lisha_bug = 'lisha_bugs';

	// Force lisha language from framework language
	//$_GET['lng'] = $_SESSION[$ssid]['lisha']['langue']; // Already done by language include of main page
	
	// Use framework connexion information from framework
	$_SESSION[$ssid]['lisha'][$lisha_bug] = new lisha(
														$lisha_bug,
														$ssid,
														__MYSQL__,
														array('user' => __LISHA_DATABASE_USER__,'password' => __LISHA_DATABASE_PASSWORD__,'host' => __LISHA_DATABASE_HOST__,'schema' => __LISHA_DATABASE_SCHEMA__),
														'../',
														null,
														false,
														__LISHA_APPLICATION_RELEASE__);

	// Create a reference to the session
	$obj_lisha_bug = &$_SESSION[$ssid]['lisha'][$lisha_bug];
	
	//==================================================================
	// Define main query
	//==================================================================
	$query = "	SELECT
					`bugsreports`.`ID` AS 'id',
					TEXD.`text` AS 'business',
					`bugsreports`.`Type` AS 'type',
					TEXC.`text` AS 'classe',
					`bugsreports`.`Version` AS 'version',
					`bugsreports`.`DateCrea` AS 'DateCrea',
					(
						SELECT
							CASE TEX.`id`
								WHEN 4
								THEN CONCAT('[S]',`bugsreports`.`Description`,'[/S]')
								ELSE
									`bugsreports`.`Description`
								END	
					) AS 'Description',
					CONCAT('[img]',CLAS.`symbol`,'[/img]') AS 'flag',
					CONCAT(
								(
									SELECT
							 			CASE IFNULL( LENGTH( `bugsreports`.`details` ) , 0 ) + IFNULL( LENGTH( `bugsreports`.`solution` ) , 0 ) 
											WHEN 0
											THEN CONCAT('[URL=./editbug.php?ssid=".$ssid."&MTLNG=".$_GET['lng']."&ID=',`bugsreports`.`ID`,']".$_SESSION[$ssid]['lisha']['page_text'][15]['TX']."[/URL]')
											ELSE CONCAT('<a target=\"_blank\" onclick=\"lisha_StopEventHandler(event);\"[URL=./viewbug.php?ssid=".$ssid."&MTLNG=".$_GET['lng']."&ID=',`bugsreports`.`ID`,']".$_SESSION[$ssid]['lisha']['page_text'][13]['TX']."[/URL]</a>',' / ','<a target=\"_blank\" onclick=\"lisha_StopEventHandler(event);\"[URL=./editbug.php?ssid=".$ssid."&MTLNG=".$_GET['lng']."&ID=',`bugsreports`.`ID`,']".$_SESSION[$ssid]['lisha']['page_text'][14]['TX']."[/URL]</a>')
										END
								)
						  ) AS 'details',
					`bugsreports`.`Qui` AS 'qui',
					TEX.`text` AS 'status',
					`bugsreports`.`reference` AS 'reference',
					`bugsreports`.`Last_mod` AS 'last_mod'
				FROM
					`".__LISHA_TABLE_EXTRA_TICK__."`, -- No alias on update table !!
					`".__LISHA_TABLE_EXTRA_TICK_TEXT__."` TEX,
					`".__LISHA_TABLE_EXTRA_TICK_CLAS__."` CLAS,
					`".__LISHA_TABLE_EXTRA_TICK_CLAS__."` CLASC,
					`".__LISHA_TABLE_EXTRA_TICK_CLAS__."` CLASD,
					`".__LISHA_TABLE_EXTRA_TICK_TEXT__."` TEXC,
					`".__LISHA_TABLE_EXTRA_TICK_TEXT__."` TEXD
				WHERE 1 = 1
					AND CLAS.`id` = TEX.`id`
					AND CLAS.`class` = 'status'
					AND CLASC.`id` = `bugsreports`.`Classe`
					AND CLASC.`class` = 'class'
					AND CLASD.`id` = `bugsreports`.`Business`
					AND CLASD.`class` = 'business'
					AND `bugsreports`.`Classe` = TEXC.`id`
					AND `bugsreports`.`Business` = TEXD.`id` 
					AND TEX.`id_lang` = TEXC.`id_lang`
					AND TEX.`id_lang` = TEXD.`id_lang`
					AND `bugsreports`.`status` = TEX.`id`
					AND TEX.`id_lang` = '".$_GET['lng']."'
				";

	$obj_lisha_bug->define_attribute('__main_query', $query);
	//==================================================================
	
	//==================================================================
	// Lisha display setup
	//==================================================================
	$obj_lisha_bug->define_size(100,'%',100,'%');											
	$obj_lisha_bug->define_nb_line(50);													

	$obj_lisha_bug->define_attribute('__active_readonly_mode', __RW__);	// Read & Write
	$obj_lisha_bug->define_attribute('__id_theme','grey');				// Define style	
	
	$obj_lisha_bug->define_attribute('__title', $_SESSION[$ssid]['lisha']['page_text'][1]['TX']);		// Title	
	$obj_lisha_bug->define_attribute('__active_column_separation',false);
	$obj_lisha_bug->define_attribute('__active_row_separation',true);

	$obj_lisha_bug->define_attribute('__active_top_bar_page',false);
	$obj_lisha_bug->define_attribute('__active_bottom_bar_page',true);
	
	$obj_lisha_bug->define_attribute('__active_user_doc', false);			// user documentation button
	$obj_lisha_bug->define_attribute('__active_tech_doc', false);			// technical documentation button
	$obj_lisha_bug->define_attribute('__active_ticket', false);				// Tickets link

	$obj_lisha_bug->define_attribute('__update_table_name', __LISHA_TABLE_EXTRA_TICK__);		// Main table name
	//==================================================================
		
	//==================================================================
	// define output columns
	//==================================================================
		
		//==================================================================
		// define column : ID
		//==================================================================
		$obj_lisha_bug->define_column('id',$_SESSION[$ssid]['lisha']['page_text'][2]['TX'],__TEXT__,__WRAP__,__CENTER__,__EXACT__);
		$obj_lisha_bug->define_attribute('__column_input_check_update', __FORBIDDEN__,'id');
		//==================================================================
		
		//==================================================================
		// define column : Business domain
		//==================================================================
		$obj_lisha_bug->define_column('business',$_SESSION[$ssid]['lisha']['page_text'][16]['TX'],__TEXT__,__WRAP__,__CENTER__);
		
		$obj_lisha_bug->define_lov("	SELECT
											`CLAS`.`id` AS 'ID',
											`TEX`.`text` AS 'Libelle',
											`CLAS`.`order` AS 'ord'
										FROM
											`".__LISHA_TABLE_EXTRA_TICK_CLAS__."` `CLAS`, `bugstexts` `TEX`
										WHERE 1 = 1
											AND `TEX`.`id` = `CLAS`.`id`
											AND `TEX`.`id_lang` = '".$_GET['lng']."'
											AND `CLAS`.`class` = 'business'",
									$_SESSION[$ssid]['lisha']['page_text'][17]['TX'],
									'ID'
								   );
		$obj_lisha_bug->define_column_lov('Libelle',$_SESSION[$ssid]['lisha']['page_text'][8]['TX'],__TEXT__,__WRAP__,__LEFT__);
		$obj_lisha_bug->define_column_lov('ord',$_SESSION[$ssid]['lisha']['page_text'][18]['TX'],__TEXT__,__WRAP__,__LEFT__,__PERCENT__,__DISPLAY__,true);
		$obj_lisha_bug->define_column_lov('ID',__TEXT__,__WRAP__,__LEFT__);
		$obj_lisha_bug->define_column_lov_order('ord',__ASC__);
		//==================================================================

		//==================================================================
		// define column : Theme
		//==================================================================
		$obj_lisha_bug->define_column('type',$_SESSION[$ssid]['lisha']['page_text'][3]['TX'],__TEXT__,__WRAP__,__LEFT__);
		$obj_lisha_bug->define_attribute('__column_input_check_update', __REQUIRED__,'type');
		
		$obj_lisha_bug->define_lov("	SELECT
											DISTINCT
											`BUG`.`Type` AS 'Type',
											MAX(`BUG`.`Last_mod`) AS 'Lastmod'
										FROM
											`".__LISHA_TABLE_EXTRA_TICK__."` `BUG`
										GROUP BY Type",
									$_SESSION[$ssid]['lisha']['page_text'][19]['TX'],
									'Type'
								   );
		$obj_lisha_bug->define_column_lov('Type',$_SESSION[$ssid]['lisha']['page_text'][3]['TX'],__TEXT__,__WRAP__,__LEFT__,__PERCENT__,__DISPLAY__,true);
		$obj_lisha_bug->define_column_lov('Lastmod',$_SESSION[$ssid]['lisha']['page_text'][12]['TX'],__TEXT__,__WRAP__,__LEFT__);
		$obj_lisha_bug->define_column_lov_order('Lastmod',__DESC__);
		//==================================================================
					
		//==================================================================
		// define column : Bug class
		//==================================================================
		$obj_lisha_bug->define_column('classe',$_SESSION[$ssid]['lisha']['page_text'][4]['TX'],__TEXT__,__WRAP__,__CENTER__);

		$obj_lisha_bug->define_lov("	SELECT
											CLAS.`id` AS 'ID',
											TEX.`text` AS 'Libelle',
											CLAS.`order` AS 'ord'
										FROM
											`".__LISHA_TABLE_EXTRA_TICK_CLAS__."` CLAS, `bugstexts` TEX
										WHERE 1 = 1
											AND TEX.`id` = CLAS.`id`
											AND TEX.`id_lang` = '".$_GET['lng']."'
											AND CLAS.`class` = 'class'",
									$_SESSION[$ssid]['lisha']['page_text'][17]['TX'],
									'ID'
								   );
		$obj_lisha_bug->define_column_lov('Libelle',$_SESSION[$ssid]['lisha']['page_text'][8]['TX'],__TEXT__,__WRAP__,__LEFT__,__PERCENT__,__DISPLAY__,true);
		$obj_lisha_bug->define_column_lov('ord',$_SESSION[$ssid]['lisha']['page_text'][18]['TX'],__TEXT__,__WRAP__,__LEFT__);
		$obj_lisha_bug->define_column_lov('ID','Iden',__TEXT__,__WRAP__,__LEFT__);
		$obj_lisha_bug->define_column_lov_order('ord',__ASC__);
		//$obj_lisha_bug->define_input_focus_lov('ID');
		//==================================================================
				
		//==================================================================
		// define column : Application version involved
		//==================================================================
		$obj_lisha_bug->define_column('version',$_SESSION[$ssid]['lisha']['page_text'][5]['TX'],__TEXT__,__WRAP__,__CENTER__,__EXACT__);
		//==================================================================
				
		//==================================================================
		// define column : Create date
		//==================================================================
		$obj_lisha_bug->define_column('DateCrea',$_SESSION[$ssid]['lisha']['page_text'][6]['TX'],__DATE__,__WRAP__,__CENTER__);
		$obj_lisha_bug->define_attribute('__column_date_format','%d/%m/%Y','DateCrea');
		//==================================================================
		
		//==================================================================
		// define column : Bug title
		//==================================================================
		$obj_lisha_bug->define_column('Description',$_SESSION[$ssid]['lisha']['page_text'][7]['TX'],__BBCODE__,__WRAP__,__LEFT__);
		$obj_lisha_bug->define_input_focus('Description', true);					// Focused
		//==================================================================
				
		//==================================================================
		// define column : Status
		//==================================================================
		$obj_lisha_bug->define_column('status',$_SESSION[$ssid]['lisha']['page_text'][8]['TX'],__TEXT__,__WRAP__,__CENTER__);

		$obj_lisha_bug->define_lov("	SELECT
											CLAS.`id` AS 'ID',
											TEX.`text` AS 'Libelle',
											CLAS.`order` AS 'ord',
											CONCAT('[img]',CLAS.`symbol`,'[/img]') AS 'symbol'
										FROM
											`".__LISHA_TABLE_EXTRA_TICK_CLAS__."` CLAS, `bugstexts` TEX
										WHERE 1 = 1
											AND TEX.`id` = CLAS.`id`
											AND TEX.`id_lang` = '".$_GET['lng']."'
											AND CLAS.`class` = 'status'",
									$_SESSION[$ssid]['lisha']['page_text'][17]['TX'],
									'ID'
								   );
		$obj_lisha_bug->define_column_lov('Libelle',$_SESSION[$ssid]['lisha']['page_text'][8]['TX'],__TEXT__,__WRAP__,__LEFT__,__PERCENT__,__DISPLAY__,true);
		$obj_lisha_bug->define_column_lov('symbol',$_SESSION[$ssid]['lisha']['page_text'][8]['TX'],__BBCODE__,__WRAP__,__LEFT__);
		$obj_lisha_bug->define_column_lov('ord',$_SESSION[$ssid]['lisha']['page_text'][18]['TX'],__TEXT__,__WRAP__,__LEFT__);
		$obj_lisha_bug->define_column_lov('ID','Iden',__TEXT__,__WRAP__,__LEFT__);
		$obj_lisha_bug->define_column_lov_order('ord',__ASC__);
		//==================================================================
				
		//==================================================================
		// define column : Status symbol
		//==================================================================
		$obj_lisha_bug->define_column('flag',$_SESSION[$ssid]['lisha']['page_text'][11]['TX'],__BBCODE__,__WRAP__,__CENTER__);
		$obj_lisha_bug->define_attribute('__column_input_check_update', __FORBIDDEN__,'flag');
		//==================================================================
				
		//==================================================================
		// define column : Action on further details
		//==================================================================
		$obj_lisha_bug->define_column('details',$_SESSION[$ssid]['lisha']['page_text'][9]['TX'],__BBCODE__,__WRAP__,__CENTER__);
		$obj_lisha_bug->define_attribute('__column_input_check_update', __FORBIDDEN__,'details');
		//==================================================================
				
		//==================================================================
		// define column : Who
		//==================================================================
		$obj_lisha_bug->define_column('qui',$_SESSION[$ssid]['lisha']['page_text'][10]['TX'],__TEXT__,__WRAP__,__CENTER__);
		//==================================================================

		//==================================================================
		// define column : Dev reference
		//==================================================================
		$obj_lisha_bug->define_column('reference',$_SESSION[$ssid]['lisha']['page_text'][19]['TX'],__BBCODE__,__WRAP__,__CENTER__);

		$obj_lisha_bug->define_lov("	SELECT
											DISTINCT
											BUG.`reference` AS 'reference',
											MAX(BUG.`Last_mod`) AS 'Lastmod'
										FROM
											`".__LISHA_TABLE_EXTRA_TICK__."` BUG
										WHERE BUG.`reference` IS NOT NULL
										GROUP BY reference",
									$_SESSION[$ssid]['lisha']['page_text'][20]['TX'],
									'reference'
								   );
		$obj_lisha_bug->define_column_lov('reference',$_SESSION[$ssid]['lisha']['page_text'][3]['TX'],__TEXT__,__WRAP__,__LEFT__);
		$obj_lisha_bug->define_column_lov('Lastmod',$_SESSION[$ssid]['lisha']['page_text'][12]['TX'],__TEXT__,__WRAP__,__LEFT__);
		$obj_lisha_bug->define_column_lov_order('Lastmod',__DESC__);
		//==================================================================
		
		//==================================================================
		// define column : Last update
		//==================================================================
		$obj_lisha_bug->define_column('last_mod',$_SESSION[$ssid]['lisha']['page_text'][12]['TX'],__DATE__,__WRAP__,__CENTER__);
		$obj_lisha_bug->define_attribute('__column_date_format','%d-%m-%Y %H:%i:%s','last_mod');
		$obj_lisha_bug->define_attribute('__column_input_check_update', __FORBIDDEN__,'last_mod');
		//==================================================================
				
		//error_log(print_r($obj_lisha_bug->c_columns,true));die();
	//==================================================================
				
	// Columns attribut for update
	
	$obj_lisha_bug->define_attribute('__column_input_check_update', __REQUIRED__,'business');
	$obj_lisha_bug->define_attribute('__column_input_check_update', __REQUIRED__,'classe');
	$obj_lisha_bug->define_attribute('__column_input_check_update', __REQUIRED__,'version');
	$obj_lisha_bug->define_attribute('__column_input_check_update', __REQUIRED__,'DateCrea');
	$obj_lisha_bug->define_attribute('__column_input_check_update', __REQUIRED__,'Description');
	$obj_lisha_bug->define_attribute('__column_input_check_update', __REQUIRED__,'qui');
	$obj_lisha_bug->define_attribute('__column_input_check_update', __REQUIRED__,'status');
		
	//==================================================================
	// Table columns primary key
	// Caution : Can't change key column name from origine query column name
	// It's not required to declare column key with define_column method
	//==================================================================
	$obj_lisha_bug->define_key(Array('id'));
	//==================================================================
	
	//==================================================================
	// Column order : Define in ascending priority means first line defined will be first priority column to order by and so on...
	//==================================================================
	$obj_lisha_bug->define_order_column('last_mod',__DESC__);					
	//==================================================================
		
	//==================================================================
	// Cyclic theme lines
	//==================================================================
	$obj_lisha_bug->define_line_theme("DDDDFF","0.7em","CCCCEE","0.8em","68B7E0","0.7em","46A5C0","0.8em","000","000");
	$obj_lisha_bug->define_line_theme("EEEEEE","0.7em","D0DCE0","0.8em","AEE068","0.7em","8CC046","0.8em","000","000");
	//==================================================================