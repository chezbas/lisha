<?php
	/**==================================================================
	 * Build or return only ssid identifier for each window
	 * Start php session with ssid name
	 ====================================================================*/
	// check if ssid already exists in URL
	if( !isset($_GET["ssid"]) )
	{ 
		if (count($_GET) > 0)
		{
			$w_digit = '&';
		}
		else
		{
			$w_digit = '?';
		}
		$new_ssid = __PREFIX_URL_COOKIES__.sha1(mt_rand().microtime()).mt_rand();
		//==================================================================
		// Recall, if needed current page with ssid identifier
		//==================================================================
		echo '<html>
				<head>
					<script language="javascript" >
					chaine = document.location.href + "'.$w_digit.'ssid=" + "'.$new_ssid.'";
	      			window.location.href = chaine;
	      			</script>
	      		</head>
	      	  	<body>
	      	  	</body>
	      	  </html>';
		//==================================================================
		die();
	}
	
	$ssid = $_GET["ssid"];
	/**==================================================================
	 * Start session on ssid name
	 ====================================================================*/
	require('active_session.php');
	/*===================================================================*/	
?>