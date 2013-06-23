<?php
	$ssid = $_POST['ssid'];
	require('../session.php');
	
	require '../../../../../../includes/ifiche/vimofy/edit/tiny/init_liste_link_edit_ID.php';
	$style = $obj_vimofy_ID->vimofy_generate_header(true);

	header("Content-type: text/xml");
	echo "<?xml version='1.0' encoding='UTF8'?>";
	echo "<parent>";
	echo "<vimofy>".rawurlencode($obj_vimofy_ID->generate_lmod_form())."</vimofy>";
	echo "<header>".rawurlencode($obj_vimofy_ID->generate_lmod_header(false))."</header>";
	echo "<json>".rawurlencode($obj_vimofy_ID->vimofy_generate_js_body(true))."</json>";
	echo "<css>".rawurlencode($style)."</css>";
	echo "</parent>";
?>