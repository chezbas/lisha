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
		<script type="text/javascript" src="../../../../../../ajax/common/ajax_generique_dev.js"></script>
		<script type="text/javascript">
			del_css('dialog.css') ;
			var ssid = '<?php echo $_GET['ssid']; ?>';
			function get_val_possible(id)
			{
				switch (id) {
					case '6':
						html =  '<table>';
						html += '<tr><td COLSPAN=2><b><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][251]); ?></b></td></tr>'; // Jour
						html += '<tr><td>d</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][243]); ?></td></tr>';
						html += '<tr><td>D</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][244]); ?></td></tr>';
						html += '<tr><td>j</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][245]); ?></td></tr>';
						html += '<tr><td>l</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][246]); ?></td></tr>';
						html += '<tr><td>N</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][247]); ?></td></tr>';
						html += '<tr><td>S</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][248]); ?></td></tr>';
						html += '<tr><td>w</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][249]); ?></td></tr>';
						html += '<tr><td>z</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][250]); ?></td></tr>';
						html += '<tr><td COLSPAN=2><b><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][252]); ?></b></td></tr>'; // Semaine
						html += '<tr><td>W</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][253]); ?></td></tr>';
						html += '<tr><td COLSPAN=2><b><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][254]); ?></b></td></tr>'; // Mois
						html += '<tr><td>F</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][255]); ?></td></tr>';
						html += '<tr><td>m</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][256]); ?></td></tr>';
						html += '<tr><td>M</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][257]); ?></td></tr>';
						html += '<tr><td>n</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][258]); ?></td></tr>';
						html += '<tr><td>t</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][259]); ?></td></tr>';
						html += '<tr><td COLSPAN=2><b><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][260]); ?></b></td></tr>'; // Année
						html += '<tr><td>L</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][261]); ?></td></tr>';
						html += '<tr><td>o</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][262]); ?></td></tr>';
						html += '<tr><td>Y</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][263]); ?></td></tr>';
						html += '<tr><td>y</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][264]); ?></td></tr>';
						html += '<tr><td COLSPAN=2><b><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][265]); ?></b></td></tr>'; // Heure
						html += '<tr><td>a</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][266]); ?></td></tr>';
						html += '<tr><td>A</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][267]); ?></td></tr>';
						html += '<tr><td>B</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][268]); ?></td></tr>';
						html += '<tr><td>g</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][269]); ?></td></tr>';
						html += '<tr><td>G</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][270]); ?></td></tr>';
						html += '<tr><td>h</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][271]); ?></td></tr>';
						html += '<tr><td>H</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][272]); ?></td></tr>';
						html += '<tr><td>i</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][273]); ?></td></tr>';
						html += '<tr><td>s</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][274]); ?></td></tr>';
						html += '<tr><td>u</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][275]); ?></td></tr>';
						html += '<tr><td COLSPAN=2><b><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][276]); ?></b></td></tr>'; // Fuseau horaire
						html += '<tr><td>e</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][277]); ?></td></tr>';
						html += '<tr><td>I</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][278]); ?></td></tr>';
						html += '<tr><td>O</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][279]); ?></td></tr>';
						html += '<tr><td>P</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][280]); ?></td></tr>';
						html += '<tr><td>T</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][281]); ?></td></tr>';
						html += '<tr><td>Z</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][282]); ?></td></tr>';
						html += '<tr><td COLSPAN=2><b><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][283]); ?></b></td></tr>'; // Date et heure
						html += '<tr><td>c</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][284]); ?></td></tr>';
						html += '<tr><td>r</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][285]); ?></td></tr>';
						html += '<tr><td>U</td><td><?php echo  str_replace("'","\'",$_SESSION[$_GET['ssid']]['message'][286]); ?></td></tr>';
						
					html += '</table>';	
						return html;
						break;
					default :
						return '';
						break;
				}

			}
		</script>
		<?php echo '<title>'.$_SESSION[$ssid]['message'][233].'</title>'; ?>
	</head>
	<body style="font-size:12px;">
		<div class="bloc">
			<table style="height:100%;border-spacing:0;width:100%;">
				<tr>
					<td style="border-right:1px solid #555;vertical-align:top;padding-right:6px;width: 200px;">
						<div style="background-image:url(window.png);background-repeat:no-repeat;height:60px;background-position:bottom;width:48px;margin: 0px auto 10px auto;"></div>
						<div style="font-weight:bold;"><?php echo  $_SESSION[$_GET['ssid']]['message'][234]; ?> :</div>
						<div>
							<select multiple="multiple" onclick="set_forme_champ(this.value);" style="height:200px;">
								<option value="1"><?php echo $_SESSION[$_GET['ssid']]['message'][237]; ?></option>
								<option value="2"><?php echo $_SESSION[$_GET['ssid']]['message'][238]; ?></option>
								<option value="3"><?php echo $_SESSION[$_GET['ssid']]['message'][239]; ?></option>
								<option value="4"><?php echo $_SESSION[$_GET['ssid']]['message'][240]; ?></option>
								<option value="5"><?php echo $_SESSION[$_GET['ssid']]['message'][241]; ?></option>
								<option value="6"><?php echo $_SESSION[$_GET['ssid']]['message'][242]; ?></option>
								<option value="7"><?php echo $_SESSION[$_GET['ssid']]['message'][287]; ?></option>
								<option value="8"><?php echo $_SESSION[$_GET['ssid']]['message'][288]; ?></option>
								<option value="9"><?php echo $_SESSION[$_GET['ssid']]['message'][289]; ?></option>
							</select>
						</div>
						<div style="bottom:0;position:absolute;">
							<div style="font-weight:bold;"><?php echo $_SESSION[$_GET['ssid']]['message'][235]; ?> :</div>
							<input type="text" size="30" id="forme_champ" size=15 style="margin:5px 10px 4px 0;"/>
							<div style="font-weight:bold;"><?php echo $_SESSION[$_GET['ssid']]['message'][456]; ?> :</div>
							<input type="text" size="30" readonly="readonly"  id="rendu" size=15 style="margin:5px 10px 4px 0;"/>
							<div style="background-color:red;width:16px;height:16px;float:right;margin-top:-23px;background-image:url(calcul.png);background-repeat:no-repeat;" onclick="render_field();"></div>
							<a href="../../../../../../../index.php?ID=aide" target="_blank" style="color:grey;background-image:url(help.png);background-repeat:no-repeat;padding:0 0 0 20px;height: 20px;line-height:20px;display:block;"><?php echo $_SESSION[$_GET['ssid']]['message'][236]; ?></a><br />
							<input type="submit" id="insert"  value="<?php echo $_SESSION[$_GET['ssid']]['message'][146]; ?>" onclick="inserer_texte();"/>
							<input type="button" id="cancel"  value="<?php echo $_SESSION[$_GET['ssid']]['message'][181]; ?>" onclick="tinyMCEPopup.close();" />
						</div>
					</td>
					<td style="border-left:1px solid #FFF;padding-left:6px;">
						<div id="val_possible" style="top:0;bottom:0;position:absolute;overflow:auto;"></div>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>