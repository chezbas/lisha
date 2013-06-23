<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<META http-equiv="Content-Type" content="text/html; charset=UTF-8">	
		<link rel="shortcut icon" type="image/png" href="favicon.ico">
<?php
	// Get the datehour of the begin of visualisation
	$_SESSION[$ssid]['start_visu'] = date('d/m/y H:i');
		
	echo '<script type="text/javascript">';
	/**==================================================================
	* Database connexion and load conf parameters in both session and javascript
	====================================================================*/	
	require('load_conf_session_js.php');
	/*===================================================================*/

	/**==================================================================
	* Recover language
	====================================================================*/	
	require('language.php');
	echo "var iknow_lng = '".$_SESSION[$ssid]['langue']."';";	
	echo "var iknow_lng_tinyMCE = '".$_SESSION[$ssid]['langue_TinyMCE']."';";	
	/*===================================================================*/
		
	/**==================================================================
	* Recover text
	====================================================================*/	
	$_SESSION[$ssid]['application'] = $type_soft;
	require('includes/common/textes.php');
	echo '</script>';
	/*===================================================================*/
	
	if(!isset($_GET['version'])) $_GET['version'] = null;
	
	
	/**==================================================================
	* Define page session timeout
	* Set a protection of minimum time to 1 hour
	====================================================================*/	
	if($_SESSION[$ssid]['configuration'][41] < 3600)
	{
		ini_set('session.gc_maxlifetime',3600);
	}
	else
	{
		ini_set('session.gc_maxlifetime',$_SESSION[$ssid]['configuration'][41]);
	}
	/*===================================================================*/
?>
<!--************************************************************************************************************
 *		GENERATION DE LA PARTIE STATIQUE SIMPLIFIEE DE L'ENTETE DE LA PAGE		
 *************************************************************************************************************-->	
<link rel="stylesheet" href="css/common/err_sql.css" type="text/css">	
<link rel="stylesheet" href="css/common/iknow/iknow_panel.css" type="text/css">
<link rel="stylesheet" href="css/common/iknow/iknow_msgbox.css" type="text/css">
<script type="text/javascript" src="js/common/iknow/iknow_msgbox.js"></script>
<script type="text/javascript" src="js/common/iknow/iknow_panel.js"></script>
<script type="text/javascript" src="js/common/iknow/iknow_effect.js"></script>
<script type="text/javascript" src="js/common/iknow/iknow_timer.js"></script>
<!--**********************************************************************************************************-->