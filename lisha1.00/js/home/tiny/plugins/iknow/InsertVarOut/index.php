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
		<script type="text/javascript" src="function.js"></script>
		<script type="text/javascript" src="../../../tiny_mce_popup.js"></script>
		<script type="text/javascript" src="../js/iknow.js"></script>
		<?php 
			/**==================================================================
			 * Vimofy init
			 ====================================================================*/	
			require('../../../../../../includes/ifiche/vimofy/edit/tiny/vim_varout_step.php');
			require('../../../../../../includes/ifiche/vimofy/edit/tiny/vim_varout_ext_step.php');
			/*===================================================================*/	
			
			echo '<title>'.$_SESSION[$ssid]['message'][60].'</title>';
			/*==================================================================
			* Vimofy internal init
			====================================================================*/  
			$_SESSION['vimofy'][$ssid]['vim_varout_tiny']->generate_public_header();   
			$_SESSION['vimofy'][$ssid]['vim_varout_tiny']->vimofy_generate_header();
			$_SESSION['vimofy'][$ssid]['vim_varout_tiny_ext']->generate_public_header();   
			$_SESSION['vimofy'][$ssid]['vim_varout_tiny_ext']->vimofy_generate_header();
			/*===================================================================*/    
		?>
		<script type="text/javascript">
			del_css('dialog.css') ;
		</script>
	</head>
	<body style="font-size:16px;" onmousemove="vimofy_move_cur(event);" onmouseup="vimofy_mouseup();">
		<div class="bloc">
			<div style="width:100%;bottom:50%;top:0;position:absolute;background-color:#999;">
				<?php echo $_SESSION['vimofy'][$ssid]['vim_varout_tiny']->generate_vimofy(); ?>
			</div>
			<div style="width:100%;bottom:0;top:50%;position:absolute;background-color:#999;">
				<?php echo $_SESSION['vimofy'][$ssid]['vim_varout_tiny_ext']->generate_vimofy(); ?>
			</div>
		</div>
		<?php 
			$_SESSION['vimofy'][$ssid]['vim_varout_tiny']->vimofy_generate_js_body();
			$_SESSION['vimofy'][$ssid]['vim_varout_tiny_ext']->vimofy_generate_js_body();
		?>
	</body>
</html>