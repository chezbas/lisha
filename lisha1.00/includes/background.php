<?php
$image = imagecreatetruecolor( 200 , 100);
	imagesavealpha( $image, true );
	$bg = imagecolorallocatealpha($image, 255, 255, 255, 0);
	imagefilledrectangle ($image, 0, 0, 200, 100, $bg);
	$textcolor = imagecolorallocate( $image, 0, 0, 0 );
//	imagestring ( $image, 10, 0 , 9, "Paramètres", $textcolor );       
//	imagestring ( $image, 15, 10 , 30, "d'entrée", $textcolor );

	//imagettftext($image,15,45,20,90,$textcolor,'arial.ttf','Paramètres');
	//imagettftext($image,15,45,50,100,$textcolor,'arial.ttf','d\'Entrée');
	imagettftext($image,15,45,20,90,$textcolor,'arial.ttf','Informations');
	imagettftext($image,15,45,50,100,$textcolor,'arial.ttf','récupérées');


	header("Content-type: image/png");
	Imagepng($image);