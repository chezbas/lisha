<?php
	/**==================================================================
	 * Automatic detection of current lisha version
	 ====================================================================*/
	require('../includes/common/version_active.php');
	/*===================================================================*/		

	$application_release = $version_soft;

	/**==================================================================
	 * Get/Set ssid window identifier
	 * Start unique php session with ssid name
	 ====================================================================*/
	define("__PREFIX_URL_COOKIES__","");
	require('../includes/common/ssid_session_start.php');
	/*===================================================================*/		


	/**==================================================================
	 * Page buffering ( !! No output ( echo, print_r etc..) before this include !! )
	 ====================================================================*/
	require('../includes/common/buffering.php');
	/*===================================================================*/	


	/**==================================================================
	 * Load global functions
	 ====================================================================*/	
	require('../includes/common/global_functions.php');
	/*===================================================================*/	


	/**==================================================================
	 * Lisha configuration and framework includes
	 ====================================================================*/
	// Lisha main hard coded definition
	require('../../includes/lishaSetup/main_configuration.php');

	$path_root_lisha = '../';
	// Lisha load main customized database configuration
	require('../includes/LishaSetup/custom_configuration.php');

	// Lisha using language
	require('../includes/common/language.php');

	// Lisha read localization features
	require('../includes/LishaSetup/lisha_localization.php');

	// Lisha framework includes
	require('../lisha_includes.php');
	/*===================================================================*/		


	/**==================================================================
	 * Setup page max timeout
	 ====================================================================*/	
	require('../includes/common/page_timeout.php');
	/*===================================================================*/	


	/**==================================================================
	 * HTML declare page interpretation directive
	 ====================================================================*/	
	require('../includes/common/html_doctype.php');
	/*===================================================================*/	
?>
<html>
	<head>
		<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" href="css/index.css" type="text/css">
		<script type="text/javascript">
			var ssid = '<?php echo $ssid; ?>';
		</script>
		<?php 
			/**==================================================================
			 * Load text in both php session and javascript
			 * Warning : Must be in <head> html bloc 
			 ====================================================================*/	
			$type_screen = 'bug';
			require('../includes/common/textes.php');
			echo chr(10);
			/*===================================================================*/


			/**==================================================================
			 * Lisha init
			 ====================================================================*/	
			include ('init_liste_bugs.php');
			/*===================================================================*/	

			//==================================================================
			// Lisha transactional init
			//==================================================================
			$_SESSION[$ssid]['lisha'][$lisha_bug]->generate_public_header();   
			$_SESSION[$ssid]['lisha'][$lisha_bug]->generate_header();
			//==================================================================
		?>
		<script type="text/javascript" src="ajax/common/ajax_generique_dev.js"></script>
		<script type="text/javascript" src="js/common/json.js"></script>
		<script type="text/javascript" src="js/index.js"></script>

		<title><?php echo $_SESSION[$ssid]['lisha']['page_text'][1]['TX'];?></title>
	</head>
	<body onmousemove="lisha_move_cur(event);" onmouseup="lisha_mouseup();">

		<div style="width:100%;bottom:0;top:0;position:absolute;">
			<?php echo $_SESSION[$ssid]['lisha'][$lisha_bug]->generate_lisha(); ?>
		</div>

		<?php 
			$_SESSION[$ssid]['lisha'][$lisha_bug]->lisha_generate_js_body();
		?>
	</body>
</html>