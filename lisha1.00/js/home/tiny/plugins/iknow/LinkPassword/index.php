<?php 
	session_name($_GET['ssid']);
	session_start(); 

	if($_GET['url'] == 'false')
	{
		// New
		include 'password_new.php';
	}
	else
	{
		// Edit
		include 'password_edit.php';
	}
?>