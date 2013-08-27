<?php 	
	// Check if url point to a password
	if(stristr($_GET['url'],'password.php') == false)
	{
		// Link doesn't point to a password
		echo '<body style="background-color:#EEEEEE;font-size:13px;">';
		echo '<div style="background-image:url(../images/erreur.png);background-repeat:no-repeat;display:block;height: 48px;padding:8px 0 0 60px;margin:0 0 0 0;">';
		echo $_SESSION[$_GET['ssid']]['message'][221];
		die();	
	}
	
	// Le lien pointe sur un mot de passe
	$type_lien = 'password';
	$nom_fichier = 'password.php';
	$motif_id = '#password.php\?(.*)?ID=([0-9]+)#i';
	
	// On execute l'expression regulière pour chercher l'ID du mot de passe
	preg_match_all($motif_id,$_GET['url'],$out);

	// Récupération de l'ID
	$array_expr_id = array_values(array_unique($out[2]));

	if(isset($array_expr_id[0]))
	{
		// Id existant
		$id = $array_expr_id[0];
	}
	else
	{
		// Id inexistant
		$id = '';	
	}
	/**==================================================================
	 * HTML declare page interpretation directive
	 ====================================================================*/	
	require('../../../../../../includes/common/html_doctype.php');
	/*===================================================================*/	
?>
<html>
	<head>
	<title><?php echo  $_SESSION[$_GET['ssid']]['message'][219]; ?></title>
		<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" href="../css/iknow_tiny.css" type="text/css">
		<link rel="stylesheet" href="../../../../../../css/ifiche/tiny.css" type="text/css">
		<link rel="stylesheet" href="password.css" type="text/css">
		<script type="text/javascript" src="function.js"></script>
		<script type="text/javascript" src="../../../../../../ajax/common/ajax_generique_dev.js"></script>
		<script type="text/javascript" src="../../../tiny_mce_popup.js"></script>
		<script type="text/javascript" src="../js/iknow.js"></script>		
		<script type="text/javascript">
			var ssid = '<?php echo $_GET['ssid']; ?>';
		</script>
	<script type="text/javascript">
		var ssid = '<?php echo $_GET['ssid']; ?>';
	</script>
	</head>
	<body>
		<div class="fingerprint">
			<div><?php echo  $_SESSION[$_GET['ssid']]['message'][222]; ?> :</div>
			<form onsubmit="javascript:update_url();" id="monformsubmit" name="monformsubmit">
				<div>
					<table>
						<tr>
							<td><input type="text" id="id_password" onkeyup="ctrl_id_password(event,this.value);"size=3 style="margin:5px 10px 10px 0;" value="<?php echo $id; ?>"/></td>
							<td><img src="error.png" style="display:none;height:16px;margin:5px 10px 10px 0;" id="img_verif"/></td>
							<td><div style="display:none;" id="txt_niveau"></div></td>
						</tr>
					</table>
				</div>
				<div>
					<a href="../../../../../../../password.php" target="_blank" style="color:grey;"><?php echo $_SESSION[$_GET['ssid']]['message'][214]; ?></a>
				</div>
				<div style="margin:18px 0 0 0;">
					<input type="submit" id="insert" class="disable" name="insert"  value="<?php echo $_SESSION[$_GET['ssid']]['message'][175]; ?>" />
					<input type="button" name="cancel" id="cancel" value="<?php echo $_SESSION[$_GET['ssid']]['message'][181]; ?>" onclick="tinyMCEPopup.close();" />
				</div>
			</form>
		</div>
		<script type="text/javascript">
			document.getElementById('id_password').focus();
		</script>
	</body>
</html>