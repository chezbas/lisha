<?php 

	if(isset($_GET['url']) && $_GET['url'] != 'false')
	{
		require('edit_url.php');
	}
	else
	{
		require('new_url.php');
	}

?>