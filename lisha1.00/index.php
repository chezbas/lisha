<?php
	/**==================================================================
	 * Set of framework include
	 ====================================================================*/
	require('header.php');
	/*===================================================================*/		


	// Define edit mode for each tree in page
	if(!isset($_SESSION[$ssid]['lisha']['doc']['tree']['user']))
	{
		$_SESSION[$ssid]['MT']['tree']['id']['ikdoc']["edit_mode"] = false;
	}
	else
	{
		if(!($_SESSION[$ssid]['lisha']['doc']['tree']['user']))
		{
			$_SESSION[$ssid]['MT']['tree']['id']['ikdoc']["edit_mode"] = false;
		}
		else
		{
			$_SESSION[$ssid]['MT']['tree']['id']['ikdoc']["edit_mode"] = true;
		}
	}

	//==================================================================
	// Get ID of page
	//==================================================================
	if(isset($_GET["id"]))
	{
		if(!is_numeric($_GET["id"]))
		{
			error_log_details('fatal','you need an numeric id');
			die();
		}
		else
		{
			$id_page= $_GET["id"];
		}
	}
	else
	{
		if(!isset($_SESSION[$ssid]['MT']['current_read_page']))
		{
			// Default page
			$id_page= '1'; // You need at last node 1 define in your tree
		}
		else
		{
			$id_page = $_SESSION[$ssid]['MT']['current_read_page'];
		}
	}
	//==================================================================	

	// Page language equal magic tree language
	$_SESSION[$ssid]['langue'] = $_SESSION[$ssid]['lisha']['langue'];
