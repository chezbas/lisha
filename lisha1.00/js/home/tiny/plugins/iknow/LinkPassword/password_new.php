<?php
	/**==================================================================
	 * HTML declare page interpretation directive
	 ====================================================================*/	
	require('./../../../../../../includes/common/html_doctype.php');
	/*===================================================================*/	
?>
<html>
	<head>
		<title><?php echo $_SESSION[$_GET['ssid']]['message'][213]; ?></title>
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
	</head>
	<body>
		<div class="fingerprint">
			<div><?php echo  $_SESSION[$_GET['ssid']]['message'][215]; ?> :</div>
			<form onsubmit="javascript:inserer_texte();" id="monformsubmit" name="monformsubmit">
				<div>
					<table>
						<tr>
							<td><input type="text" id="id_password" onkeyup="ctrl_id_password(event,this.value)"size=3 style="margin:5px 10px 10px 0;"/></td>
							<td><img src="error.png" style="display:none;height:16px;margin:5px 10px 10px 0;" id="img_verif"/></td>
							<td><div style="display:none;" id="txt_niveau"></div></td>
						</tr>
					</table>
				</div>
				<div>
					<?php echo  $_SESSION[$_GET['ssid']]['message'][218]; ?> :
					<input type="text" id="lbl_password" size=15 style="margin:5px 10px 4px 0;" value="<?php echo $_SESSION[$_GET['ssid']]['message'][217]; ?>"/>
				</div>
				<div>
					<a href="../../../../../../../password.php" target="_blank" style="color:grey;"><?php echo $_SESSION[$_GET['ssid']]['message'][214]; ?></a>
				</div>
				<div style="margin:18px 0 0 0;">
					<input type="submit" id="insert" class="disable" name="insert"  value="<?php echo $_SESSION[$_GET['ssid']]['message'][146]; ?>" />
					<input type="button" name="cancel" id="cancel" value="<?php echo $_SESSION[$_GET['ssid']]['message'][181]; ?>" onclick="tinyMCEPopup.close();" />
				</div>
			</form>
		</div>
		<script type="text/javascript">
			document.getElementById('id_password').focus();
		</script>
	</body>
</html>