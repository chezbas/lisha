<?php 
	require('../session.php');
	
	// Clear the url temp for the ID TEMP
	$_SESSION[$ssid]['objet_fiche']->clear_url_temp();
	
	// Parse the URL & insert params in database
	if($_GET['url'] != 'false')
	{
	
	}
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
		<link rel="stylesheet" href="../../../../../../css/common/iknow/iknow_onglet.css" type="text/css">
		<link rel="stylesheet" href="../../../../../../css/ifiche/common_fiche.css" type="text/css"/>
		<style type="text/css">
		.onglets
		{
			overflow:hidden;
			top:0;
			bottom:0;
			left:0;
			right:0;
			background-color:#CCC;
			position:absolute;
		}
		
		.onglets_step
		{
			overflow:hidden;
			top:20px;
			bottom:0;
			left:0;
			right:0;
			position:absolute;
		}
		
		
		</style>
		<script type="text/javascript" src="../../../../../../js/common/iknow/iknow_onglet.js"></script>
		
		<?php require('function_dynamic_js.php');?>
		
		<script type="text/javascript" src="function.js"></script>
		<script type="text/javascript" src="../../../../../../ajax/common/ajax_generique_dev.js"></script>
		<script type="text/javascript" src="../../../tiny_mce_popup.js"></script>
		<script type="text/javascript" src="../js/iknow.js"></script>
		<script type="text/javascript">
			<?php  
				echo 'var ssid="'.$_GET['ssid'].'";';  
				echo 'var id_step="'.$_GET['id_step'].'";'; 
			?>
		</script>
		<?php 
			echo '<title>'.$_SESSION[$ssid]['message'][165].'</title>';
			/**==================================================================
			 * Vimofy init
			 ====================================================================*/	
			require('../../../../../../includes/ifiche/vimofy/edit/tiny/vim_link_param.php');
			/*===================================================================*/	
			
			/*==================================================================
			* Vimofy internal init
			====================================================================*/  
			$_SESSION['vimofy'][$ssid]['vim_link_param_tiny']->generate_public_header();   
			/*===================================================================*/    
		?>
		<script type="text/javascript">
			del_css('dialog.css') ;
		</script>
	</head>
	<body onmousemove="vimofy_move_cur(event);" onmouseup="vimofy_mouseup();" onmousedown="vimofy_mousedown(event);">
		<div class="bloc">
			<div style="border-bottom:1px solid #999;">
			<table>
					<tr>
						<td style="width:110px;font-weight:bold;"><?php echo $_SESSION[$ssid]['message'][425]; ?> :</td><td><input type="radio" id="rd_ifiche" name="iobject" onclick="clear_elements();load_vim_id(__IFICHE__);"/> <?php echo $_SESSION[$ssid]['message'][502]; ?></td>
						<td></td><td><input type="radio" id="rd_icode" name="iobject" onclick="clear_elements();load_vim_id(__ICODE__);"/> <?php echo $_SESSION[$ssid]['message'][503]; ?></td>
					</tr>
				</table>
			</div>
			<div style="border-top:1px solid #FFF;border-bottom:1px solid #999;">
				<table>
					<tr>
						<td style="width:110px;font-weight:bold;height:24px;"><?php echo $_SESSION[$ssid]['message'][46]; ?> :</td><td><div id="vimofy_iobjet_id"></div></td>
					</tr>
				</table>
			</div>
			<div style="border-top:1px solid #FFF;border-bottom:1px solid #999;">
				<table>
					<tr>
						<td style="width:110px;font-weight:bold;height:24px;"><?php echo $_SESSION[$ssid]['message'][48]; ?> :</td><td><div id="vimofy_iobjet_version"></div></td>
					</tr>
				</table>
			</div>
			<div style="border-top:1px solid #FFF;border-bottom:1px solid #999;height:398px;">
				<div style="visibility:hidden;height:100%;" id="url_param">
					<table style="height:100%;width:100%;">
						<tr>
							<td style="width:110px;font-weight:bold;"><?php echo $_SESSION[$ssid]['message'][310]; ?> :</td>
							<td style="height:100%;">
								<div style="width:100%;height:100%;position:relative;" id="vim_link_param_tiny">
								</div>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div id="div_options" style="border-top:1px solid #FFF;border-bottom:1px solid #999;height:132px;display:none;">
				<div id="tabbar" style="position:relative;height:96%;"></div>
			</div>
			<div style="border-top:1px solid #FFF;"></div>
			<div style="position:absolute;bottom:0;right:5px;">
				<input type="button"  value="<?php echo $_SESSION[$ssid]['message'][67]; ?>" onclick="tinyMCEPopup.close();"/>
				<input type="button" disabled="disabled" id="ikcalc_insert_button" value="<?php echo $_SESSION[$ssid]['message'][146]; ?>" onclick="insert_url();"/>
			</div>
		</div>
	</body>
</html>