?>
<html>
	<head>
		<META http-equiv="Content-Type" content="text/html; charset=UTF-8">	
		<script type="text/javascript">
			var language = '<?php echo $_SESSION[$ssid]['langue']; ?>';
			var ssid = '<?php echo $ssid; ?>';
		</script>
		<link rel="stylesheet" href="css/home/index.css" type="text/css"> <!-- * load custom page style * -->
		<link rel="stylesheet" href="css/home/tiny_details.css" type="text/css"> <!-- * load custom tiny page style * -->

		<script type="text/javascript" src="ajax/common/ajax_generique_dev.js"></script>
		<script type="text/javascript" src="js/common/session_management.js"></script>
		<script type="text/javascript" src="js/home/tiny/tiny_mce.js"></script> <!-- TinyMCE for documentation -->

		<script type="text/javascript" src="js/common/ClassVar.js"></script> <!-- * Include javascript main var class definition * -->
		<script type="text/javascript" src="js/common/ClassQuartz.js"></script> <!-- * Include timer Class * -->
		<script type="text/javascript" src="js/common/ClassOpacity.js"></script> <!-- * Include javascript Opacity class definition * -->
		<script type="text/javascript" src="js/common/cookie.js"></script>
		<script type="text/javascript" src="js/common/informations.js"></script>	
		<script type="text/javascript" src="js/common/time.js"></script>	
		<script type="text/javascript" src="js/home/index.js"></script> <!-- Specific javascript of current page -->
		<script type="text/javascript">

			var MainTimer = new Class_timer();

			MainTimer.init(30,"T1");

			MainTimer.add_event(50,"blink()");
			<?php
			/*if(!$_SESSION[$ssid]['MT']['tree']['id']['ikdoc']["edit_mode"])
			{
				//echo 'MainTimer.add_event(2,"bounce_tool_bar(20)");';
			}
			*/
			?>

			MainTimer.start();		

			var libelle_common = Array();

			// Up time session javascript variable
			<?php 
				$gc_lifetime = ini_get('session.gc_maxlifetime'); 
				$end_visu_date  = time() + $gc_lifetime;
				$end_visu_time = $end_visu_date;
				$end_visu_date = date('m/d/Y',$end_visu_date);
				$end_visu_time = date('H:i:s',$end_visu_time);
			?>
			var end_visu_date = '<?php echo $end_visu_date; ?>';
			var end_visu_time = '<?php echo $end_visu_time; ?>';
			function over(p_txt)
			{
				document.getElementById('help').innerHTML = p_txt;
			}

			function unset_text_help()
			{
				document.getElementById('help').innerHTML = '';
			}
		</script>
		<?php 
			//==================================================================
			// Common tree HTML header generation
			//==================================================================
			itree::generate_common_html_header(	$link_mt,
												$ssid,
												__MAGICTREE_TABLE_TEXT__,
												__MAGICTREE_TABLE_SETUP__,
												__MAGICTREE_APPLICATION_RELEASE__,
												$_SESSION[$ssid]['langue']
												);
			//==================================================================

			/**==================================================================
			 * MagicTree definition of each tree in page
			 ====================================================================*/	
			include ('includes/MTSetup/define/MT_user_doc.php');
			$_SESSION[$ssid]['MT'][$mt_user_id]->generate_html_header();
			/*===================================================================*/

			/**==================================================================
			 * Load custom doxumentation screen text in both php session and javascript
			 * Warning : Must be in <head> html bloc 
			 ====================================================================*/	
			$type_screen = 'user';
			$_SESSION[$ssid]['lisha']['langue'] = $_SESSION[$ssid]['langue'];
			require('./includes/common/textes.php');
			echo chr(10);
			echo '</script>';
			/*===================================================================*/
			?>
		<title><?php echo $_SESSION[$ssid]['lisha']['page_text'][2]['TX'];?> </title>
	</head>
	<body onload="init_load('<?php echo $_SESSION[$ssid]['lisha']['langue_TinyMCE'];?>','<?php echo $id_page;?>');<?php echo 'MagicTree_'.$mt_user_id.'()'?>;">
		<!-- ================================================== MSGBOX ================================================= -->	
		<div id="iknow_msgbox_background"></div>
		<div id="iknow_msgbox_conteneur" style="display:none;"></div>
		<!-- ===============================================  END MSGBOX ==============================================  -->
		<div id="ikdoc" style="width:500px;"></div><!-- SAME IN INDEX.CSS AND INDEX.JS CONFIGURATION  -->
		<div id="gauche" style="float: left; width:30%; height:100%; display:block;"></div>
		<div id="main_details" style="float: right;">
			<div id="headdetails" class="headdetails" <?php if(!$_SESSION[$ssid]['MT']['tree']['id']['ikdoc']["edit_mode"]) echo 'onclick=\'MainTimer.add_event(1,"reduce_tool_bar()");\'';echo 'MagicTree_'.$mt_user_id.'();';?> style="height: 0px;">
				<?php
				echo '<div class="welcome">'.$_SESSION[$ssid]['lisha']['page_text'][2]['TX'].'</div>';
				if($_SESSION[$ssid]['MT']['tree']['id']['ikdoc']["edit_mode"])
				{
				echo '
				<div class="boutton_security_off" onClick="user_tree_mode();" onmouseout="lib_hover(\'\')" onmouseover="lib_hover(\''.js_protect($_SESSION[$ssid]['lisha']['page_text'][6]['TX']).'\')"></div>
				<div id="tool_button_edit" class="tool_button_edit">
				<div id="boutton_edit" class="boutton_edit" onClick="show_tiny(\''.$_SESSION[$ssid]['langue'].'\')" onmouseout="lib_hover(\'\')" onmouseover="lib_hover(\''.js_protect($_SESSION[$ssid]['lisha']['page_text'][4]['TX']).'\')"></div>
					<div id="boutton_back" class="boutton_back" onClick="html_detail_display(\'ikdoc\')" onmouseout="lib_hover(\'\')" onmouseover="lib_hover(\''.js_protect($_SESSION[$ssid]['lisha']['page_text'][5]['TX']).'\')"></div>
					</div>
					';	
				}
				else
				{
					echo '
					<div class="boutton_security" onmouseout="lib_hover(\'\')" onmouseover="lib_hover(\''.js_protect($_SESSION[$ssid]['lisha']['page_text'][3]['TX']).'\')" onClick="user_tree_mode();"></div>
					';
				}
				?>
			</div>
			<div id="slideh" onclick="active_expand_tools_bar()"></div>
			<div id="details"></div><!-- HTML RESULT -->
			<form method="post" action="javascript:sauvegarder(ssid,'<?php echo $_SESSION[$ssid]['langue'];?>');">
			<div id="details_tiny" style="width: 100%; height: 100%;">
					<textarea id="elm1" name="elm1" rows="40" cols="90" style="width: 100%;">XX</textarea>
			</div>
			</form>
		</div>
		<div id="slidev" style="left:500px;" onclick="active_expand_navigation_tree()"></div>
		<!-- ========================================== END BARRE INFORMATIONS ===================================== -->
			<div id="footer" style="z-Index:500;"><div id="txt_help" class="txt_help">...</div></div>

		<!-- ============================================= END FOOTER ================================================= -->
		<script type="text/javascript">
		read_details('<?php echo $mt_user_id;?>','<?php echo $id_page; ?>','U',''); // First load
		<?php 
		if($_SESSION[$ssid]['MT']['tree']['id']['ikdoc']["edit_mode"])
		{
			echo 'document.getElementById(\'headdetails\').style.height = "20px";';
			//echo 'resize_details();';			
		}
		?>
		</script>
	</body>
</html>