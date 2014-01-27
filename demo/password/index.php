<?php
	/**==================================================================
	 * Load common constants
	 ====================================================================*/
	require('../includes/common/constante.php');
	/**===================================================================*/

	/**==================================================================
	 * Get/Set ssid window identifier
	 * Start unique php session with ssid name
	 ====================================================================*/
	require('../includes/common/ssid_session_start.php');
	/**===================================================================*/


	/**==================================================================
	 * Page buffering ( !! No output ( echo, print_r etc..) before this include !! )
	 ====================================================================*/
	require('../includes/common/buffering.php');
	/**===================================================================*/


	/**==================================================================
	 * Load global functions
	====================================================================*/
	require('../includes/common/global_functions.php');
	/**===================================================================*/


	/**==================================================================
	 * Authentication required
	====================================================================*/
	$url_param = url_get_exclusion($_GET,array('mode','lng'));
	$_SESSION['demo'][$ssid]['redirect_page'] = getenv("SCRIPT_NAME");
	$_SESSION['demo'][$ssid]['logout_page'] = 'check_id.php';
	$_SESSION['demo'][$ssid]['level_require_path'] = './';

	require('./check_login.php');
	/**===================================================================*/


	/**==================================================================
	 * Lisha configuration and framework includes
	====================================================================*/
	// Lisha main hard coded definition
	require('../includes/lishaSetup/main_configuration.php');
	$path_root_lisha =  '../../'.__LISHA_APPLICATION_RELEASE__;

	// Lisha load main customized database configuration
	require($path_root_lisha.'/includes/LishaSetup/custom_configuration.php');

	// Lisha using language
	require($path_root_lisha.'/includes/common/language.php');

	// Lisha read localization features
	require($path_root_lisha.'/includes/LishaSetup/lisha_localization.php');

	// Lisha framework includes
	require($path_root_lisha.'/lisha_includes.php');
	/**===================================================================*/


	$_SESSION[$ssid]['langue'] = $_SESSION[$ssid]['lisha']['langue']; // Recover main page language from lisha

	/**==================================================================
	 * Setup page max timeout
	 ====================================================================*/	
	require('../includes/common/page_timeout.php');
	/**===================================================================*/


	/**==================================================================
	 * HTML declare page interpretation directive
	 ====================================================================*/	
	require('../includes/common/html_doctype.php');
	/**===================================================================*/
?>
<html>
	<head>
		<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" href="../css/password.css" type="text/css">
		<script type="text/javascript">
			<?php
				/**==================================================================
				 * Load text in both php session and javascript
				 * Warning : Must be in <head> html bloc
				 ====================================================================*/
				require('../includes/common/textes.php');
				echo chr(10);
				/*===================================================================*/
			?>
		</script>
		<?php
		//==================================================================
		// Lisha HTML header generation
		//==================================================================
		lisha::generate_common_html_header($ssid);	// Once
		//==================================================================

		/**==================================================================
		 * Include all Lisha list setup
		====================================================================*/
		include ('../includes/LishaDefine/password.php');
		/**===================================================================*/

		?>
		<script type="text/javascript" src="../js/password.js"></script> <!-- Custom javascript -->

		<title><?php echo $_SESSION[$ssid]['message'][3]?></title>
	</head>
	<body onmousemove="lisha_move_cur(event);" onmouseup="lisha_mouseup();">
	<div class="mydiv"><?php echo $obj_lisha_password->generate_lisha(); ?></div>
	<div class="logout" onclick="logout();">LOG OUT</div>
	<?php $obj_lisha_password->lisha_generate_js_body();?>

	<?php
	//==================================================================
	// Lisha HTML bottom generation
	//==================================================================
	lisha::generate_common_html_bottom($obj_lisha_password->c_dir_obj,$_SESSION[$ssid]['lisha']['configuration'][12],$_SESSION[$ssid]['lisha']['langue']);	// Once
	//==================================================================
	?>
	</body>
</html>