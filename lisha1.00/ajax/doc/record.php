<?php
	/**==================================================================
	 * Set of documentation framework includes
	 ====================================================================*/
	require('header.php');
	/*===================================================================*/		


	/**==================================================================
	 * Page database connexion
	 ====================================================================*/	
	require('../../includes/common/db_connect.php');
	/*===================================================================*/	


	//==================================================================
	// Load ssid identifier
	//==================================================================
	if(!isset($_POST["ssid"]))
	{
		error_log_details('fatal','No ssid define. ssid is mandatory !');
	}
	else
	{
		$_GET["ssid"] = $_POST["ssid"];
	}
	//==================================================================

	//==================================================================
	// Controle current page exists
	//==================================================================
	if(!isset($_SESSION[$ssid]['current_read_page']))
	{
		error_log_details('fatal','you need a current page');
		die();
	}
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


	$corps = str_replace("'","''", $_POST["corps"]);
	$corps = str_replace("\\","\\\\", $corps);


	//==================================================================	
	// Local page already exists ?
	//==================================================================	
	$query = "
				SELECT 1 AS 'output'
				FROM `".$_SESSION[$ssid]['MT']['ikdoc']->tree_caption."` `MTC`
				WHERE
					`MTC`.`application_release` = '".__MAGICTREE_APPLICATION_RELEASE__."'
					AND `MTC`.`language` = '".$language."'
					AND `MTC`.`id` = ".$_SESSION[$ssid]['current_read_page'].";
			";

	$result = $link->query($query);

	$row = $result->fetch_array(MYSQLI_ASSOC);
	//==================================================================

	if(!isset($row['output']))
	{
		// First, recover title node in root language
		$query = "
					SELECT `title` AS 'title'
					FROM `".$_SESSION[$ssid]['MT']['ikdoc']->tree_caption."` `MTC`
					WHERE
						`MTC`.`application_release` = '".__MAGICTREE_APPLICATION_RELEASE__."'
						AND `MTC`.`language` = '".$_SESSION[$ssid]['MT']['configuration'][1]."'
						AND `MTC`.`id` = ".$_SESSION[$ssid]['current_read_page'].";
				";

		$result = $link->query($query);

		$row = $result->fetch_array(MYSQLI_ASSOC);

		$query = "
				INSERT 
				INTO`".$_SESSION[$ssid]['MT']['ikdoc']->tree_caption."`
					(
					 `application_release`,
					 `id`,
					 `language`,
					 `title`,
					 `description`
					)
					VALUES
					(
					 '".__MAGICTREE_APPLICATION_RELEASE__."',
					 ".$_SESSION[$ssid]['current_read_page'].",
					 '".$language."',
					 '".$row['title']."',
					 '".$corps."'
					)
			";
	}
	else
	{
		$query = "
					UPDATE `".$_SESSION[$ssid]['MT']['ikdoc']->tree_caption."`
						SET `description` = '".$corps."'
					WHERE
						`application_release` = '".__MAGICTREE_APPLICATION_RELEASE__."'
						AND `language` = '".$language."'
						AND `id` = ".$_SESSION[$ssid]['current_read_page'].";
					";

	}

	// Execute query
	$result = $link->query($query);

	$num_rows = $link->affected_rows;

	if($num_rows == 0)
	{
		echo $_SESSION[$ssid]['lisha']['page_text'][8]['TX'];
	}
	else
	{
		echo $_SESSION[$ssid]['lisha']['page_text'][9]['TX'];
	}
?>