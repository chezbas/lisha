<?php

	$ssid = $_POST['ssid'];
	require('../session.php');
	
	$object_id = $_POST['id_dst'];
	$object_version = $_POST['v_dst'];
	$object_type = $_POST['type_lien'];

	function get_ik_cartridge()
	{
		//IK_CARTRIDGE=15
		$_POST['url'];
	}
	
	switch($object_type)
	{
		case '__IFICHE__':
			echo '<div id="cartouche_3_0" class="conteneur_cartouche">
						<div style="cursor:pointer;" class="cart_head cart_head_info_ifiche" onclick="toggle_div(\'cartouche_info_lien_entete_conteneur_3_0\');">
							Appel de l\'<span style="font-weight:bold;color:red;">i</span>Fiche '.$object_id.' ';
			if($object_version == $_SESSION[$ssid]['message']['iknow'][504]) // SRX remove max by dynamic text in db
			{
				echo 'sans version ('.$_SESSION[$ssid]['objet_fiche']->get_max_version_of_object($object_type,$object_id).') - '.$_SESSION[$ssid]['objet_fiche']->get_title_of_object($object_type,$object_id,$object_version);
			}
			else
			{
				echo 'version '.$object_version.'/'.$_SESSION[$ssid]['objet_fiche']->get_max_version_of_object($object_type,$object_id).' - '.$_SESSION[$ssid]['objet_fiche']->get_title_of_object($object_type,$object_id,$object_version);
			} 
			
			echo '</div>
						<div id="cartouche_info_lien_entete_conteneur_3_0">
							<div id="info_lien_entete_en_colonne_3_0">
								<div class="cart_head cart_head_info_ifiche ikcur" style="border-top:none;" onclick="toggle_div(\'info_lien_en_colonne_3_0\');">
									<table width=100%>
										<tr>
											<td style="width:16px;">
												<div class="liste" style="float:left;margin:-3px 10px 0 0;"></div>
											</td>
											<td class="fleche_parametres_lien" style="font-family: Verdana, Arial;font-size: 10px;">
												<div class="droite" style="float:left;"></div>'.$_SESSION[$ssid]['message'][117].' (1)
											</td>
										</tr>
									</table>
								</div>
								<div class="info_lien" id="info_lien_en_colonne_3_0" style="padding: 0px 8px;">
									<table>
										<tr>
											<td>table_parametre</td>
											<td style="padding:0 15px;">&larr;</td>
											<td><span class="constante">\'DEP\'</span></td>
										</tr>
									</table>
								</div>
							</div>
							<div class="cart_head cart_head_info_ifiche ikcur" style="border-top:none;" onclick="toggle_div(\'info_lien_param_en_colonne_3_0\');">
								<table width=100%>
									<tr>
										<td style="width:16px;">
											<div class="liste" style="float:right;margin:-3px 10px 0 0;"></div>
										</td>
										<td class="arrow_varin_link" style="font-family: Verdana, Arial;font-size: 10px;">'.$_SESSION[$ssid]['message'][45].' (1)</td>
									</tr>
								</table>
							</div>
							<div class="info_lien" style="/*display:none;*/padding: 0px 8px;" id="info_lien_param_en_colonne_3_0">
								<table>
									<tr>
										<td><span class="BBVarExt">VT_PARAM4</span></td>
										<td style="padding:0 15px;">VT_PARAM4</td>
									</tr>
								</table>
							</div>
						</div>
					</div>';
				break;
	case '__ICODE__':
		echo '<div id="cartouche_3_0" class="conteneur_cartouche">
			<div style="cursor:pointer;" class="cart_head cart_head_info_icode" style="border-top:none;" onclick="toggle_div(\'cartouche_info_lien_entete_conteneur_3_0\');">
							Appel de l\'<span style="font-weight:bold;color:red;">i</span>Fiche '.$object_id.' ';
			if($object_version == $_SESSION[$ssid]['message']['iknow'][504]) // SRX remove max by dynamic text in db
			{
				echo 'sans version ('.$_SESSION[$ssid]['objet_fiche']->get_max_version_of_object($object_type,$object_id).') - '.$_SESSION[$ssid]['objet_fiche']->get_title_of_object($object_type,$object_id,$object_version);
			}
			else
			{
				echo 'version '.$object_version.'/'.$_SESSION[$ssid]['objet_fiche']->get_max_version_of_object($object_type,$object_id).' - '.$_SESSION[$ssid]['objet_fiche']->get_title_of_object($object_type,$object_id,$object_version);
			} 
			
			echo '</div>
						<div id="cartouche_info_lien_entete_conteneur_3_0">
							<div id="info_lien_entete_en_colonne_3_0">
								<div class="cart_head cart_head_info_icode ikcur" style="border-top:none;" onclick="toggle_div(\'info_lien_en_colonne_3_0\');">
									<table width=100%>
										<tr>
											<td style="width:16px;">
												<div class="liste" style="float:left;margin:-3px 10px 0 0;"></div>
											</td>
											<td class="fleche_parametres_lien" style="font-family: Verdana, Arial;font-size: 10px;">
												<div class="droite" style="float:left;"></div>'.$_SESSION[$ssid]['message'][117].' (1)
											</td>
										</tr>
									</table>
								</div>
								<div class="info_lien" id="info_lien_en_colonne_3_0" style="padding: 0px 8px;">
									<table>
										<tr>
											<td>table_parametre</td>
											<td style="padding:0 15px;">&larr;</td>
											<td><span class="constante">\'DEP\'</span></td>
										</tr>
									</table>
								</div>
							</div>
							<div class="cart_head cart_head_info_icode ikcur" style="border-top:none;" onclick="toggle_div(\'info_lien_param_en_colonne_3_0\');">
								<table width=100%>
									<tr>
										<td style="width:16px;">
											<div class="liste" style="float:right;margin:-3px 10px 0 0;"></div>
										</td>
										<td class="arrow_varin_link" style="font-family: Verdana, Arial;font-size: 10px;">'.$_SESSION[$ssid]['message'][45].' (1)</td>
									</tr>
								</table>
							</div>
							<div class="info_lien" style="/*display:none;*/padding: 0px 8px;" id="info_lien_param_en_colonne_3_0">
								<table>
									<tr>
										<td><span class="BBVarExt">VT_PARAM4</span></td>
										<td style="padding:0 15px;">VT_PARAM4</td>
									</tr>
								</table>
							</div>
						</div>
					</div>';
			break;
	}
?>