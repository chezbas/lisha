<?php
	/**==================================================================
	 * Set of documentation framework includes
	 ====================================================================*/
	require('header.php');
	/*===================================================================*/		


	//==================================================================
	// Get tree ID
	//==================================================================
	if(!isset($_POST["internal_id"]))
	{
		error_log_details('fatal','you need an tree ID');
		die();
	}
	$tree_id = $_POST["internal_id"];
	//==================================================================	

	//==================================================================
	// Get ID item node
	//==================================================================
	if(!isset($_POST["IDitem"]))
	{
		error_log_details('fatal','you need an item ID');
		die();
	}
	$id_item= $_POST["IDitem"];
	$_SESSION[$ssid]['MT']['current_read_page'] = $id_item;
	//==================================================================	

	//==================================================================
	// Get tree mode
	//==================================================================
	if(!isset($_POST["mode"]))
	{
		error_log_details('fatal','you need an mode');
		die();
	}
	$mode= $_POST["mode"];
	//==================================================================	

	//==================================================================
	// Get language in use
	//==================================================================
	if(!isset($_POST["language"]))
	{
		error_log_details('fatal','you need an language');
		die();
	}
	$language= $_POST["language"];
	//==================================================================	

	//==================================================================
	// Source path of calling script
	//==================================================================
	if(!isset($_POST["pathname"]))
	{
		error_log_details('fatal','you need an script pathname');
		die();
	}
	$pathname = $_POST["pathname"];
	//==================================================================

	//==================================================================
	// Try local language if exists first
	//==================================================================
	$query = "	SELECT 
					`MTC`.`description` AS 'DETAILS'
				FROM `".$_SESSION[$ssid]['MT']['ikdoc']->tree_caption."` `MTC`
			WHERE
				`MTC`.`application_release` = '".__MAGICTREE_APPLICATION_RELEASE__."'
				 AND `MTC`.`language` = '".$language."'
				 AND `MTC`.`id` = ".$id_item."
			";

	$result = $link->query($query);
	$row = $result->fetch_array(MYSQLI_ASSOC);

	/**==================================================================
	 * Common dynamic page translation
	 ====================================================================*/	
	require('../../ajax/common/dynamic_doc.php');
	/*===================================================================*/	

	// Try local language first
	if( (!isset($row["DETAILS"]) || $row["DETAILS"] == '')  && $_SESSION[$ssid]['MT']['configuration'][1] != $language )
	{
		// No page define for this page, recover default language page
		$query = "	SELECT 
						`MTC`.`description` AS 'DETAILS'
					FROM `".$_SESSION[$ssid]['MT']['ikdoc']->tree_caption."` `MTC`
				WHERE
					`MTC`.`application_release` = '".__MAGICTREE_APPLICATION_RELEASE__."'
					 AND `MTC`.`language` = '".$_SESSION[$ssid]['MT']['configuration'][1]."'
					 AND `MTC`.`id` = ".$id_item."
				";

		$result = $link->query($query);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$row["DETAILS"] = '<div style="background-color: #fff; opacity:0.3;">'.$row["DETAILS"].'</div>';
	}
	//==================================================================

	//==================================================================
	// Get localization feature using field name
	// Search special pattern <ilocal:xx/>
	// xx values come from your lisha configuration table : __LISHA_TABLE_LOCALIZATION__
	// text         : To get current language in text
	// date_format  : To get date format pattern
	//==================================================================
	$motif = '#&lt;ilocal:([^/]+)[ ]*/&gt;#i';

	preg_match_all($motif,$row["DETAILS"],$out);

	foreach($out[1] as $key => $value)
	{
		$query = "
						SELECT
							`MTL`.`".$value."` AS 'sortie'
						FROM
							`".__LISHA_TABLE_LOCALIZATION__."` `MTL`
						WHERE 1 = 1
							AND `MTL`.`id`		= '".$language."'
					";

		$result = $link->query($query);
		$rowl = $result->fetch_array(MYSQLI_ASSOC);

		$replace = $rowl['sortie'];
		$row["DETAILS"] = str_replace($out[0][$key],'<span class="auto_field">'.$replace.'</span>',$row["DETAILS"]);
	}
	//==================================================================

	//==================================================================
	// Search special pattern <isym:lisha/>
	//==================================================================
	$motif = '#&lt;isym:([^/]+)[ ]*/&gt;#i';

	preg_match_all($motif,$row["DETAILS"],$out);

	foreach($out[1] as $key => $value)
	{
		$replace = '<span class="auto_field">'.ucfirst($value).'</span>';
		$row["DETAILS"] = str_replace($out[0][$key],$replace,$row["DETAILS"]);

	}	
	//==================================================================	

	//==================================================================
	// Configuration parameters of tree documentation
	// Pattern <MTconf:xx/>
	//==================================================================
	$motif = '#&lt;MTconf:([^/]+)[ ]*/&gt;#i';

	preg_match_all($motif,$row["DETAILS"],$out);

	foreach($out[1] as $key => $value)
	{
		$replace = $_SESSION[$ssid]['MT']['configuration'][$value];
		$row["DETAILS"] = str_replace($out[0][$key],'<span class="auto_field">'.$replace.'</span>',$row["DETAILS"]);

	}	
	//==================================================================	

	//==================================================================
	// Configuration parameters of lisha
	// Pattern <LIconf:xx/>
	//==================================================================
	$motif = '#&lt;LIconf:([^/]+)[ ]*/&gt;#i';

	preg_match_all($motif,$row["DETAILS"],$out);

	foreach($out[1] as $key => $value)
	{
		$replace = $_SESSION[$ssid]['lisha']['configuration'][$value];
		$row["DETAILS"] = str_replace($out[0][$key],'<span class="auto_field">'.$replace.'</span>',$row["DETAILS"]);

	}	
	//==================================================================	

	//==================================================================
	// Lisha global configuration
	// Pattern <iglo:xx/>
	//==================================================================
	$motif = '#&lt;iglo:([^/]+)[ ]*/&gt;#i';
	preg_match_all($motif,$row["DETAILS"],$out);

	foreach($out[1] as $key => $value)
	{
		$replace = $_SESSION[$ssid]['lisha'][$value];
		$row["DETAILS"] = str_replace($out[0][$key],'<span class="auto_field">'.$replace.'</span>',$row["DETAILS"]);

	}	
	//==================================================================	

	//==================================================================
	// Replace by node title
	// Search special pattern <ipage:page_number label/>
	// page_number : Mandatory
	// label : replace ipage title if needed
	// Eg : <ipage:77 tata/> will display tata and link jump to page 77
	//==================================================================
	//$motif = '#&lt;ipage:([^&nbsp; ]*)[&nbsp; ]*([^/]*)/&gt;#i';
	$motif = '#&lt;ipage:([0-9]+)([^/]*)/&gt;#i';

	preg_match_all($motif,$row["DETAILS"],$out);

	foreach($out[1] as $key => $value)
	{
		$query = "
				SELECT
				`MTC`.`title` AS 'value'
			FROM 
				`".$_SESSION[$ssid]['MT']['ikdoc']->tree_caption."` `MTC`
			WHERE 1 = 1
				AND `MTC`.`application_release`	= '".__MAGICTREE_APPLICATION_RELEASE__."'
				AND `MTC`.`language` = '".$language."'
				AND `MTC`.`id`		= ".$value."
		";
		$result = $link->query($query);
		$rowl = $result->fetch_array(MYSQLI_ASSOC);

		if(!isset($rowl['value']))
		{
			// No title yet defined in local language, recover title from root language
			// add red flag to show that value come from root language
			$query = "
					SELECT
					CONCAT('<span class=\"language_not_exists\">',`MTC`.`title`,'</span>') AS 'value'
				FROM 
					`".$_SESSION[$ssid]['MT']['ikdoc']->tree_caption."` `MTC`
				WHERE 1 = 1
					AND `MTC`.`application_release`	= '".__MAGICTREE_APPLICATION_RELEASE__."'
					AND `MTC`.`language` = '".$_SESSION[$ssid]['MT']['configuration'][1]."'
					AND `MTC`.`id`		= ".$value."
			";
			$result = $link->query($query);
			$rowl = $result->fetch_array(MYSQLI_ASSOC);
		}

		if($out[2][$key] == '')
		{
			$replace = '<span class="jump" onclick="jump_screen(\''.$value.'\',\''.$pathname.'?id='.'\')">'.$rowl['value'].'</span>';
		}
		else
		{
			$replace = '<span class="jump" onclick="jump_screen(\''.$value.'\',\''.$pathname.'?id='.'\')">'.$out[2][$key].'</span>';
		}
		$row["DETAILS"] = str_replace($out[0][$key],$replace,$row["DETAILS"]);

	}	
	//==================================================================	

	//==================================================================
	// Text from lisha_text table
	// Pattern : <ilisha:xx/>
	//==================================================================
	$motif = '#&lt;ilisha:([0-9]+)([^/]*)/&gt;#i';
	preg_match_all($motif,$row["DETAILS"],$out);

	//error_log(print_r($_SESSION[$ssid]['lisha']['lib'],true));
	foreach($out[1] as $key => $value)
	{
		$replace = $_SESSION[$ssid]['lisha']['lib'][$value];

		$row["DETAILS"] = str_replace($out[0][$key],'<span class="auto_text">'.$replace.'</span>',$row["DETAILS"]);
	}
	//==================================================================

	//==================================================================
	// Build path string
	//==================================================================
	$id=$id_item;

	$tab_to_root = array();
	$current = get_parent($ssid,$language,$link,$id);
	while( $current[1] != '') // Till a parent exists
	{
		array_unshift($tab_to_root,array($current[0],$current[1],$current[2]));
		$current = get_parent($ssid,$language,$link,$current[1]);
	}

	$preference = '<div id="boutton_preference" class="boutton_preference" onClick="active_expand_tools_bar()" onmouseout="lib_hover(\'\')" onmouseover="lib_hover(\''.js_protect($_SESSION[$ssid]['lisha']['page_text'][7]['TX']).'\')"></div>';

	// Home path
	$path_to_root = $preference.'<div class="home_path" onclick="jump_screen(\'1\',\''.$pathname.'?id='.'\')" onmouseout="lib_hover(\'\')" onmouseover="lib_hover(\''.js_protect("homepath").'\')"></div> : ';
	$separator = '';
	while(list($parent,$value) = each($tab_to_root))
	{
		//==================================================================
		// Manage BBCode is setup on
		//==================================================================
		$value[2] = htmlentities($value[2],ENT_QUOTES);

		if($_SESSION[$ssid]['MT']['ikdoc']->use_bbcode)
		{
			$value[2] = $_SESSION[$ssid]['MT']['ikdoc']->convertBBCodetoHTML($value[2]);
		}
		//==================================================================
		$path_to_root = $path_to_root . '<span class="mouse" onclick="jump_screen(\''.$value[0].'\',\''.$pathname.'?id='.'\')">'.$value[2].'</span>' . $separator;
	}

	if($separator <> '')
	{
		$path_to_root = substr($path_to_root,0,-strlen($separator));
	}
	//==================================================================

	$html = '<div id="head_path" class="head_path"><span class="lien_arbo">'.$path_to_root.'</span></div>
	<div id="details_body" class="details_body">'.$row["DETAILS"].'</div>';

	// Keep current page loaded in php session
	$_SESSION[$ssid]['current_read_page'] = $id;

	$retour = array("HTML" => $html,"PATH" => $path_to_root);
	echo json_encode($retour);

	//==================================================================
	// return parent of $id
	//==================================================================
	function get_parent($ssid,$language,$link,$id)
	{
		$query = "	SELECT
						`MTN`.`id` AS 'id',
						`MTC`.`title` AS 'value',
						`MTN`.`parent` AS 'parent'
					FROM 
						`".$_SESSION[$ssid]['MT']['ikdoc']->tree_node."` `MTN`,
						`".$_SESSION[$ssid]['MT']['ikdoc']->tree_caption."` `MTC`
					WHERE 1 = 1
						AND `MTN`.`id` = `MTC`.`id`
						AND `MTN`.`application_release` = `MTC`.`application_release`
						AND `MTN`.`application_release`	= '".__MAGICTREE_APPLICATION_RELEASE__."'
						AND `MTC`.`language` = '".$language."'
						AND `MTN`.`id`		= ".$id."
				";
		$result = $link->query($query);
		$row = $result->fetch_array(MYSQLI_ASSOC);		
		// No local language define, recover root language and mark in red
		if (!isset($row['id']))
		{
			$query = "	SELECT
							`MTN`.`id` AS 'id',
							CONCAT('<span class=\"language_not_exists\">',`MTC`.`title`,'</span>') AS 'value',
							`MTN`.`parent` AS 'parent'
						FROM 
							`".$_SESSION[$ssid]['MT']['ikdoc']->tree_node."` `MTN`,
							`".$_SESSION[$ssid]['MT']['ikdoc']->tree_caption."` `MTC`
						WHERE 1 = 1
							AND `MTN`.`id` = `MTC`.`id`
							AND `MTN`.`application_release` = `MTC`.`application_release`
							AND `MTN`.`application_release`	= '".__MAGICTREE_APPLICATION_RELEASE__."'
							AND `MTC`.`language` = '".$_SESSION[$ssid]['MT']['ikdoc']->root_language."'
							AND `MTN`.`id`		= ".$id."
					";
			$result = $link->query($query);
			$row = $result->fetch_array(MYSQLI_ASSOC);		
		}

		return array($row['id'],$row['parent'],$row['value']);		
	}
	//==================================================================