<?php
	$ssid = $_POST['ssid'];
	require('../session.php');
	
	require '../../../../../../includes/ifiche/vimofy/edit/tiny/vim_link_param.php';
	$style = $obj_vimofy_link_param->vimofy_generate_header(true);

	header("Content-type: text/xml");
	echo "<?xml version='1.0' encoding='UTF8'?>";
	echo "<parent>";
	echo "<vimofy>".rawurlencode($obj_vimofy_link_param->generate_vimofy())."</vimofy>";
	echo "<json>".rawurlencode($obj_vimofy_link_param->vimofy_generate_js_body(true))."</json>";
	echo "<css>".rawurlencode($style)."</css>";
	echo "</parent>";
?>