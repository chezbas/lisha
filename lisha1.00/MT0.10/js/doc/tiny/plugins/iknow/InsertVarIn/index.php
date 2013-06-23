<?php 
	require('../session.php');
	
	/**==================================================================
	 * HTML declare page interpretation directive
	 ====================================================================*/	
	require('../../../../../../includes/common/html_doctype.php');
	/*===================================================================*/	
?>
<html>
	<head>
		<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" href="../css/iknow_tiny.css" type="text/css">
		<link rel="stylesheet" href="../../../../../../css/ifiche/tiny.css" type="text/css">
		<link rel="stylesheet" href="../../../../../../css/common/icones_iknow.css" type="text/css">
		<script type="text/javascript" src="function.js"></script>
		<script type="text/javascript" src="../../../tiny_mce_popup.js"></script>
		<script type="text/javascript" src="../js/iknow.js"></script>
		<?php 
			/**==================================================================
			 * Vimofy init
			 ====================================================================*/	
			require('../../../../../../includes/ifiche/vimofy/edit/tiny/vim_varin_step.php');
			/*===================================================================*/	
			
			echo '<title>'.$_SESSION[$ssid]['message'][59].'</title>';
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
		<div class="bloc">
			<div style="width:100%;bottom:0;top:0;position:absolute;background-color:#999;" id="vim_varin_tiny">
				<?php echo $_SESSION['vimofy'][$ssid]['vim_varin_tiny']->generate_vimofy(); ?>
			</div>
		</div>
		<?php 
			$_SESSION['vimofy'][$ssid]['vim_varin_tiny']->vimofy_generate_js_body();
		?>
	</body>
</html>