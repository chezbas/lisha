<?php
	//==================================================================
	// Current language used
	// Search special pattern <icurlang/>
	//==================================================================
	$motif = '#&lt;icurlang/&gt;#i';
	
	preg_match_all($motif,$row["DETAILS"],$out);

	foreach($out[0] as $key => $value)
	{
		$replace = $_SESSION[$ssid]['langue'];
		$row["DETAILS"] = str_replace($out[0][$key],$replace,$row["DETAILS"]);
	}
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
	//		apache					: Get web page engine ( Apache, nginx.. etc.. )
	//		vtree				 	: tree version
	//		appli					: Application name
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
	//		table_conf				: configuration table name
	//		table_language			: Languages table name
	//		table_texte				: Textes localization table name
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
			case 'vtree':
				$_local_sys = __MAGICTREE_APPLICATION_RELEASE__;
			break;
			case 'appli':
				$_local_sys = __MAGICTREE_APPLICATION_NAME;
			break;
			case 'language_url':
				$reserved_word = explode('|',$_SESSION[$ssid]['MT']['configuration'][2]);
				$_local_sys = $reserved_word[1]."=".$language;
			break;
			case 'keyword_ssid':
				$reserved_word = explode('|',$_SESSION[$ssid]['MT']['configuration'][2]);
				$_local_sys = $reserved_word[0];
			break;
			case 'keyword_language':
				$reserved_word = explode('|',$_SESSION[$ssid]['MT']['configuration'][2]);
				$_local_sys = $reserved_word[1];
			break;
			case 'table_conf':
				$_local_sys = __MAGICTREE_TABLE_SETUP__;
			break;
			case 'table_language':
				$_local_sys = __MAGICTREE_TABLE_LANGUAGE__;
			break;
			case 'table_texte':
				$_local_sys = __MAGICTREE_TABLE_TEXT__;
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
?>