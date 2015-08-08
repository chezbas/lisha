<?php
	//==================================================================

	//==================================================================
	// Display system information
	// Search special pattern <isys:$system/>
	// $system will have value below
	// 		language 	 		 	: current language used
	//		language_url		 	: keyword plus language currently in use
	//		keyword_id			 	: Get keyword to design iobject id
	//		keyword_ssid		 	: Get keyword to design session identifier
	//		keyword_version		 	: Get keyword to force version in URL
	//		keyword_language	 	: Get keyword to force language in URL
	//		keyword_valorization 	: Get keyword to force setup valorization mode
	//		php					 	: php version
	//		MySQL				 	: MySQL version
	//		apache				 	: apache version
	//		version				 	: lisha version
	//		appli					: lisha application name
	//		browser				 	: Browser client
	//		session_timeout		 	: Session timeout
	//		ssid				 	: Current ssid number
	//		table_config		 	: Configuration table
	//		table_custom		 	: Custom view table
	//		table_doc_user		 	: table for user documentation ( main nodes )
	//		table_doc_user_caption	: table for user documentation ( details content with localization )
	//		table_doc_tech			: table for technical documentation ( main nodes )
	//		table_doc_tech_caption	: table for technical documentation ( details content with localization )
	//		table_tickets			: table of tickets
	//		table_tickets_texts		: table of text element for bug / tickets screen
	//		table_tickets_class		: table of class ticket translation
	//==================================================================
	$motif = '#&lt;isys:([^/]+)[ ]*/&gt;#i';

	preg_match_all($motif,$row["DETAILS"],$out);

	foreach($out[1] as $key => $value)
	{
		$_local_sys = '';

		switch($value)
		{
			case 'language':
				$_local_sys = $language;
			break;
			case 'php':
				$_local_sys = phpversion();
			break;
			case 'MySQL':
				$_local_sys = $link->server_info;
			break;
			case 'apache':
				//$_local_sys = apache_get_version();
				$_local_sys = $_SERVER["SERVER_SOFTWARE"];
			break;
			case 'version':
				$_local_sys = __LISHA_APPLICATION_RELEASE__;
			break;
			case 'appli':
				$_local_sys = __LISHA_APPLICATION_NAME__;
			break;
			case 'language_url':
				$_local_sys = $_SESSION[$ssid]['lisha']['configuration'][12]."=".$language;
			break;
			case 'keyword_ssid':
				$reserved_word = $_SESSION[$ssid]['lisha']['configuration'][2];
				$_local_sys = $reserved_word[1];
			break;
			case 'keyword_language':
				$_local_sys = $_SESSION[$ssid]['lisha']['configuration'][12];
			break;
			case 'table_config':
				$_local_sys = __LISHA_TABLE_SETUP__;
			break;
			case 'table_custom':
				$_local_sys = __LISHA_TABLE_FILTER__;
			break;
			case 'table_language':
				$_local_sys = __LISHA_TABLE_LOCALIZATION__;
			break;
			case 'table_doc_user':
				$_local_sys = __MT_TABLE_USER_DOCU__;
			break;
			case 'table_doc_user_caption':
				$_local_sys = __MT_TABLE_USER_DOCU_CAPTION__;
			break;
			case 'table_doc_tech':
				$_local_sys = __MT_TABLE_TECH_DOCU__;
			break;
			case 'table_doc_tech_caption':
				$_local_sys = __MT_TABLE_TECH_DOCU_CAPTION__;
			break;
			case 'table_tickets':
				$_local_sys = __LISHA_TABLE_EXTRA_TICK__;
			break;
			case 'table_tickets_texts':
				$_local_sys = __LISHA_TABLE_EXTRA_TICK_TEXT__;
			break;
			case 'table_tickets_class':
				$_local_sys = __LISHA_TABLE_EXTRA_TICK_CLAS__;
			break;
			case 'ssid':
				$_local_sys = $ssid;
			break;
			case 'session_timeout':
				$temps = ini_get('session.gc_maxlifetime');
				$heure = floor($temps/3600);
				$minute = floor(($temps - ($heure * 3600))/60);
				$string_heure = str_pad($heure, 2, "0", STR_PAD_LEFT);
				$string_minute = str_pad($minute, 2, "0", STR_PAD_LEFT);
				$_local_sys = $string_heure.":".$string_minute;
			break;
			case 'browser':
				$ua = $_SERVER["HTTP_USER_AGENT"];

				// Safari
				$motif = '#Version/([^ ]+) ([^ ]+)/#i';
				if(preg_match_all($motif,$ua,$browser))
				{	
					$_local_sys = $browser[2][0]." - ".$browser[1][0];
				}

				// Firefox
				$motif = '#(firefox)/([^ ]+)#i';
				if(preg_match_all($motif,$ua,$browser))
				{	
					$_local_sys = $browser[1][0]." - ".$browser[2][0];
				}

				// Chrome
				$motif = '#(chrome)/([^ ]+)#i';
				if(preg_match_all($motif,$ua,$browser))
				{	
					$_local_sys = $browser[1][0]." - ".$browser[2][0];
				}

				// Opera
				$motif = '#(opera).+Version/([^ ]+)#i';
				if(preg_match_all($motif,$ua,$browser))
				{	
					$_local_sys = $browser[1][0]." - ".$browser[2][0];
				}
				//error_log($ua);
			break;
		}
		$row["DETAILS"] = str_replace($out[0][$key],$_local_sys,$row["DETAILS"]);

	}	
	//==================================================================

	//==================================================================
	// Search special pattern <ilocalization/>
	//==================================================================
	$motif = '#&lt;ilocalization/&gt;#i';
	preg_match_all($motif,$row["DETAILS"],$out);

	foreach($out[0] as $key => $value)
	{
		$replace = '
				<table class="main_table">
				<tr class="head_table">
				<td>ID</td>
				<td>&lt;ilisha:127/&gt;</td>
				</tr>';

		$query = "
							SELECT
								`MTS`.`ID`      AS 'iden',
								`MTS`.`text`    AS 'local'
							FROM
								`".__LISHA_TABLE_LOCALIZATION__."` `MTS`
							WHERE 1 = 1
						 ";

		$result = $link->query($query);

		while($rowl = $result->fetch_array(MYSQLI_ASSOC))
		{
			$replace .= '<tr class="row_table">
					<td>'.$rowl['iden'].'</td>
					 <td>'.$rowl['local'].'</td>
					 </tr>
					';
		};


		$replace .= '</table>';
		$row["DETAILS"] = str_replace($out[0][$key],$replace,$row["DETAILS"]);
	}
	//==================================================================