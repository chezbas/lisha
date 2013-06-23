<?php
	require('../session.php');
	$selection = $_GET['selection'];

	/**==================================================================
	 * HTML declare page interpretation directive
	 ====================================================================*/	
	require('../../../../../../includes/common/html_doctype.php');
	/*===================================================================*/	
?>
<html>
	<head>
		<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title><?php echo $_SESSION[$ssid]['message'][444]; ?></title>
		<link rel="stylesheet" href="../css/iknow_tiny.css" type="text/css">
		<link rel="stylesheet" href="style.css" type="text/css">
		<link rel="stylesheet" href="../../../../../../css/ifiche/tiny.css" type="text/css">
		<link rel="stylesheet" href="../../../../../../css/common/icones_iknow.css" type="text/css">
		<script type="text/javascript" src="function.js"></script>
		<script type="text/javascript" src="../../../tiny_mce_popup.js"></script>
		<script type="text/javascript" src="../js/iknow.js"></script>
		<?php
			/**==================================================================
			 * Vimofy init
			 ====================================================================*/
			require('../../../../../../includes/ifiche/vimofy/edit/tiny/init_vim_var_ikcalc.php');
			/*===================================================================*/

			/*==================================================================
			* Vimofy internal init
			====================================================================*/
			$_SESSION['vimofy'][$ssid]['vim_varin_tiny']->generate_public_header();   
			$_SESSION['vimofy'][$ssid]['vim_varin_tiny']->vimofy_generate_header();
			/*===================================================================*/    
		?>
		<script type="text/javascript">
			del_css('dialog.css') ;
		</script>
	</head>
	<body style="font-size:16px;" onmousemove="vimofy_move_cur(event);" onmouseup="vimofy_mouseup();">
	<?php 
		if($selection == '')
		{
			echo '<form action="javascript:ikcalc_tiny_insert_value(document.getElementById(\'formule\').value);">';
		}
		else
		{
			echo '<form action="javascript:ikcalc_tiny_update_value(document.getElementById(\'formule\').value);">';
		}
	
	?>
			Formule : <input id="formule" type="text" class="formule_input" onkeyup="document.getElementById('formule_div').innerHTML = this.value;" onchange="document.getElementById('formule_div').innerHTML = this.value;" value="<?php echo str_replace('"','&quot;',$selection); ?>"/>
			<div class="formule_input" style="height:22px;" id="formule_div"><?php echo $selection; ?></div>
			<div id="formule_value"></div>
			<?php 
				if($selection == '')
				{
					echo '<input type="submit" value="'.$_SESSION[$ssid]['message'][146].'"/>';
				}
				else
				{
					echo '<input type="submit" value="'.$_SESSION[$ssid]['message'][445].'"/>';
				}
			?>
		</form>
			<div style="width:100%;bottom:0;top:110px;position:absolute;background-color:#999;" id="vim_varin_tiny">
				<?php echo $_SESSION['vimofy'][$ssid]['vim_varin_tiny']->generate_vimofy(); ?>
			</div>
		<?php
			$_SESSION['vimofy'][$ssid]['vim_varin_tiny']->vimofy_generate_js_body();
		?>
	</body>
</html>