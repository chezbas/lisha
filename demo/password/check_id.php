<?php
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

	// Just to get global lisha access
	require('../includes/lishaSetup/main_configuration.php');

	/**==================================================================
	 * Load global functions
	====================================================================*/
	require('../includes/common/global_functions.php');
	/**===================================================================*/

	$url_param = url_get_exclusion($_GET);


	if(isset($_POST['login']) && isset($_POST['password']))
	{
		// Check identification
		$link_access = mysql_connect(__LISHA_DATABASE_HOST__, __LISHA_DATABASE_USER__, __LISHA_DATABASE_PASSWORD__);
		mysql_set_charset('utf8'); // FORCE_UTF8_CHARSET
		mysql_select_db(__LISHA_DATABASE_SCHEMA__,$link_access) or die('Error database connexion');

		$sql = "SELECT
						`level` LEVEL
					FROM
						`demo_group` AS `GR`
					WHERE 1 = 1
						AND `GR`.`name` ='".mysql_real_escape_string($_POST['login'])."'
						AND `GR`.`password`= AES_ENCRYPT('".mysql_real_escape_string($_POST['password'])."','FhX*24é\"3_--é0Fz.')";

		$resultat = mysql_query($sql,$link_access) or die('Error'.$sql);


		if(mysql_num_rows($resultat)>0)
		{
			// Login ok
			$_SESSION['demo'][$ssid]['identified_level'] = mysql_result($resultat,0,'level');
			$_SESSION['demo'][$ssid]['login'] = $_POST['login'];
			(isset($_GET['ID'])) ? $id = '&ID='.$_GET['ID'] : $id = '';
			header('Location: '.$_SESSION['demo'][$ssid]['redirect_page'].'?'.$url_param);
		}
		else
		{
			// Identification error
			$_SESSION['demo'][$ssid]['identified_level'] = false;
			$_SESSION['demo'][$ssid]['error_message'] = 'Identification error !!';
		}
	}
	/**==================================================================
	 * HTML declare page interpretation directive
	====================================================================*/
	require('../includes/common/html_doctype.php');
	/**===================================================================*/
?>
<html>
<head>
	<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" href="../css/admin.css" type="text/css">
	<script type="text/javascript">
		var ssid= '<?php echo $_GET["ssid"]; ?>';
	</script>
	<title>Identification screen</title>
</head>
<body>
<div id="header">
	<div style="position:absolute;top:2px;left:5px;">
		<div class="boutton_url_home boutton_outils" onclick="window.location.replace('../../?ssid='+ssid);" onmouseover="over('Portail iKnow');" onmouseout="unset_text_help();"></div>
	</div>
	<div class="logo"></div>
</div>
<div id="identification_title">ACCESS REQUIRED</div>
<div id="content">
	<div id="authentification">
		<form action="" method="post">
			<table summary="">
				<tr><td class="lib_input">User</td><td><input type="text" name="login" id="login" class="input_txt gradient"/></td></tr>
				<tr><td class="lib_input">Password</td><td><input type="password" name="password" class="input_txt gradient"/></td></tr>
				<tr><td></td><td><input type="submit" value="Login" class="submit_btn"/></td></tr>
				<tr>
					<td colspan="2">
						<div id="lib_erreur">
							<?php
							if(isset($_SESSION['demo'][$ssid]['error_message']))
							{
								echo $_SESSION['demo'][$ssid]['error_message'];
								unset($_SESSION['demo'][$ssid]['error_message']);
							}
							?>
						</div>
					</td>
				</tr>
			</table>
		</form>
	</div>
	<div class="details">
		user : admin<br>
		pass : demo<br><hr>
		user : level1 or level2 or level3<br>
		pass : demo
	</div>
</div>
<script type="text/javascript">
	document.getElementById('login').focus();
</script>
</body>
</html>